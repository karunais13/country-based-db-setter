<?php

namespace Karu\DBConnectionSetter\Queue;

use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\DatabaseJob;

class DBJob extends DatabaseJob
{

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @param  \Illuminate\Queue\DatabaseQueue  $database
     * @param  \stdClass  $job
     * @param  string  $connectionName
     * @param  string  $queue
     * @return void
     */
    public function __construct(Container $container, DBQueue $database, $job, $connectionName, $queue)
    {
        $this->job = $job;
        $this->queue = $queue;
        $this->database = $database;
        $this->container = $container;
        $this->connectionName = $connectionName;
    }
}
