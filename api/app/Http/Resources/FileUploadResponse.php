<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileUploadResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => 'id',
            'type' => 'fileUploadResponse',
            'attributes' => [
                'succeed' => $this->getSucceed(),
                'path' => $this->getPath(),
                'extension' => $this->getExtension(),
                'lines' => $this->getLines(),
                'headerValid' => $this->getValidHeader(),
                'linesValid' => $this->getValidLines(),
                'linesCount' => $this->getLinesCount(),
            ]
        ];
    }
}
