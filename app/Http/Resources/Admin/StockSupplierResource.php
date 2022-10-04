<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\BaseResource;

class StockSupplierResource extends BaseResource
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
            'code' => $this->code,
            'name' => $this->name,
            'seq_no' => $this->seq_no,
            'status' => $this->statusTranslation($this->status)
        ];
    }
}
