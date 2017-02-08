<?php

namespace Hatchly\GoogleAnalytics;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Hatchly\Settings\SettingModule;
use Hatchly\GoogleAnalytics\Extensions;

class GoogleAnalyticsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'hatchly-analytics');
        $this->registerRoutes();
    }

    public function register()
    {
        $this->registerGoogleAnalyticsSettings();
    }

    public function registerGoogleAnalyticsSettings()
    {
        $this->app['module-manager']->promiseClosureForModule('Hatchly\Settings\SettingModule', function ($app) {

            SettingModule::registerSettingPageExtension($app->make(AnalyticsSettingPage::class));
            SettingModule::registerSettingExtension($app->make(OauthAuthenticatedSetting::class));

            if (setting('analytics.oauth-authenticated')) {
                
                SettingModule::registerSettingExtension($app->make(OauthAuthorisationCodeSetting::class));
                SettingModule::registerSettingExtension($app->make(OauthTokenSetting::class));
                SettingModule::registerSettingExtension($app->make(AnalyticsProfileSetting::class));
                SettingModule::registerSettingExtension($app->make(CacheDurationSetting::class));
            }
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
