<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\BaseResource;

class AnnouncementResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $content = [];
        foreach ($this->details as $key => $value) {
            $content[$key]['title'] = $value['title'];
            $content[$key]['description'] = $value['description'];
            $content[$key]['lang_code'] = $value->language->code;
            $content[$key]['lang_name'] = $value->language->name;
        }

        return [
            'id' => encrypt($this->id),
            'code' => $this->code,
            'name' => $this->name,
            'avatar' => asset("images/{$this->avatar}"),
            'date_start' => $this->date_start,
            'date_end' => $this->date_end ?? "",
            'seq_no' => $this->seq_no,
            'is_popup' => $this->is_popup,
            'content' => $content,
            'created_at' => $this->created_at,
            'created_by' => $this->createdBy->username ?? "",
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updatedBy->username ?? ""
        ];
    }
}
