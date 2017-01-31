<?php

namespace Hatchly\GoogleAnalytics\Extensions;

use Hatchly\Settings\ExtensionableInterfaces\SettingPageInterface;

class AnalyticsSettingPage implements SettingPageInterface
{
    public function pageKey()
    {
        return 'analytics';
    }

    public function title()
    {
        return 'Analytics';
    }
}
