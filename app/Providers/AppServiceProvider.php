<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\WorkingCompany;
use App\Models\Review;

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
        // Force HTTPS
        if (
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
        ) {
            URL::forceScheme('https');
        }

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Company View Composer
        View::composer('common.company', function ($view) {

            $companies = Cache::remember('common_companies', 3600, function () {

                return WorkingCompany::where('status', 1)
                    ->select('id', 'name', 'image')
                    ->get();

            });

            $view->with('companies', $companies);
        });

        View::composer('common.review', function ($view) {

            $reviews = Cache::remember('common_reviews', 3600, function () {

                return Review::where('status', 1)
                    ->select('id', 'name', 'image')
                    ->get();

            });

            $view->with('reviews', $reviews);
        });
    }
}
