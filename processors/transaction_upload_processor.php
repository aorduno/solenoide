<?php

require_once(__DIR__ . '/tasks/process_transaction_uploads_task.php');
require_once(__DIR__ . '/workers/transaction_upload_worker.php');

function printWithNewLine($message)
{
    print($message . PHP_EOL);
}

define('PROCESSORS_ROOT', __DIR__);
define('TRANSACTIONS_UPLOAD_DIR', PROCESSORS_ROOT . '/../api/storage/app/transactions');

$files = new FilesystemIterator(TRANSACTIONS_UPLOAD_DIR, FilesystemIterator::SKIP_DOTS);
$pool = new Pool(3, 'TransactionUploadWorker');
$toProcess = min(iterator_count($files), 6);

printWithNewLine('there are ' . iterator_count($files) . ' files in here -- will only process ' . $toProcess);
printWithNewLine('processing ' . $toProcess . ' -- 3 by 3');
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
printWithNewLine('We are done!');