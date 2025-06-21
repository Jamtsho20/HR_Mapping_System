<?php

namespace App\Providers;

use App\Models\LeaveApplication;
use App\Models\TravelAuthorizationApplication;
use App\Models\User;
use App\Observers\LeaveApplicationObserver;
use App\Observers\TravelAuthorizationObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
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
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        TravelAuthorizationApplication::observe(TravelAuthorizationObserver::class);
        LeaveApplication::observe(LeaveApplicationObserver::class);
        // User::observe(UserObserver::class);
        if (env('APP_ENV') === 'production') {
            URL::forceRootUrl("https://hrms.tashicell.com");
        }
        }
    
}
