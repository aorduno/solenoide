<?php

namespace App\Core\Services;

interface TransactionService
{
    public function findAll();

    public function create($request);

    public function find($id);

    public function update($id, $request);

    public function delete($id);
}
