<?php

require_once(__DIR__ . '/transaction_upload_worker.php');

class TransactionUploadManager extends Thread
{
    const TRANSACTIONS_UPLOAD_DIR = PROCESSORS_ROOT . '/../api/storage/app/transactions';

    public function run()
    {
        $files = new FilesystemIterator(self::TRANSACTIONS_UPLOAD_DIR, FilesystemIterator::SKIP_DOTS);
        $workers = array();
        $toProcess = min(iterator_count($files), 5);
        foreach ($files as $file) {
            if (count($workers) >= $toProcess) {
                break;
            }
            $this->printWithNewLine('filepath ' . $file);
            $workers[] = new TransactionUploadWorker($file->getPath() . '/' . $file->getFilename());
        }

        $this->printWithNewLine('there are ' . iterator_count($files) . ' in here -- processing ' . $toProcess);
        $this->printWithNewLine('processing ' . $toProcess);
        $this->printWithNewLine('starting ' . count($workers) . ' workers');
        foreach ($workers as $worker) {
            $worker->start();
        }
    }

    private function printWithNewLine($message)
    {
        print($message . PHP_EOL);
    }
}