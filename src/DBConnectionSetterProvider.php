<?php

namespace Karu\DBConnectionSetter;

use Illuminate\Support\ServiceProvider;

class DBConnectionSetterProvider extends ServiceProvider
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
        $this->publishes([
            __DIR__ . '/config/country.php' => config_path('country.php'),
        ]);
    }
}
