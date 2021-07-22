<?php

namespace Karu\DBConnectionSetter\Queue;

use Illuminate\Queue\QueueServiceProvider;

class DBQueueServiceProvider extends QueueServiceProvider
{
    /**
     * {@inheritdoc}
     */

    protected function registerDatabaseConnector($manager)
    {
        $manager->addConnector('database', function () {
            return new DBConnector($this->app['db']);
        });
    }
}
