# Hatchly: Google Analytics

[![Build Status](https://travis-ci.com/netsells/hatchly-google-analytics.svg?token=4gBezk2Epx92wCk1hJyg&branch=master)](https://travis-ci.com/netsells/hatchly-google-analytics)

## Documentation
Documetation for the hatchly can be found on the [documentation website](http://docs.hatchly.io/developer-documentation).

## Contributing
The contribution guide can be found on the [documentation website](http://docs.hatchly.io/developer-documentation#welcome-contributing).

## Quick Start

### Installation
Include with composer:

```bash
composer require hatchly/google-analytics
```

Once installed, you need to load the module by placing the module into the `config/hatchly/core.php` file in the modules array.

```php
Hatchly\GoogleAnalytics\GoogleAnalyticsModule::class
```

### Usage

Go to Settings > Google Analytics in the admin area and follow the instructions to link your google account with the install of Hatchly.

Once setup, you can begin adding modules to your dashboard. The following modules are available to add any number of times in the `config/hatchly/core.php` dashboard-widgets array. 

```php
Hatchly\GoogleAnalytics\Widgets\PageViewsWidget::class,
Hatchly\GoogleAnalytics\Widgets\SessionsWidget::class,
```