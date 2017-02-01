<?php

namespace Hatchly\GoogleAnalytics\Services;

use Cache, Exception, Google_Client, Google_Service_Analytics;
use Hatchly\Settings\Setting;

class GoogleAnalyticsService
{
    public $month;
    public $week;
    public $yesterday;
    public $today;
    public $exception;

    protected $client;
    protected $analytics;
    protected $token;

    public function fetch()
    {
        try {
            $this->month = $this->getPageViews('month', '30daysAgo', 'today');
            $this->week = $this->getPageViews('week', '7daysAgo', 'today');
            $this->yesterday = $this->getPageViews('yesterday', 'yesterday', 'today');
            $this->today = $this->getPageViews('today', 'today', 'today');
            return $this;
        } catch (Exception $e) {
            $this->exception = $e;
            return $this;
        }
    }

    public function getAuthUrl()
    {
        $this->makeClient(true);
        return $this->client->createAuthUrl();
    }

    public function deauthorise()
    {
        $this->makeClient();
        $this->client->revokeToken();
    }

    public function triggerLogin()
    {
        $this->makeClient();
    }

    private function getFirstProfileId()
    {
        $accounts = $this->analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            $properties = $this->analytics->management_webproperties->listManagementWebproperties($firstAccountId);

            if (count($properties) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                $profiles = $this->analytics->management_profiles->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    return $items[0]->getId();
                } else {
                    throw new Exception("No Google Analytics views (profiles) found for this user.");
                }
            } else {
                throw new Exception("No Google Analytics properties found for this user.");
            }
        } else {
            throw new Exception("No Google Analytics accounts found for this user.");
        }
    }

    private function makeClient($skipTokenCheck = false)
    {
        $this->client = new Google_Client();
        $this->client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
        // TODO: Make the below hard coded values dynamic
        $this->client->setAuthConfig(__DIR__.'/../authconfig.json');
        $this->client->setApplicationName('Hatchly Google Analytics');
        $this->client->setRedirectUri('http://hatchly.dev/admin/settings/analytics/oauth');
        $this->analytics = new Google_Service_Analytics($this->client);

        if ($skipTokenCheck) {
            return;
        }

        $settingAuth = Setting::firstOrNew(['key' => 'analytics.oauth-authorisation-code']);
        $settingToken = Setting::firstOrNew(['key' => 'analytics.oauth-token']);

        // Manage access token
        if ($settingAuth && $settingAuth->value) {
            if ($settingToken && $settingToken->value) {
                if ($this->client->isAccessTokenExpired()) {
                    $token = $this->client->fetchAccessTokenWithRefreshToken($settingToken->value);
                    if (isset($token['access_token'])) {
                        $settingToken->value = json_encode($token);
                        $settingToken->save();
                    }
                }
            } else {
                $token = $this->client->fetchAccessTokenWithAuthCode($settingAuth->value);
                if (isset($token['access_token'])) {
                    $settingToken->value = json_encode($token);
                    $settingToken->save();
                }
            }
            if ($settingToken->value) {
                json_decode($this->client->setAccessToken($settingToken->value));
            }
        }
    }

    protected function getPageViews($type, $end, $start)
    {
        $class = $this;

        return Cache::remember('ga-' . $type, 120, function () use ($class, $start, $end) {

            $class->makeClient();
            $firstProfileId = $class->getFirstProfileId();

            return $this->analytics->data_ga->get('ga:' . $firstProfileId, $end, $start, 'ga:users')->getRows()[0][0];
        });
    }
}
