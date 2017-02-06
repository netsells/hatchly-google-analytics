<?php

namespace Hatchly\GoogleAnalytics\Extensions;

use Hatchly\Settings\ExtensionableInterfaces\BaseSetting;
use Hatchly\Settings\ExtensionableInterfaces\SettingInterface;
use Hatchly\Settings\Setting;

class CacheDurationSetting extends BaseSetting implements SettingInterface
{
    public function pageKey()
    {
        return 'analytics';
    }

    public function extensionableKey()
    {
        return 'analytics.cache-duration';
    }

    public function viewPath()
    {
        return 'hatchly-analytics::extensions.settings.cache-duration.view';
    }

    public function defaultValue()
    {
        return 5;
    }
}
