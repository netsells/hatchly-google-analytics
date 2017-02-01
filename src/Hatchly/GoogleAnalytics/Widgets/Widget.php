<?php

namespace Hatchly\GoogleAnalytics\Widgets;

use Hatchly\Core\Dashboard\Widget as BaseWidget;
use Hatchly\GoogleAnalytics\Services\GoogleAnalyticsService;

abstract class Widget extends BaseWidget
{
    protected $analyticsService;

    public function __construct(GoogleAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function getAdminStyles()
    {
        return [
        	asset('css/admin/app.min.css'),
        ];
    }

    public function getAdminScripts()
    {
        return [];
    }
}