<?php

namespace App\Http\Requests\Admin\Announcement;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementPostRequest extends FormRequest
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
        return [
            'country_code' => 'sometimes',
            'code' => 'sometimes',
            'content' => 'required|json',
            'avatar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'date_start' => 'sometimes|date_format:Y-m-d',
            'date_end' => 'sometimes',
            'seq_no' => 'required|integer|min:0',
            'is_popup' => 'required|in:0,1'
        ];
    }
}
