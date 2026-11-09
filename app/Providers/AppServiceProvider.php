<?php

namespace App\Providers;

use App\Models\Page;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\CartService::class);
    }

    public function boot(): void
    {
        // Super admin bypasses all permission checks.
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // Share footer pages with all views (safe if DB not ready).
        View::composer('*', function ($view) {
            try {
                $view->with('footerPages', Page::footer()->get());
            } catch (\Throwable $e) {
                $view->with('footerPages', collect());
            }
        });
    }
}
