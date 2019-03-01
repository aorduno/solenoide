<?php

namespace App\Core\Models;


class CsvValidationData
{
    private $valid;
    private $headerValid;
    private $rowsValid; // does not include header

    public function __construct($valid, $headerValid, $rowsValid)
    {
        $this->valid = $valid;
        $this->headerValid = $headerValid;
        $this->rowsValid = $rowsValid;
    }

    /**
     * @return mixed
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @param mixed $valid
     */
    public function setValid($valid): void
    {
        $this->valid = $valid;
    }

    /**
     * @return mixed
     */
    public function getHeaderValid()
    {
        return $this->headerValid;
    }

    /**
     * @param mixed $headerValid
     */
    public function setHeaderValid($headerValid): void
    {
        $this->headerValid = $headerValid;
    }

    /**
     * @return mixed
     */
    public function getRowsValid()
    {
        return $this->rowsValid;
    }

    /**
     * @param mixed $rowsValid
     */
    public function setRowsValid($rowsValid): void
    {
        $this->rowsValid = $rowsValid;
    }
}
