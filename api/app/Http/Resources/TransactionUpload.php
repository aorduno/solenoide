<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionUpload extends JsonResource
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
            'id' => $this->id,
            'type' => 'transactionUpload',
            'attributes' => [
                'filepath' => $this->filepath,
                'status' => $this->status,
                'completed' => $this->completed,
                'failed' => $this->failed,
            ]
        ];
    }
}
