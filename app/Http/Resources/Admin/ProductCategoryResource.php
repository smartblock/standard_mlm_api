<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $status = "";
        switch ($this->status) {
            case "A":
                $status = trans("common.active");
                break;
            case "I":
                $status = trans("common.inactive");
                break;
        }

        return [
            'id' => encrypt($this->id),
            'category_code' => $this->category_code ?? "",
            'category_name' => $this->category_name ?? "",
            'parent' => $this->parent->category_code ?? "",
            'seq_no' => $this->seq_no ?? "",
            'status' => $status,
            'has_child' => ($this->child->count() > 0) ? 1 : 0
        ];
    }
}
