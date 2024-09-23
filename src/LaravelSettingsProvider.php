<?php

namespace Mikrahub\LaravelSettings;

use Illuminate\Support\ServiceProvider;
use Mikrahub\LaravelSettings\Storages\MysqlSettingsStorage;

class LaravelSettingsProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/laravel-settings.php', 'laravel-settings');

        $this->app->singleton('laravel-settings', function ($app) {
            return SettingStorageFactory::create();
        });

        $this->app->bind('laravel-settings.mysql', function ($app) {
            return new MysqlSettingsStorage();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config/laravel-settings.php' => config_path('laravel-settings.php'),
        ], 'config');
    }
}
