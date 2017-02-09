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
        $redirectUrl = $request->get('state');
        $authCode = $request->get('code', '');

        $setting = Setting::firstOrNew(['key' => 'analytics.oauth-authorisation-code']);
        $setting->value = $authCode;
        $setting->save();

        $setting = Setting::firstOrNew(['key' => 'analytics.oauth-authenticated']);

        if ($authCode) {

            $setting->value = 1;
            $setting->save();

            return redirect($redirectUrl);
        }

        $setting->value = 0;
        $setting->save();
        
        return redirect($redirectUrl)
            ->withNotices('OAuth 2 authorisation code was not provided');
    }

    // Remove authorisation and clear settings values
    public function deauth()
    {
        $this->analyticsService->deauthorise();

        return redirect()->back()->withSuccesses('Deauthorised the app successfully');
    }
}
