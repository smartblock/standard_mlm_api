<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
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
            'code' => $this->code,
            'mobile_no' => $this->profile->mobile_no ?? "",
            'email' => $this->email,
            'sponsor' => $this->sponsor->code ?? "",
            'country' => $this->country->name,
            'status' => empty($this->deleted_at) ? trans('label.active') : trans('label.inactive'),
            'created_at' => $this->created_at
        ];
    }
}
