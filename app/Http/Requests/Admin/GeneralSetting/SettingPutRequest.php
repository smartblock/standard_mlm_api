<?php

namespace App\Http\Requests\Admin\GeneralSetting;

use Illuminate\Foundation\Http\FormRequest;

class SettingPutRequest extends FormRequest
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
            'country_code' => 'required|min:2',
            'name' => 'required|min:3|max:50',
            'status' => 'required|in:A,I'
        ];
    }
}
