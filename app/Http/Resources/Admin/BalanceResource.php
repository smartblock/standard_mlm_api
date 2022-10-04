<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class BalanceResource extends JsonResource
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
            'wallet_code' => $this->code,
            'balance' => $this->balance,
            'total_credit' => $this->total_credit ?? 0,
            'total_debit' => $this->total_debit ?? 0,
        ];
    }
}
