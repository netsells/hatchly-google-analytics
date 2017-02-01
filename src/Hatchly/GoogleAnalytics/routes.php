<?php

Route::group(['prefix' => config('hatchly.core.admin-url'), 'middleware' => ['auth', 'auth.admin']], function () {
    Route::group(['prefix' => 'settings'], function () {
        Route::group(['prefix' => 'analytics'], function () {
            Route::get('oauth', [
                'as' => 'hatchly.settings.analytics.oauth',
                'uses' => '\Hatchly\GoogleAnalytics\GoogleAnalyticsController@oauthCallback',
            ]);
            Route::get('deauth', [
                'as' => 'hatchly.settings.analytics.deauth',
                'uses' => '\Hatchly\GoogleAnalytics\GoogleAnalyticsController@deauth',
            ]);
        });
    });
});
