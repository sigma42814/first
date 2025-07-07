<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // app/Providers/AppServiceProvider.php
public function register()
{
    $this->app->bind(InventoryService::class, function ($app) {
        return new InventoryService();
    });
}
    

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
