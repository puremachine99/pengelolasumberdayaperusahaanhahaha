<?php

namespace App\Providers;

use App\Models\MenuCategory;
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
    public function boot()
    {
        View::composer('layout.sidebar', function ($view) {
            $view->with('categories', MenuCategory::orderBy('name')->get());
        });
    }
}
