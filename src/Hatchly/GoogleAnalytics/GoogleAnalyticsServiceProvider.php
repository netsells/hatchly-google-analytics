<?php

namespace Hatchly\GoogleAnalytics;

use Illuminate\Support\ServiceProvider;
use Hatchly\Settings\SettingModule;

class GoogleAnalyticsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'hatchly-analytics');
    }

    public function register()
    {
        $this->registerPageSettings();
    }

    public function registerPageSettings()
    {
        $this->app['module-manager']->promiseClosureForModule('Hatchly\Settings\SettingModule', function ($app) {
            SettingModule::registerSettingPageExtension($app->make(
            	'Hatchly\GoogleAnalytics\Extensions\AnalyticsSettingPage'));
            SettingModule::registerSettingExtension($app->make(
            	'Hatchly\GoogleAnalytics\Extensions\OauthAuthorisationCodeSetting'));
        });
    }
}