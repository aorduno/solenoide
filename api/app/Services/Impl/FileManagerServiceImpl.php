<?php

namespace App\Services\Impl;


use App\Models\FileUploadResponse;
use App\Models\TransactionUpload;
use App\Services\FileManagerService;
use SplFileObject;

class FileManagerServiceImpl implements FileManagerService
{
    const TRANSACTION_COLUMNS = array('id', 'description', 'amount', 'date', 'owner');
    private $isHeaderValid = false;

    public function upload($request)
    {
        $fileUploadResponse = $this->uploadFile($request);
        $filePath = $fileUploadResponse->getPath();
        if (!empty($filePath)) {
            $fileUpload = new TransactionUpload();
            $fileUpload->filepath = $filePath;
            $fileUpload->status = 'pending';
            $fileUpload->completed = 0;
            $fileUpload->failed = 0;
            $fileUpload->save();
        }
        return $fileUploadResponse;
    }

    private function getArrayLinesFromCsv($file)
    {
        $file = new SplFileObject($file);
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
        $lines = array();
        foreach ($file as $row) {
            $lines[] = $row;
        }

        return $lines;
    }

    private function areCsvLinesValid($fileLines)
    {
        return $this->isValidCsvHeader($fileLines[0]) && $this->areCsvValuesValid(array_slice($fileLines, 1));
    }

    private function isValidCsvHeader($lineArray)
    {
        if (count($lineArray) != count(self::TRANSACTION_COLUMNS)) {
            $this->isHeaderValid = false;
            return false;
        }

        $valid = 'id' == strtolower($lineArray[0])
            && 'description' == strtolower($lineArray[1])
            && 'amount' == strtolower($lineArray[2])
            && 'date' == strtolower($lineArray[3])
            && 'owner' == strtolower($lineArray[4]);
        $this->isHeaderValid = $valid;
        return $valid;
    }

    private function areCsvValuesValid($linesWithNoHeader)
    {
        foreach ($linesWithNoHeader as $line) {
            if (!$this->isValidCsvValue($line)) {
                return false;
            }
        }

        return true;
    }

    private function isValidCsvValue($lineArray)
    {
        // @TODO:aorduno -- do we care of the csv values here?... like make sure description, amount and date are valid
        $id = trim($lineArray[0]);
        $description = trim($lineArray[1]);
        $amount = trim($lineArray[2]);
        $date = trim($lineArray[3]);
        $owner = trim($lineArray[4]);
        return count($lineArray) == count(self::TRANSACTION_COLUMNS) && (
                !empty($description) && !empty($amount) && !empty($date) && !empty($id) && !empty($owner));
    }

    private function getFileName($request)
    {
        return 'transactions_userid_' . $request->userId . '_' . date('Y_m_d_H_i_s') . '.csv';
    }

    /**
     * @param $request
     * @return FileUploadResponse
     */
    public function uploadFile($request)
    {
        if (!$request->hasFile('file') || !$request->type == 'text/csv') {
            // throw invalidError
            return;
        }

        $path = '';
        $csvLines = $this->getArrayLinesFromCsv($request->file);
        $areCsvLinesValid = $this->areCsvLinesValid($csvLines);
        if ($areCsvLinesValid) {
            $path = $request->file->storeAs('transactions', $this->getFileName($request));
        }
        $fileUploadResponse = new FileUploadResponse();
        $fileUploadResponse->setSucceed(!empty($path));
        $fileUploadResponse->setExtension($request->type);
        $fileUploadResponse->setPath($path);
        $fileUploadResponse->setLines($csvLines);
        $fileUploadResponse->setValidHeader($this->isHeaderValid);
        $fileUploadResponse->setValidLines($areCsvLinesValid);
        $fileUploadResponse->setLinesCount(count($csvLines));
        return $fileUploadResponse;
    }
}
