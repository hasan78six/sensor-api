<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

/**
 * Application Service Provider
 * 
 * This is the main service provider for the application.
 * It bootstraps the application and registers any application services.
 * 
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * 
     * This method is called during the service container binding phase of the application.
     * Use this method to register any application services that should be bound in the container.
     * 
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * 
     * This method is called after all other service providers have been registered,
     * meaning you have access to all other services that have been registered by the framework.
     * 
     * @return void
     */
    public function boot(): void
    {
        // Set default string length to 191 for MySQL utf8mb4 compatibility (767 bytes index limit)
        Schema::defaultStringLength(191);
    }
}
