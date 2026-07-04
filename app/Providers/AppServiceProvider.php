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

        View::composer('includes.header', function ($view) {
            $notifications = Schema::hasTable('system_notifications')
                ? \App\Models\SystemNotification::where('is_read', false)->latest()->take(10)->get()
                : collect();
            $view->with([
                'adminNotifications' => $notifications,
                'adminNotificationsCount' => $notifications->count(),
            ]);
        });

        // Dynamically cap file upload validation max rules to 500 KB globally
        \Illuminate\Support\Facades\Validator::resolver(function($translator, $data, $rules, $messages, $customAttributes) {
            foreach ($rules as $attribute => &$attributeRules) {
                $hasFile = false;
                $value = data_get($data, $attribute);
                if ($value instanceof \Illuminate\Http\UploadedFile) {
                    $hasFile = true;
                } elseif (is_array($value)) {
                    foreach (\Illuminate\Support\Arr::flatten($value) as $item) {
                        if ($item instanceof \Illuminate\Http\UploadedFile) {
                            $hasFile = true;
                            break;
                        }
                    }
                }

                if ($hasFile) {
                    if (is_array($attributeRules)) {
                        foreach ($attributeRules as &$rule) {
                            if (is_string($rule) && str_starts_with($rule, 'max:')) {
                                $maxVal = (int) substr($rule, 4);
                                if ($maxVal > 500) {
                                    $rule = 'max:500';
                                }
                            }
                        }
                    } elseif (is_string($attributeRules)) {
                        $parts = explode('|', $attributeRules);
                        foreach ($parts as &$part) {
                            if (str_starts_with($part, 'max:')) {
                                $maxVal = (int) substr($part, 4);
                                if ($maxVal > 500) {
                                    $part = 'max:500';
                                }
                            }
                        }
                        $attributeRules = implode('|', $parts);
                    }
                }
            }

            return new \Illuminate\Validation\Validator($translator, $data, $rules, $messages, $customAttributes);
        });
    }
}
