<?php

namespace Hatchly\GoogleAnalytics;

use Hatchly\Core\Modules\Module;
use Hatchly\Core\Modules\Permissions\Permission;
use Hatchly\Core\Modules\Permissions\PermissionModule;
use Hatchly\Core\Modules\Permissions\PermissionGroup;

class GoogleAnalyticsModule extends Module
{
    public function getModuleName()
    {
        return "Google Analytics";
    }

    public function getPackageName()
    {
        return 'hatchly/google-analytics';
    }

    public function getProviders()
    {
        return [
            'Hatchly\GoogleAnalytics\GoogleAnalyticsServiceProvider',
        ];
    }

    public function getPermissions()
    {
        return new PermissionModule('Google Analytics', [
            new PermissionGroup('Settings', [
                new Permission('Setup Auth', 'list'),
            ]),
        ]);
    }
}