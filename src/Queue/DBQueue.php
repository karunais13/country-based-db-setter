<?php

namespace Karu\DBConnectionSetter\Queue;

use Illuminate\Queue\DatabaseQueue;

class DBQueue extends DatabaseQueue
{

    /**
     * {@inheritdoc}
     */
    public function deleteReserved($queue, $id)
    {
        $this->database = $this->database->getPdo() ?? \DB::connection($this->database->getConfig('name'));

        parent::deleteReserved($queue, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function pop($queue = null)
    {

        if( !method_exists($this->database, 'getPdo') ) {
            $this->database = \DB::connection('currentHost');
        }

        return parent::pop($queue);
    }
}
