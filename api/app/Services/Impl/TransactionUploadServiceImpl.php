<?php

namespace App\Services\Impl;

use App\Models\TransactionUpload;
use App\Services\TransactionUploadService;

class TransactionUploadServiceImpl implements TransactionUploadService
{

    public function findAll()
    {
        return TransactionUpload::all();
    }
}
