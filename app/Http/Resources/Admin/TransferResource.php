<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => encrypt($this->id),
            'doc_date' => $this->created_at,
            'wallet_type' => $this->wallet->code,
            'sender' => $this->sender->username,
            'receiver' => $this->receiver->username,
            'amount' => $this->transfer_amount
        ];
    }
}
