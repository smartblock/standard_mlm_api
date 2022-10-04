<?php

namespace App\Http\Requests\Admin\SysLanguage;

use Illuminate\Foundation\Http\FormRequest;

class SysLanguagePostRequest extends FormRequest
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
            'locale' => 'required|min:2|unique:App\Models\SysLanguage,locale,NULL,id,deleted_at,NULL',
            'code' => 'required|min:2|unique:App\Models\SysLanguage,code,NULL,id,deleted_at,NULL',
            'name' => 'required|min:2',
            'avatar' => 'required',
            'seq_no' => 'integer',
            'avatar' => 'file',
        ];
    }
}
