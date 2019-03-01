<?php

namespace App\Core\Services\Impl;


use App\Core\Models\CsvValidationData;
use App\Core\Services\CsvValidatorService;
use DateTime;
use SplFileObject;

class CsvValidatorServiceImpl implements CsvValidatorService
{
    const TRANSACTION_COLUMNS = array('id', 'description', 'amount', 'date', 'owner');
    const DATE_FORMAT = 'Y-m-d';

    /**
     * @param $file
     * @return CsvValidationData
     */
    public function getValidationData($file)
    {
        $csvLines = $this->getArrayLinesFromCsv($file);
        $isValidCsvHeader = $this->isValidCsvHeader($csvLines[0]);
        $areCsvValuesValid = $this->areCsvValuesValid(array_slice($csvLines, 1));
        return new CsvValidationData(($isValidCsvHeader && $areCsvValuesValid), $isValidCsvHeader, $areCsvValuesValid);
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

    private function isValidCsvHeader($lineArray)
    {
        if (count($lineArray) != count(self::TRANSACTION_COLUMNS)) {
            return false;
        }

        return ('id' == strtolower($lineArray[0])
            && 'description' == strtolower($lineArray[1])
            && 'amount' == strtolower($lineArray[2])
            && 'date' == strtolower($lineArray[3])
            && 'owner' == strtolower($lineArray[4]));
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
        $id = trim($lineArray[0]);
        $description = trim($lineArray[1]);
        $amount = trim($lineArray[2]);
        $date = trim($lineArray[3]);
        $owner = trim($lineArray[4]);
        $isIdValid = $this->isIdValid($id);
        $isDescriptionValid = $this->isDescriptionValid($description);
        $isAmountValid = $this->isAmountValid($amount);
        $isDateValid = $this->isDateValid($date);
        $isOwnerValid = $this->isOwnerValid($owner);
        return count($lineArray) == count(self::TRANSACTION_COLUMNS) && $isIdValid &&
            $isDescriptionValid && $isAmountValid && $isDateValid &&
            $isOwnerValid;
    }

    private function isIdValid($id)
    {
        $id = intval($id);
        return is_int($id) && $id > 0;
    }

    private function isDescriptionValid($description)
    {
        return is_string($description) && !empty(trim($description));
    }

    private function isAmountValid($amount)
    {
        return is_numeric($amount);
    }

    private function isDateValid($date)
    {
        $dateFormat = DateTime::createFromFormat(self::DATE_FORMAT, $date);
        if ($dateFormat == null) {
            return false;
        }

        $dateFormat->format(self::DATE_FORMAT);
        $errors = DateTime::getLastErrors();
        return $errors['warning_count'] == 0 && $errors['error_count'] == 0;
    }

    private function isOwnerValid($owner)
    {
        return is_string($owner) && !empty(trim($owner));
    }
}
