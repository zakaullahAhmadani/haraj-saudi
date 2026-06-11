<?php

namespace App\Providers;

use App\Models\Ad;
use App\ViewComposers\CategoryComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Only register view composers for web requests
        if (!$this->app->runningInConsole()) {
            View::composer('*', CategoryComposer::class);
            
            // Handle searchCities separately
            try {
                if (Schema::hasTable('ads')) {
                    View::share('searchCities', Ad::saudiCities());
                } else {
                    View::share('searchCities', collect([]));
                }
            } catch (\Exception $e) {
                View::share('searchCities', collect([]));
            }
        } else {
            // During console commands, share empty data
            View::share('headerCategories', collect([]));
            View::share('allCategories', collect([]));
            View::share('searchCities', collect([]));
        }
    }
}