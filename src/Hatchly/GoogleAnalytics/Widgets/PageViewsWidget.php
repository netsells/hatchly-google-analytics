<?php

namespace Hatchly\GoogleAnalytics\Widgets;

class PageViewsWidget extends Widget
{
    public function view()
    {
        $this->analyticsService->fetch();

        dd($this->analyticsService);

        return view('hatchly-analytics::widgets.page-views.view', ['analytics' => $this->analyticsService]);
    }
}