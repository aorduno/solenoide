<?php

class TransactionUploadWorker extends Thread
{
    const TRANSACTION_COLUMNS = array('transaction_id', 'description', 'amount', 'date', 'owner');
    const NOTIFICATION_MESSAGE = "One of your transactions has been processed. Brief details: 
    Transactions processed %u, transactions completed %u, transactions failed %u.";

    private $filepath;

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
    }

    public function run()
    {
        $startedAt = time();
        $this->logMessage('started at ' . $startedAt);
        $this->logMessage('will be processing file ' . $this->filepath);
        $file = new SplFileObject($this->filepath);
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
        $lines = array();
        foreach ($file as $row) {
            $lines[] = $row;
        }
        $linesCount = count($lines);
        $this->logMessage('file has ' . $linesCount . ' lines');
        // ignore first since that's header
        $lineBatches = array_chunk(array_slice($lines, 1), 10000); // batches of 10000
        $this->logMessage('batches ' . count($lineBatches));


        $failed = 0;
        $completed = 0;
        $myPdo = new PDO('pgsql:host=localhost;dbname=lawn_pro', 'aorduno', '');
        $myPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        foreach ($lineBatches as $batch) {
            $processData = $this->processCsvBatch($batch, $myPdo);
            $failed += $processData['failed'];
            $completed += $processData['completed'];
            $this->logMessage('rows processed info at the moment: completed: ' . $completed . ' || failed: ' . $failed);
            usleep(500000);
        }

        $this->sendNotification($myPdo, $linesCount, $completed, $failed);
        $this->logMessage('Done processing CSV Rows! processed: ' . $linesCount . ' || completed: ' . $completed . ' || failed: ' . $failed);
        $doneAt = time();
        $this->logMessage('Done at ' . $doneAt . '. Took up to ' . ($doneAt - $startedAt) . ' seconds');
    }

    private function sendNotification($myPdo, $processed, $completed, $failed)
    {
        try {
            $prepared = $myPdo->prepare('INSERT INTO notification(message) VALUES (?)');
            $prepared->execute(array(sprintf(self::NOTIFICATION_MESSAGE, $processed, $completed, $failed)));
        } catch (PDOException $e) {
            $this->logMessage($e->getMessage());
        }
    }

    private function logMessage($message)
    {
        echo 'thread::' . $this->getThreadId() . ': ' . $message . PHP_EOL;
    }

    private function processCsvBatch($batch, $myPdo)
    {
        $csvRows = array();
        $csvRowsCount = 0;
        foreach ($batch as $csvRow) {
            $csvRows[] = array(
                'transaction_id' => $csvRow[0], 'description' => $csvRow[1], 'amount' => $csvRow[2],
                'date' => $csvRow[3], 'owner' => $csvRow[4]
            ); // make sure they're in the right order
            $csvRowsCount++;
        }

        $insertValues = array();
        $placeholders = array_map(function () {
            return '?';
        }, self::TRANSACTION_COLUMNS);

        $questionMarks = array();
        foreach ($csvRows as $csvRow) {
            $questionMarks[] = '(' . join(',', $placeholders) . ')';
            $insertValues = array_merge($insertValues, array_values($csvRow));
        }

        $insertSql = "INSERT INTO transaction (" . implode(",", self::TRANSACTION_COLUMNS) . ") VALUES " .
            implode(',', $questionMarks);

//        $this->logMessage($insertSql);

        // try to connect first
        $preparedStatement = $myPdo->prepare($insertSql);
        $processData = array('completed' => 0, 'failed' => 0);
        try {
            $inserted = $preparedStatement->execute($insertValues);
            if ($inserted) {
                $processData['completed'] = $csvRowsCount;
            } else {
                $processData['failed'] = $csvRowsCount;
            }
        } catch (PDOException $e) {
            $this->logMessage($e->getMessage());
            $processData['failed'] = $csvRowsCount;
        }

        return $processData;
    }
}