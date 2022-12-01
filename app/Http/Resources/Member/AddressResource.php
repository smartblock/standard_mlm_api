<?php

namespace App\Http\Resources\Member;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'recipient_name' => $this->name,
            'email' => $this->email ?? "",
            'address' => $this->address1,
            'mobile_prefix' => $this->country->calling_no_prefix,
            'mobile_no' => $this->mobile_no ?? "",
            'is_default_billing' => $this->is_default_billing,
            'is_default_shipping' => $this->is_default_shipping,
        ];
    }
}
