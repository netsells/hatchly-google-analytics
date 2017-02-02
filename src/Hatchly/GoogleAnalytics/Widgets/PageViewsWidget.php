<?php

namespace Hatchly\GoogleAnalytics\Widgets;

class PageViewsWidget extends Widget
{
    public function view()
    {
        // This will catch and store exceptions if they occur
        $this->analyticsService->fetch();

        // If an exception occurred, store the message
        if ($this->analyticsService->exception) {
            $error = $this->analyticsService->exception->getMessage();
            // Check if the error is in JSON format
            if (($errorJson = json_decode($error)) !== null) {
                $error = isset($errorJson->error->message) ? $errorJson->error->message : '';
            }
        }

        return view('hatchly-analytics::widgets.page-views.view', [
            'analytics' => $this->analyticsService,
            'error' => isset($error) ? $error : '',
        ]);
    }
}