<?php

namespace App\Core\Api\Version1;


use App\Core\Api\TransactionUploadApi;
use App\Core\Services\CsvValidatorService;
use App\Core\Services\TransactionUploadService;
use App\Exceptions\ApiException;

class TransactionUploadApiVersion1 implements TransactionUploadApi
{
    const SUPPORTED_EXTENSIONS = array('csv');
    private $transactionUploadService;
    private $csvValidatorService;

    public function __construct(TransactionUploadService $transactionUploadService, CsvValidatorService $csvValidatorService)
    {
        $this->transactionUploadService = $transactionUploadService;
        $this->csvValidatorService = $csvValidatorService;
    }

    public function fetchAll()
    {
        return $this->transactionUploadService->fetchAll();
    }

    /**
     * @param $request
     * @return mixed
     * @throws ApiException
     */
    public function store($request)
    {
        $clientOriginalExtension = $request->file->getClientOriginalExtension();
        if (empty($request->file) || !in_array($clientOriginalExtension, self::SUPPORTED_EXTENSIONS)) {
            throw new ApiException('Invalid file received', 400);
        }

        $validationData = $this->csvValidatorService->getValidationData($request->file);
        if (!$validationData->getValid()) {
            if (!$validationData->getHeaderValid()) {
                throw new ApiException('Something is wrong with your CSV headers kid!', 400);
            }

            if (!$validationData->getRowsValid()) {
                throw new ApiException('Something is wrong with your CSV rows kid!', 400);
            }
        }

        return $this->transactionUploadService->store($request);
    }
}
