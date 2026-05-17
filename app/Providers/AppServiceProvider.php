<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;

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
        // Share cart count with all views
        View::composer('*', function ($view) {
            $cart = Session::get('cart', []);
            $cartCount = collect($cart)->sum('quantity');
            $view->with('cartCount', $cartCount);
        });

        // Define Gates
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('customer', function ($user) {
            return $user->isCustomer();
        });
    }
}
