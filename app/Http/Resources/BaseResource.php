<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function statusTranslation(string $status)
    {
        $status_txt = "";
        switch (strtolower($status)) {
            case "a":
                $status_txt = trans('label.active');
                break;
            case 'i':
                $status_txt = trans('label.inactive');
                break;
        }

        return $status_txt;
    }
}
