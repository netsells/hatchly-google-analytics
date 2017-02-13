<?php

namespace Hatchly\GoogleAnalytics\Widgets;

class StatsWidget extends Widget
{
    public function view()
    {
        return view('hatchly-analytics::widgets.stats.view', [
            'metrics' => $this->analyticsService->metrics,
            'stats' => $this->analyticsService->getStats(),
            'error' => $this->analyticsService->getError(),
        ]);
    }
}