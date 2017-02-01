<?php

namespace Hatchly\GoogleAnalytics;

use Route;
use Illuminate\Http\Request;
use Hatchly\Settings\Setting;
use Hatchly\Core\Admin\Controller as BaseController;
use Hatchly\GoogleAnalytics\Services\GoogleAnalyticsService;

class GoogleAnalyticsController extends BaseController
{
    protected $analyticsService;

    public function __construct(GoogleAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    // The callback that Google's OAuth 2 server will redirect to
    public function oauthCallback(Request $request)
    {
        $analyticsSettingsPage = config('hatchly.core.admin-url') . '/settings/analytics';
        $authCode = $request->get('code') ? $request->get('code') : '';

        $settingAuth = Setting::firstOrNew(['key' => 'analytics.oauth-authorisation-code']);
        $settingAuth->value = $authCode;
        $settingAuth->save();

        $this->analyticsService->triggerLogin();

        if (!empty($authCode)) {
            return redirect($analyticsSettingsPage)
                ->withSuccesses('OAuth 2 authorisation code has been set successfully');
        } else {
            return redirect($analyticsSettingsPage)
                ->withNotices('OAuth 2 authorisation code was not provided');
        }
    }

    // Remove authorisation and clear settings values
    public function deauth()
    {
        $this->analyticsService->deauthorise();

        $settingAuth = Setting::firstOrNew(['key' => 'analytics.oauth-authorisation-code']);
        $settingAuth->value = '';
        $settingAuth->save();

        $settingAuth = Setting::firstOrNew(['key' => 'analytics.oauth-token']);
        $settingAuth->value = '';
        $settingAuth->save();

        return redirect()->back()->withSuccesses('Deauthorised the app successfully');
    }
}
