<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppHackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Http\Controllers\HomeController::routes();

        config(['database.default' => 'sqlite']);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
