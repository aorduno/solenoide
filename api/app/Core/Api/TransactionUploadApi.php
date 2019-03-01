<?php

namespace App\Core\Api;


interface TransactionUploadApi
{
    public function fetchAll();

    public function store($request);
}
