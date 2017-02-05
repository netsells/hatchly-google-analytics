<?php

namespace Hatchly\GoogleAnalytics\Extensions;

use Hatchly\Settings\ExtensionableInterfaces\BaseSetting;
use Hatchly\Settings\ExtensionableInterfaces\SettingInterface;
use Hatchly\Settings\Setting;
use Hatchly\GoogleAnalytics\Services\GoogleAnalyticsService;

class OauthAuthenticatedSetting extends BaseSetting implements SettingInterface
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
        return 'analytics.oauth-authenticated';
    }

    public function viewPath()
    {
        return 'hatchly-analytics::extensions.settings.oauth-authenticated.view';
    }

    public function defaultValue()
    {
        return '';
    }
}
