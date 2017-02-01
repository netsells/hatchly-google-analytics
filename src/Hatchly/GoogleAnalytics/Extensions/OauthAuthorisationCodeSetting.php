<?php

namespace Hatchly\GoogleAnalytics\Extensions;

use Hatchly\Settings\ExtensionableInterfaces\BaseSetting;
use Hatchly\Settings\ExtensionableInterfaces\SettingInterface;
use Hatchly\Settings\Setting;
use Hatchly\GoogleAnalytics\Services\GoogleAnalyticsService;

class OauthAuthorisationCodeSetting extends BaseSetting implements SettingInterface
{
    protected $analyticsService;

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
        return 'analytics.oauth-authorisation-code';
    }

    public function viewPath()
    {
        return 'hatchly-analytics::extensions.settings.oauth-authorisation-code.view';
    }

    public function defaultValue()
    {
        return '';
    }

    public function view(Setting $setting = null)
    {
        return view($this->viewPath(), [
            'setting' => $setting ?: new Setting(),
            'extension' => $this,
            'authUrl' => $this->analyticsService->getAuthUrl(),
        ]);
    }
}
