<?php

namespace App\Http\Requests\Member\Address;

use Illuminate\Foundation\Http\FormRequest;

class AddressPostRequest extends FormRequest
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
            'label' => 'required|min:2',
            'recipient_name' => 'required',
            'email' => 'required|email',
            'country_code' => 'required',
            'mobile_no' => 'required',
            'address' => 'required|min:3',
            'city' => 'sometimes',
            'postcode' => 'required',
            'state' => 'sometimes',
            'is_default_shipping_address' => 'required|boolean',
            'is_default_billing_address' => 'required|boolean'
        ];
    }
}
