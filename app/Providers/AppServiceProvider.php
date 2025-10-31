<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Staff;
use App\Observers\StaffObserver;

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
        // Register Staff observer for automatic image compression
        Staff::observe(StaffObserver::class);
    }
}
