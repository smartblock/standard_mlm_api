<?php

namespace App\Http\Requests\Member\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePostRequest extends FormRequest
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
            'action_type' => 'required'
        ];
    }
}
