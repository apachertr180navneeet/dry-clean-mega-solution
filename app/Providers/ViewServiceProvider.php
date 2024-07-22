<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\View\Composers\ViewComposer;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['*'], ViewComposer::class);
    }
}
