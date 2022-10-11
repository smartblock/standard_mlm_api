<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\BaseResource;

class StockLocationResource extends BaseResource
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
            'stock_code' => $this->stock_code,
            'stock_name' => $this->stock_name,
            'seq_no' => $this->seq_no ?? "",
            'created_at' => $this->created_at
        ];
    }
}
