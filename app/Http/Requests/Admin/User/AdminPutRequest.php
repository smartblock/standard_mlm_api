<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class AdminPutRequest extends FormRequest
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
            'username' => 'required|min:6|unique:users,username,'.decrypt($this->id).',id',
            'name' => 'required|min:6',
            'email' => 'required|email|unique:users,email,'.decrypt($this->id).',id',
            'status' => 'required|max:3|in:A,I',
            'password' => 'sometimes',
            'role' => 'required'
        ];
    }
}
