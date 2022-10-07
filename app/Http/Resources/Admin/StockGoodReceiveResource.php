<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class StockGoodReceiveResource extends JsonResource
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
            'doc_no' => $this->doc_no,
            'stock_code' => $this->stock->stock_name ?? "",
            'doc_date' => $this->doc_date,
            'trans_type' => $this->trans_type,
            'status' => $this->status,
            'ref_no' => $this->ref_no ?? "",
            'remark' => $this->remark ?? "",
            'created_at' => $this->created_at
        ];
    }
}
