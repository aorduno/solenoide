<?php

namespace App\Http\Controllers;

use App\Core\Api\TransactionUploadApi;
use App\Http\Resources\TransactionUpload;
use App\Http\Resources\TransactionUploadCollection;
use Illuminate\Http\Request;

class TransactionUploadController extends Controller
{
    protected $transactionUploadApi;

    public function __construct(TransactionUploadApi $transactionUploadApi)
    {
        $this->transactionUploadApi = $transactionUploadApi;
    }

    /**
     * Display a listing of the resource.
     *
     * @return TransactionUploadCollection
     */
    public function index()
    {
        return new TransactionUploadCollection($this->transactionUploadApi->fetchAll());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return TransactionUpload
     */
    public function store(Request $request)
    {
        return new TransactionUpload($this->transactionUploadApi->store($request));
    }
}
