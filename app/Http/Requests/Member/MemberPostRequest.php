<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class MemberPostRequest extends FormRequest
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
            'sponsor' => 'required|min:3',
            'country_code' => 'required|min:2',
//            'username' => 'required|alpha_dash|min:3|max:25|unique:users,username,'.$this->id,
            'username' => 'required|alpha_dash|min:3|max:25',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|min:3|max:100',
            'identity_no' => 'required',
            'dob' => 'required|date_format:Y-m-d',
            'mobile_no' => 'required',
            'password' => 'required|min:8|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'password_confirmation' => 'required|min:8'
        ];
    }
}
