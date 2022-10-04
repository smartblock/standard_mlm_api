<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'status' => empty($this->deleted_at) ? trans('label.active') : trans('label.inactive'),
            'role' => $this->getRoleNames()[0] ?? "",
            'created_at' => $this->created_at
        ];
    }
}
