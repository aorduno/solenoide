<?php

namespace App\Core\Services\Impl;

use App\Core\Models\TransactionUpload;
use App\Core\Services\FileManagerService;
use App\Core\Services\TransactionUploadService;

class TransactionUploadServiceImpl implements TransactionUploadService
{
    private $fileManagerService;

    public function __construct(FileManagerService $fileManagerService)
    {
        $this->fileManagerService = $fileManagerService;
    }

    public function fetchAll()
    {
        return TransactionUpload::all();
    }

    /**
     * @param $request
     * @return TransactionUpload
     */
    public function store($request)
    {
        $fileStoreObj = $this->fileManagerService->storeFile($request);
        $fileUpload = new TransactionUpload();
        if ($fileStoreObj == null) {
            return $fileUpload;
        }

        $fileUpload->filename = $fileStoreObj->getFilename();
        $fileUpload->status = 'pending';
        $fileUpload->completed = 0;
        $fileUpload->failed = 0;
        $fileUpload->save();
        return $fileUpload;
    }
}
