<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\WorkingCompany;
use App\Services\GoogleReviewsService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

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

        // Google Reviews View Composer
        View::composer('common.review', function ($view) {
            $googleReviews = app(GoogleReviewsService::class);
            $data = $googleReviews->getReviews();

            $view->with('reviews',      $data['reviews']      ?? []);
            $view->with('rating',       $data['rating']       ?? 5.0);
            $view->with('totalReviews', $data['totalReviews'] ?? 0);
            $view->with('businessName', $data['businessName'] ?? 'Mirror Booth Dubai');
        });
    }
}
