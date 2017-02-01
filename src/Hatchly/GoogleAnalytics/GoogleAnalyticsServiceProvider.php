<?php

namespace Hatchly\GoogleAnalytics;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Hatchly\Settings\SettingModule;

class GoogleAnalyticsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'hatchly-analytics');
        $this->registerRoutes();
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
            SettingModule::registerSettingExtension($app->make(
                'Hatchly\GoogleAnalytics\Extensions\OauthTokenSetting'));
        });
    }

    public function registerRoutes()
    {
        Route::group([
            'middleware' => 'web',
        ], function ($router) {
            require __DIR__.'/routes.php';
        });
    }
}