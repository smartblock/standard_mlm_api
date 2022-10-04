<?php

namespace App\Http\Requests\Admin\Stock;

use Illuminate\Foundation\Http\FormRequest;

class SupplierPostRequest extends FormRequest
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
            'code' => 'required|alpha_dash|min:3|max:255|unique:stock_suppliers,code',
            'name' => 'required|min:3|max:255',
            'seq_no' => 'required|integer',
            'status' => 'required|in:A,I'
        ];
    }
}
