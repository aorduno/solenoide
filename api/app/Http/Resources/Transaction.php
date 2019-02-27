<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
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
            'type' => 'transaction',
            'attributes' => [
                'date' => $this->date,
                'description' => $this->description,
                'amount' => $this->amount,
                'owner-id' => $this->owner_id,
            ]
        ];
    }
}
