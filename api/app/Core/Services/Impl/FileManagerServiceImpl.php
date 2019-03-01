<?php

namespace App\Core\Services\Impl;


use App\Core\Models\FileStore;
use App\Core\Services\FileManagerService;

class FileManagerServiceImpl implements FileManagerService
{
    /**
     * @param $request
     * @return FileStore
     */
    public function storeFile($request): FileStore
    {
        $fileName = $this->getFileName($request);
        $path = $request->file->storeAs('transactions', $fileName);
        $fileStore = new FileStore();
        $fileStore->setFilename($fileName);
        $fileStore->setFilepath($path);

        return $fileStore;
    }

    private function getFileName($request)
    {
        // @TODO:aorduno -- perhaps generate a UID instead of passing a userid
        return 'transactions_userid_' . $request->userId . '_' . date('Y_m_d_H_i_s') . '.csv';
    }
}
