<?php

namespace Karu\DBConnectionSetter;

use Illuminate\Support\ServiceProvider;
use Karu\DBConnectionSetter\Console\CustomMigration;
use Karu\DBConnectionSetter\Console\CustomQueueWork;
use Karu\DBConnectionSetter\Console\CustomSeed;

class DBConnectionSetterProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'command.migrate-all',
            function ($app) {
                return new CustomMigration();
            }
        );

        $this->app->singleton(
            'command.db.seed-all',
            function ($app) {
                return new CustomSeed();
            }
        );

        $this->app->extend('command.queue.work', function () {
            return new CustomQueueWork(app('queue.worker'), app('cache.store'));
        });

//        $this->app->bind(WorkCommand::class, function ($app) {
//            return new WorkCommand($app['queue.worker'], $app['cache.store']);
//        });
        $this->commands(
            'command.migrate-all',
            'command.db.seed-all'
        );
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
