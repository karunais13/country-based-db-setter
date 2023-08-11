<?php

namespace Karu\DBConnectionSetter\Queue;

use Illuminate\Queue\DatabaseQueue;

class DBQueue extends DatabaseQueue
{
    /**
     * Get the size of the queue.
     *
     * @param  string|null  $queue
     * @return int
     */
    public function size($queue = null)
    {
        $this->resetConnection();

        return parent::size($queue);
    }

    public function bulk($jobs, $data = '', $queue = null)
    {
        $this->resetConnection();

        return parent::bulk($jobs, $data, $queue);
    }

    /**
     * Push a raw payload to the database with a given delay.
     *
     * @param  string|null  $queue
     * @param  string  $payload
     * @param  \DateTimeInterface|\DateInterval|int  $delay
     * @param  int  $attempts
     * @return mixed
     */
    protected function pushToDatabase($queue, $payload, $delay = 0, $attempts = 0)
    {
        $this->resetConnection();

        return parent::pushToDatabase($queue, $payload, $delay, $attempts);
    }

    /**
     * Get the next available job for the queue.
     *
     * @param  string|null  $queue
     * @return \Illuminate\Queue\Jobs\DatabaseJobRecord|null
     */
    protected function getNextAvailableJob($queue)
    {
        $this->resetConnection();

        return parent::getNextAvailableJob($queue);
    }

    /**
     * Get the lock required for popping the next job.
     *
     * @return string|bool
     */
    protected function getLockForPopping()
    {
        $this->resetConnection();

        return parent::getLockForPopping();
    }

    /**
     * Mark the given job ID as reserved.
     *
     * @param  \Illuminate\Queue\Jobs\DatabaseJobRecord  $job
     * @return \Illuminate\Queue\Jobs\DatabaseJobRecord
     */
    protected function markJobAsReserved($job)
    {
        $this->resetConnection();

        return parent::markJobAsReserved($job);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteReserved($queue, $id)
    {
        $this->resetConnection();

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


    private function resetConnection()
    {
        if( (config('queue.default') === 'database' ) && !($this->database instanceof MySqlConnection) ){
            $this->database = \DB::connection($this->database->getConfig('name'));
        }

    }
}
