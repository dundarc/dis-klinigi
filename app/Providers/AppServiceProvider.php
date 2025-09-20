<?php

namespace App\Providers;

// Bu satırı ekleyin
use Illuminate\Support\Facades\Schema; 
use Illuminate\Support\ServiceProvider;

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
        // Bu satırı ekleyin
        Schema::defaultStringLength(191);
    }
}