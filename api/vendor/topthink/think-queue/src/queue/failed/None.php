<?php

namespace think\queue\failed;

use think\queue\FailedJob;

class None extends FailedJob
{

    /**
     * Log a failed job into storage.
     *
     * @param string     $connection
     * @param string     $queue
     * @param string     $payload
     * @param \Exception $exception
     */
    public function log($connection, $queue, $payload, $exception)
    {

    }

    /**
     * Get a list of all of the failed jobs.
     *
     * @return array
     */
    public function all()
    {
        return [];
    }

    /**
     * Get a single failed job.
     *
     * @param mixed $id
     */
    public function find($id)
    {

    }

    /**
     * Delete a single failed job from storage.
     *
     * @param mixed $id
     * @return bool
     */
    public function forget($id)
    {
        return true;
    }

    /**
     * Flush all of the failed jobs from storage.
     *
     * @return void
     */
    public function flush()
    {

    }
}
