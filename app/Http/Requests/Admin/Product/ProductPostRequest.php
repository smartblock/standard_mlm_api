<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductPostRequest extends FormRequest
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
            'code' => 'required|min:3|unique:products,code',
            'name' => 'required|json',
            'description' => 'sometimes',
            'category' => 'required',
            'price' => 'required|min:1|numeric',
            'bv' => 'required|min:1|numeric',
            'seq_no' => 'required|integer',
            'status' => 'required|in:A,I',
            'weight' => 'required|min:0.01|numeric',
//            'delivery_group' => 'required',
            'variant' => 'sometimes',
            'images' => 'required|png,jpg|max:2048'
        ];
    }
}
