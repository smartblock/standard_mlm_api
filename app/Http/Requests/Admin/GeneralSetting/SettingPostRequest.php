<?php

namespace App\Http\Requests\Admin\GeneralSetting;

use Illuminate\Foundation\Http\FormRequest;

class SettingPostRequest extends FormRequest
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
            'type' => 'required|min:3|max:25',
            'code' => 'required|min:3|max:25',
            'name' => 'required|min:3|max:50'
        ];
    }
}
