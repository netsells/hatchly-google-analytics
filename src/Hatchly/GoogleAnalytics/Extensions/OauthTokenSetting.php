<?php

namespace Hatchly\GoogleAnalytics\Extensions;

use Hatchly\Settings\ExtensionableInterfaces\BaseSetting;
use Hatchly\Settings\ExtensionableInterfaces\SettingInterface;
use Hatchly\Settings\Setting;

class OauthTokenSetting extends BaseSetting implements SettingInterface
{
    public function pageKey()
    {
        return 'analytics';
    }

    public function extensionableKey()
    {
        return 'analytics.oauth-token';
    }

    public function viewPath()
    {
        return 'hatchly-analytics::extensions.settings.oauth-token.view';
    }

    public function defaultValue()
    {
        return '';
    }
}
