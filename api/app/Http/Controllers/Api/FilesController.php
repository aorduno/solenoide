<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileUploadResponse;
use App\Services\FileManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FilesController extends Controller
{
    private $fileManagerService;

    public function __construct(FileManagerService $fileManagerService)
    {
        $this->fileManagerService = $fileManagerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        phpinfo();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // @TODO:aorduno - unhappy path?
        Validator::make($request->all(), [
            'file' => 'required|file',
        ])->validate();

        return new FileUploadResponse($this->fileManagerService->upload($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd('show');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
