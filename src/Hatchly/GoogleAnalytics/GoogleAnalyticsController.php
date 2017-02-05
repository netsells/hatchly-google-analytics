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

        $setting = Setting::firstOrNew(['key' => 'analytics.oauth-authorisation-code']);
        $setting->value = $authCode;
        $setting->save();

        $setting = Setting::firstOrNew(['key' => 'analytics.oauth-authenticated']);

        if (!empty($authCode)) {
            $setting->value = 1;
            $setting->save();
            return redirect($analyticsSettingsPage)
                ->withSuccesses('OAuth 2 authorisation code has been set successfully');
        } else {
            $setting->value = 0;
            $setting->save();
            return redirect($analyticsSettingsPage)
                ->withNotices('OAuth 2 authorisation code was not provided');
        }
    }

    // Remove authorisation and clear settings values
    public function deauth()
    {
        $this->analyticsService->deauthorise();

        return redirect()->back()->withSuccesses('Deauthorised the app successfully');
    }
}
