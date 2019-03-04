<?php

class ProcessTransactionUploadTasks extends Threaded
{
    const TRANSACTION_COLUMNS = array('transaction_id', 'description', 'amount', 'date', 'owner');
    const NOTIFICATION_MESSAGE = "TransactionId: %u has been processed, here are some brief details: 
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
        $minusHeader = array_slice($lines, 1);
        $toProcess = count($minusHeader);
        $lineBatches = array_chunk($minusHeader, 10000); // batches of 10000
        $this->logMessage('batches ' . count($lineBatches));

        $failed = 0;
        $completed = 0;
        $connectionDsn = TRAPP_DB_TYPE . ':host=' . TRAPP_DB_HOST . ';dbname=' . TRAPP_DB_NAME;
        $myPdo = new PDO($connectionDsn, TRAPP_DB_USER, TRAPP_DB_PASS);
        $myPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        foreach ($lineBatches as $batch) {
            $processData = $this->processCsvBatch($batch, $myPdo);
            $failed += $processData['failed'];
            $completed += $processData['completed'];
            $this->logMessage('rows processed info at the moment: completed: ' . $completed . ' || failed: ' . $failed);
        }

        $filename = $file->getFilename();
        $statement = $myPdo->prepare('SELECT * FROM transaction_upload WHERE filename = ?');
        $statement->execute(array($filename));
        $transactionUploadData = $statement->fetch();
        $processData = array('total' => $toProcess, 'completed' => $completed, 'failed' => $failed, 'transactionId' => $transactionUploadData['id']);
        try {
            $myPdo->beginTransaction();
            $this->sendNotification($myPdo, $processData);
            $this->updateTransactionUpload($myPdo, $processData);
            $myPdo->commit();
        } catch (PDOException $e) {
            $this->logMessage($e->getMessage());
            $myPdo->rollBack();
        }

        $file = null;
        unlink($this->filepath);

        $this->logMessage('Done processing CSV Rows! processed: ' . $processData['total'] . ' || completed: ' . $processData['completed'] . ' || failed: ' . $processData['failed']);
        $doneAt = time();
        $this->logMessage('Done at ' . $doneAt . '. Took up to ' . ($doneAt - $startedAt) . ' seconds');
    }

    private function sendNotification($myPdo, $processData)
    {
        $prepared = $myPdo->prepare('INSERT INTO notification(message) VALUES (?)');
        $prepared->execute(array(sprintf(self::NOTIFICATION_MESSAGE, $processData['transactionId'], $processData['total'], $processData['completed'], $processData['failed'])));
    }

    public function getWorkerId()
    {
        global $workerId;
        return $workerId;
    }

    private function logMessage($message)
    {
        echo $this->getWorkerId() . ': ' . $message . PHP_EOL;
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

    private function updateTransactionUpload($myPdo, $processData)
    {
        $prepared = $myPdo->prepare('UPDATE transaction_upload SET status = ?, completed = ?, failed = ? WHERE id = ?');
        $prepared->execute(array('processed', $processData['completed'], $processData['failed'], $processData['transactionId']));
    }
}