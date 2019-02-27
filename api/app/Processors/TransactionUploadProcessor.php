<?php

namespace App\Processors;

use Thread;

class TransactionUploadProcessor extends Thread
{
    private $filepath;

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }

    public function run()
    {
        echo "hello sir! i'm gonna process " . $this->filepath;
        usleep(500);
    }
}
