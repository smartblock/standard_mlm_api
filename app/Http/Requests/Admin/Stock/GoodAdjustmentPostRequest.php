<?php

namespace App\Http\Requests\Admin\Stock;

use Illuminate\Foundation\Http\FormRequest;

class GoodAdjustmentPostRequest extends FormRequest
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
            'stock_code' => 'sometimes',
            'reason' => 'required',
            'remark' => 'sometimes',
            'date' => 'required|date_format:Y-m-d',
            'ref_no' => 'sometimes'
        ];
    }
}
