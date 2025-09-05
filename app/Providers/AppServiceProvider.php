<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Support\MenuBuilder;

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
    View::composer(
        ['layouts.app-adminlte', 'partials.sidebar', 'partials.navbar'],
        function ($view) {
            $view->with('menuTree', MenuBuilder::build());
        }
    );
}
}
