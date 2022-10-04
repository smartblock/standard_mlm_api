<?php

namespace App\Http\Requests\Admin\GeneralSetting;

use Illuminate\Foundation\Http\FormRequest;

class SettingListRequest extends FormRequest
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
            'page' => 'required|integer|min:1',
            'limit' => 'integer|min:15',
            'type' => 'min:3|max:25',
        ];
    }
}
