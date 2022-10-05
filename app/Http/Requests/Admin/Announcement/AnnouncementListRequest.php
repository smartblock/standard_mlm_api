<?php

namespace App\Http\Requests\Admin\Announcement;

use App\Http\Requests\PaginationRequest;

class AnnouncementListRequest extends PaginationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $settings = parent::rules(); // TODO: Change the autogenerated stub

        return $settings;
    }
}
