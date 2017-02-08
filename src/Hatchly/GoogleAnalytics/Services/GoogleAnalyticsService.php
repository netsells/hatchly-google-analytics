<?php

namespace Hatchly\GoogleAnalytics\Services;

use Cache;
use Exception;
use Google_Client;
use Google_Service_Analytics;
use Hatchly\Settings\Setting;
use Illuminate\Support\Facades\URL;

class GoogleAnalyticsService
{
    public $metrics = [
        'Sessions' => 'sessions',
        'Users' => 'users',
        'Page Views' => 'pageviews',
        'Bounce Rate' => 'bounceRate',
        'Organic Searches' => 'organicSearches',
        'Views Per Session' => 'pageviewsPerSession',
        'Time On Page' => 'avgTimeOnPage',
        'Page Load Time' => 'avgPageLoadTime',
        'Session Duration' => 'avgSessionDuration',
    ];

    protected $client;
    protected $analytics;
    protected $error;

    public function getError()
    {
        if ($this->error) {

            // Check if the error is in JSON format
            if (($errorJson = json_decode($this->error)) !== null) {

                return isset($errorJson->error->message) ? $errorJson->error->message : 'An unexpected error occurred';
            }

            return $this->error;
        }
        return '';
    }

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
        } catch (Exception $e) {

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
        return [
            'This Month' => $this->getStats('month', '30daysAgo', 'today', $this->metrics),
            'This Week' => $this->getStats('week', '7daysAgo', 'today', $this->metrics),
            'Yesterday' => $this->getStats('yesterday', 'yesterday', 'today', $this->metrics),
            'Today' => $this->getStats('today', 'today', 'today', $this->metrics),
        ];
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
        $this->client->setClientId('383323737772-0gg6vjt7nft8f1pf75u2t4kfg2j4ag54.apps.googleusercontent.com');
        $this->client->setClientSecret('ikQH-1m046-a3rRti51--XiH');
        $this->client->setApplicationName('Hatchly Google Analytics');
        $this->client->setState(url()->current());
        $this->client->setRedirectUri(route('hatchly.settings.analytics.oauth'));

        $this->analytics = new Google_Service_Analytics($this->client);

        $authCode = setting('analytics.oauth-authorisation-code');
        if (!$authCode) {
            // Not yet logged in
            return;
        }

        // Handle token request
        try {

            $settingToken = Setting::firstOrNew(['key' => 'analytics.oauth-token']);
            if ($settingToken->value) {

                $this->client->setAccessToken($settingToken->value);

                if ($this->client->isAccessTokenExpired()) {

                    $settingToken->value = '';
                    $settingToken->save();
                    return redirect($this->client->getAuthUrl());
                }
            } else {

                $token = $this->client->fetchAccessTokenWithAuthCode($authCode);

                $settingToken->value = json_encode($token);
                $settingToken->save();
            }

        } catch (Exception $e) {

            $this->error = $e->getMessage();
        }
    }

    // $type is a descriptive name for caching
    // $start and $end define the time/date range to be retrieved
    // $metrics is an array of analytics metrics to retrieve
    protected function getStats($type, $end, $start, $metrics)
    {
        $class = $this;

        $getData = function () use ($class, $start, $end, $metrics) {

            $class->makeClient();

            $profileId = $this->getProfileId();
            if (!$profileId) {
                return [];
            }

            // ['dimensions' => 'ga:day']
            $metrics = 'ga:' . implode(',ga:', $metrics);

            // Attempt to retrieve analytics data
            try {

                $rows = $class->analytics->data_ga->get('ga:' . $profileId, $end, $start, $metrics)->getRows();
            
            } catch (Exception $e) {

                $class->error = $e->getMessage();
                return [];
            }

            // Organise the data returned into a nicer format
            foreach ($rows[0] as $i => $row) {

                $metrics = array_values($class->metrics);
                $data[$metrics[$i]] = is_float(+$row) ? number_format($row, 2) : number_format($row);
            }

            return $data;
        };

        if (setting('analytics.cache-duration')) {

            return Cache::remember('ga---' . $type, setting('analytics.cache-duration'), $getData);
        
        } else {

            return $getData();
        }
    }
}
