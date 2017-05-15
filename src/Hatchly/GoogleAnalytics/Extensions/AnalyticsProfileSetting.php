<?php

namespace Hatchly\GoogleAnalytics\Extensions;

use Hatchly\Settings\ExtensionableInterfaces\BaseSetting;
use Hatchly\Settings\ExtensionableInterfaces\SettingInterface;
use Hatchly\Settings\Setting;
use Hatchly\GoogleAnalytics\Services\GoogleAnalyticsService;

class AnalyticsProfileSetting extends BaseSetting implements SettingInterface
{
    public $analyticsService;

    public function __construct(GoogleAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function pageKey()
    {
        return 'analytics';
    }

    public function extensionableKey()
    {
        return 'analytics.analytics-profile';
    }

    public function viewPath()
    {
        return 'hatchly-analytics::extensions.settings.analytics-profile.view';
    }

    public function defaultValue()
    {
        return '';
    }

    public function view(Setting $setting = null)
    {
        if (!setting('analytics.oauth-authenticated')) {
            // Don't show setting if not authenticated with Google
            return '';
        }

        $profiles = $this->analyticsService->getProfiles();

        return view($this->viewPath(), [
            'setting' => $setting ?: new Setting(),
            'extension' => $this,
            'profiles' => $profiles,
        ]);
    }
}
