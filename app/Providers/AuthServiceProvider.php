<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('manage_users', function (User $user) {
            return $user->hasPermission('manage_users');
        });

        Gate::define('manage_orders', function (User $user) {
            return $user->hasPermission('manage_orders');
        });

        Gate::define('manage_products', function (User $user) {
            return $user->hasPermission('manage_products');
        });
    }
}
