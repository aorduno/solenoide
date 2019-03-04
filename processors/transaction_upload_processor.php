<?php

require_once(__DIR__ . '/tasks/process_transaction_uploads_task.php');
require_once(__DIR__ . '/workers/transaction_upload_worker.php');

function printWithNewLine($message)
{
    print($message . PHP_EOL);
}

define('PROCESSORS_ROOT', __DIR__);
define('TRANSACTIONS_UPLOAD_DIR', PROCESSORS_ROOT . '/shared-data/transactions');

define('TRAPP_DB_TYPE', 'mysql');
define('TRAPP_DB_HOST', 'trapp-mysql-container');
define('TRAPP_DB_USER', 'root');
define('TRAPP_DB_NAME', 'test_trapp');
define('TRAPP_DB_PASS', 'root-secret');

if (!file_exists(TRANSACTIONS_UPLOAD_DIR)) {
    mkdir(TRANSACTIONS_UPLOAD_DIR, 0777, true);
}

$files = new FilesystemIterator(TRANSACTIONS_UPLOAD_DIR, FilesystemIterator::SKIP_DOTS);
$pool = new Pool(3, 'TransactionUploadWorker');
$filesCount = iterator_count($files);
$toProcess = min($filesCount, 6);

printWithNewLine('************************************ TransactionUpload processor START ************************************');
printWithNewLine('there are ' . $filesCount . ' files in here -- will only process ' . $toProcess);
printWithNewLine('processing ' . $toProcess . ' -- at most 3 by 3');
$counter = 0;
foreach ($files as $file) {
    if ($counter >= $toProcess) {
        break;
    }

    $pool->submit(new ProcessTransactionUploadTasks($file->getPath() . '/' . $file->getFilename()));
    $counter++;
}

while ($pool->collect()) ;

$pool->shutdown();
sleep(10);
printWithNewLine('************************************ TransactionUpload processor END ************************************');