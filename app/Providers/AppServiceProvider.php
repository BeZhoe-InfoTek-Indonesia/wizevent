<?php

namespace App\Providers;

use App\Models\Order;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
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
        Gate::policy(\App\Models\SettingComponent::class, \App\Policies\SettingComponentPolicy::class);

        Route::bind('order', function (string $value) {
            return Order::query()
                ->where('uuid', $value)
                ->orWhere('order_number', $value)
                ->firstOrFail();
        });
    }
}
