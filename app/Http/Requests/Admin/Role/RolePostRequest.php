<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class RolePostRequest extends FormRequest
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
            'code' => 'required|min:4|unique:roles,code',
            'name' => 'required|min:4',
            'seq_no' => 'required|integer',
//            'guard_name' => 'required|in:admin,member',
            'parent_code' => 'required|min:4'
        ];
    }
}
