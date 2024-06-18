<?php

namespace EskayAmadeus\FrogNotificationChannel;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class FrogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/frog-notification.php' => config_path('frog-notification.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        Notification::extend('frog', function ($app) {
            return new FrogChannel;
        });

        $this->mergeConfigFrom(__DIR__.'/../config/frog-notification.php', 'frog-notification');
    }
}
