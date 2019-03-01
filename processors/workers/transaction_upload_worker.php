<?php

class TransactionUploadWorker extends Worker
{
    protected static $workerdIdNext = 0;
    protected $workerId;

    public function __construct()
    {
        $this->workerId = ++static::$workerdIdNext;
    }

    public function run()
    {
        global $workerId;
        $workerId = 'Worker::' . $this->workerId;
    }
}