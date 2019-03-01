<?php
namespace App\Core\Services;

interface TransactionUploadService {
    public function fetchAll();

    public function store($request);
}
