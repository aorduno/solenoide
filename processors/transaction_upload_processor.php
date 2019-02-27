<?php

require_once(__DIR__ . '/workers/transaction_upload_manager.php');

define('PROCESSORS_ROOT', __DIR__);

echo "Starting uploadManager!" . PHP_EOL;
$worker = new TransactionUploadManager();
$worker->start();

$worker->join();
echo "uploadManager done!" . PHP_EOL;