<?php

class GoogleAnalyticsModuleTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->app['module-manager']->registerModule($this->app->make('Hatchly\GoogleAnalytics\GoogleAnalyticsModule'));
        $this->app['module-manager']->finishedRegisteringModules();
    }

    public function testTests()
    {
        $this->assertTrue(true);
    }
}
