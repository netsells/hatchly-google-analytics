<?php

namespace Hatchly\GoogleAnalytics\Widgets;

use Hatchly\Core\Dashboard\Widget as BaseWidget;

abstract class Widget extends BaseWidget
{
    public function getAdminStyles()
    {
        return [];
    }

    public function getAdminScripts()
    {
        return [];
    }
}