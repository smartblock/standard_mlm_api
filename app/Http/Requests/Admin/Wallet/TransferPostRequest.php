<?php

namespace App\Http\Requests\Admin\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class TransferPostRequest extends FormRequest
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
            'username_from' => 'required|min:3',
            'username_to' => 'required|min:3',
            'wallet_from' => 'required|min:2',
            'wallet_to' => 'required|min:2',
            'amount' => 'required|numeric',
            'remark' => 'sometimes'
        ];
    }

    public function attributes()
    {
        return [
            'username_from' => trans('validate.wallet_transfer_sender'),
            'username_to' => trans('validate.wallet_transfer_receiver')
        ];
    }
}
