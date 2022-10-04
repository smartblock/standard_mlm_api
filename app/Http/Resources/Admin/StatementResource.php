<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class StatementResource extends JsonResource
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
            'doc_date' => $this->created_at->format('Y-m-d'),
            'username' => $this->user->username,
            'wallet_type' => $this->wallet->code,
            'trans_type' => $this->trans_type,
            'remark' => $this->remark ?? "",
            'amount' => ($this->total_in > 0) ? $this->total_in : $this->total_out,
            'balance' => $this->balance
        ];
    }
}
