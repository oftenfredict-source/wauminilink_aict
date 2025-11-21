<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Channels\SmsChannel;
use App\Services\SmsService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {



        
        // Register SMS notification channel
        Notification::extend('sms', function ($app) {
            return new SmsChannel($app->make(SmsService::class));
        });
    }
}