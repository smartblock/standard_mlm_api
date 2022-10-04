<?php

namespace App\Http\Requests\Admin\Wallet;

use App\Http\Requests\PaginationRequest;

class StatementListRequest extends PaginationRequest
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
        $settings = parent::rules(); // TODO: Change the autogenerated stub
        $settings['username'] = 'required|min:3';
        $settings['wallet_type'] = 'sometimes|min:3';
        $settings['trans_type'] = 'sometimes|min:3';
        $settings['date_from'] = 'sometimes|date_format:Y-m-d';

        return $settings;
    }
}
