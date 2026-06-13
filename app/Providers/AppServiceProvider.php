<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Fluent;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        } 
        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {
            $settings = Schema::hasTable('site_settings')
                ? SiteSetting::pluck('value', 'key')->all()
                : [];

            $view->with('generalSettings', new Fluent($settings));
        });
    }
}
