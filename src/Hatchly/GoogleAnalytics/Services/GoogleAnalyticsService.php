<?php

namespace Hatchly\GoogleAnalytics\Services;

use Cache, Exception, Google_Client, Google_Service_Analytics;
use Hatchly\Settings\Setting;
use Illuminate\Support\Facades\URL;

class GoogleAnalyticsService
{
    public $month;
    public $week;
    public $yesterday;
    public $today;
    public $exception;
    public $error;

    protected $client;
    protected $analytics;
    protected $token;

    // Returns a list of analytics profiles on the connected account
    // Returns empty array if no profiles found
    public function getProfiles()
    {
        $this->makeClient();

        $profileList = [];

        try {
            $accounts = $this->analytics->management_accounts->listManagementAccounts();
            foreach ($accounts->getItems() as $account) {
                $accountId = $account->getId();
                $properties = $this->analytics->management_webproperties->listManagementWebproperties($accountId);
                foreach ($properties->getItems() as $property) {
                    $propertyId = $property->getId();
                    $profiles = $this->analytics->management_profiles->listManagementProfiles($accountId, $propertyId);
                    foreach ($profiles->getItems() as $profile) {
                        $profileList[$profile->id] = $profile->name;
                    }
                }
            }
        } catch(Exception $e) {
            $this->error = $e->getMessage();
            return $profileList;
        }

        if (!$profileList) {
            $this->error = 'No Google Analytics profiles were found';
        }

        return $profileList;
    }


    public function fetchStats()
    {
        try {
            $this->month = $this->getStats('month', '30daysAgo', 'today');
            $this->week = $this->getStats('week', '7daysAgo', 'today');
            $this->yesterday = $this->getStats('yesterday', 'yesterday', 'today');
            $this->today = $this->getStats('today', 'today', 'today');
        } catch (Exception $e) {
            $this->error = $e;
        }
        return $this;
    }

    // Get the URL to request authorisation for OAuth 2
    public function getAuthUrl()
    {
        $this->makeClient();
        return $this->client->createAuthUrl();
    }

    // Revokes OAuth 2 token and deletes auth code
    public function deauthorise()
    {
        $this->makeClient();
        $this->client->revokeToken();

        Setting::find([
            'analytics.oauth-authenticated',
            'analytics.oauth-authorisation-code',
            'analytics.oauth-token'
        ])->each(function ($item, $key) {
            $item->delete();
        });
    }

    // Gets the profile ID from settings
    private function getProfileId()
    {
        $profileId = setting('analytics.analytics-profile');
        if (!$profileId) {
            $this->error = 'Please select a profile from the Hatchly Google Analytics settings page';
        }
        return $profileId;
    }

    // Create Google API client and request token if necessary
    private function makeClient()
    {
        if ($this->client) {
            return;
        }

        $this->client = new Google_Client();
        $this->client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
        $this->client->setAuthConfig(base_path('gapi-client.json'));
        $this->client->setApplicationName('Hatchly Google Analytics');
        $this->client->setRedirectUri(
            URL::to(config('hatchly.core.admin-url') . '/settings/analytics/oauth')
        );

        $this->analytics = new Google_Service_Analytics($this->client);

        $authCode = setting('analytics.oauth-authorisation-code');
        if (!$authCode) {
            // Not yet logged in
            return;
        }
        
        // Handle token request/refresh
        try {
            $settingToken = Setting::firstOrNew(['key' => 'analytics.oauth-token']);
            if ($settingToken->value) {
                if ($this->client->isAccessTokenExpired()) {
                    $token = $this->client->fetchAccessTokenWithRefreshToken($settingToken->value);
                }
            } else {
                $token = $this->client->fetchAccessTokenWithAuthCode($authCode);
            }
            if (isset($token['access_token'])) {
                // TODO: Investigate how/where this should be stored.
                // It probably doesn't need to be a Setting
                $settingToken->value = json_encode($token);
                $settingToken->save();
            }
            if ($settingToken->value) {
                $this->client->setAccessToken($settingToken->value);
            }
        } catch(Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    protected function getStats($type, $end, $start)
    {
        $class = $this;

        return Cache::remember('ga-' . $type, 120, function () use ($class, $start, $end) {

            $class->makeClient();

            $profileId = $this->getProfileId();
            if (!$profileId) {
                return 0;
            }

            $metrics = 'ga:sessions,ga:users,ga:pageviews,ga:bounceRate,ga:organicSearches,ga:pageviewsPerSession,ga:avgTimeOnPage,ga:avgPageLoadTime,ga:avgSessionDuration';
            $keys = explode(',', str_replace('ga:', '', $metrics));
            // ['dimensions' => 'ga:day']
            $rows = $this->analytics->data_ga->get('ga:' . $profileId, $end, $start, $metrics)->getRows();
            foreach ($rows[0] as $i => $row) {
                $data[$keys[$i]] = is_float(+$row) ? number_format($row, 2) : number_format($row);
            }
            return $data;

        });
    }
}
