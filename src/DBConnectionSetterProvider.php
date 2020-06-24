<?php

namespace Karu\DBConnectionSetter;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Karu\DBConnectionSetter\Console\ClearCache;
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
            'command.db.seed-all',
            'command.db.connection-clear'
        );

        $this->app->singleton(
            'command.db.connection-clear',
            function ($app) {
                return new ClearCache();
            }
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->isLumen()) {
            require_once 'laravel_helper.php';
        }

        $this->publishes([
            __DIR__ . '/config/country.php' => config_path('country.php'),
        ]);


        config(['database.connections.default-mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null
        ]]);

        config(['database.connections.default-mongo' => [
            'driver' => env('DB_CONNECTION_MONGODB'),
            'host' => env('DB_HOST_MONGODB', '127.0.0.1'),
            'port' => env('DB_PORT_MONGODB', '27017'),
            'database' => env('DB_DATABASE_MONGODB'),
            'username' => env('DB_USERNAME_MONGODB'),
            'password' => env('DB_PASSWORD_MONGODB'),
            'driver_options' => [
                'database' => env('DB_AUTHENTICATION_DATABASE_MONGODB', 'admin'), // required with Mongo 3+
            ],
        ]]);
    }



    /**
     * Check if app uses Lumen.
     *
     * @return bool
     */
    protected function isLumen()
    {
        return Str::contains($this->app->version(), 'Lumen');
    }
}
