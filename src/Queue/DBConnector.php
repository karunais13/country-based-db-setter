<?php

namespace Karu\DBConnectionSetter\Queue;

use Illuminate\Queue\Connectors\ConnectorInterface;
use Illuminate\Queue\Connectors\DatabaseConnector;

class DBConnector extends DatabaseConnector
{
    /**
     * Establish a queue connection.
     * @param array $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        return new DBQueue(
            $this->connections->connection($config['connection'] ?? null),
            $config['table'],
            $config['queue'],
            $config['retry_after'] ?? 60
        );
    }
}
