<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class PackagePostRequest extends FormRequest
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
            'code' => 'required',
            'name' => 'required|json',
            'description' => 'sometimes',
            'category_code' => 'required',
            'price' => 'required|min:1|numeric',
            'bv' => 'required|min:1|numeric',
            'seq_no' => 'required|integer',
            'status' => 'required|in:A,I',
            'weight' => 'required|min:0.01|numeric',
            'product' => 'required|json',
        ];
    }
}
