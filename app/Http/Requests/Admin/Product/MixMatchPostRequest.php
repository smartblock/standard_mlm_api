<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class MixMatchPostRequest extends FormRequest
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
            'country' => 'sometimes|integer',
            'code' => 'required|alpha_dash|min:3|unique:products,code',
            'name' => 'required|json',
            'description' => 'sometimes',
            'category_code' => 'required|min:3',
            'price' => 'required|min:1|numeric',
            'bv' => 'required|numeric|min:1',
            'seq_no' => 'required|integer',
            'status' => 'required|in:A,I',
            'product' => 'required|json',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'sometimes'
        ];
    }
}
