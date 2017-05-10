<?php

namespace Hatchly\GoogleAnalytics;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Hatchly\Settings\SettingModule;
use Hatchly\GoogleAnalytics\Extensions\AnalyticsSettingPage;
use Hatchly\GoogleAnalytics\Extensions\OauthAuthenticatedSetting;
use Hatchly\GoogleAnalytics\Extensions\OauthAuthorisationCodeSetting;
use Hatchly\GoogleAnalytics\Extensions\OauthTokenSetting;
use Hatchly\GoogleAnalytics\Extensions\AnalyticsProfileSetting;
use Hatchly\GoogleAnalytics\Extensions\CacheDurationSetting;
use PDOException;

class GoogleAnalyticsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'hatchly-analytics');
        $this->registerRoutes();
    }

    public function register()
    {
        try {
            $this->registerGoogleAnalyticsSettings();
        } catch(PDOException $e){
            // We can assume the database has not yet been created
        }
    }

    public function registerGoogleAnalyticsSettings()
    {
        $this->app['module-manager']
            ->moduleRequiresModule('Hatchly\Settings\SettingModule')
            ->promiseClosureForModule('Hatchly\Settings\SettingModule', function ($app) {

                SettingModule::registerSettingPageExtension($app->make(AnalyticsSettingPage::class));
                SettingModule::registerSettingExtension($app->make(OauthAuthenticatedSetting::class));

                if (setting('analytics.oauth-authenticated')) {
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
