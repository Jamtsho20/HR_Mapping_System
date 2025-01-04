<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use App\Observers\UserObserver;
use App\Models\User;
use Illuminate\Support\Facades\URL;

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
	// User::observe(UserObserver::class);
//	if (env('APP_ENV') === 'production') {
  //          URL::forceRootUrl("https://hrms.tashicell.com");
    //    }
        if ($_SERVER['HTTP_HOST'] === 'hrms-mainfile.tashicell.com') {
            header('Location: https://hrms.tashicell.com'.$_SERVER['REQUEST_URI'], true, 301);
            exit;
        }
    }
}
