<?php

namespace App\Providers;

use App\Models\Country;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        View::composer(['layouts.frontend', 'frontend.*'], function ($view) {
            try {
                if (Schema::hasTable('countries')) {
                    $navCountries = Country::active()->orderBy('order')->orderBy('name')->get();
                    $view->with('navCountries', $navCountries);
                }

                if (Schema::hasTable('pages')) {
                    $footerPages = \App\Models\Page::published()->orderBy('order')->orderBy('id')->get();
                    $view->with('footerPages', $footerPages);
                }

                if (Schema::hasTable('settings')) {
                    $footerSocialLinks = \App\Models\Setting::getActiveSocialLinks();
                    $view->with('footerSocialLinks', $footerSocialLinks);
                }
            } catch (\Throwable $e) {
                // Ignore in case database connection not ready
            }
        });
    }
}

