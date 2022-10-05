<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleTreeResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'parent_id' => empty($this->parent_id) ? "" : encrypt($this->parent_id),
            'parent_name' => $this->parentRole->name ?? "",
            'seq_no' => $this->seq_no,
            'guard_name' => $this->guard_name,
            'children' => self::collection($this->children),
            'status' => $this->status ?? "",
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
