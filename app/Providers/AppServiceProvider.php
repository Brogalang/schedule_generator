<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\M_menu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        view()->share('administrator', M_menu::where('status','=', 2)->orderby('order','ASC')->get());
        view()->share('menus', M_menu::where('status','=', 1)->orderby('order','ASC')->get());
    }
}
