<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $profile = $this->profile ?? "";
        return [
            'fullname' => $this->name,
            'id' => $this->code,
            'country' => $this->country->name,
            'ic_no' => $profile->ic_no ?? "",
            'mobile_no' => $profile->mobile_no ?? "",
            'email' => $this->email,
            'gender' => $profile->gender ?? "",
            'status' => $this->status == "A" ? trans('active') : trans('inactive'),
            'dob' => $profile->date_of_birth ?? "",
            'joined_date' => $profile->created_at ?? ""
        ];
    }
}
