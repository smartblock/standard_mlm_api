<?php

namespace App\Http\Requests\Admin\ProductCategory;

use Illuminate\Foundation\Http\FormRequest;

class CategoryPutRequest extends FormRequest
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
            'parent' => 'required',
            'category_code' => 'required|alpha_dash|unique:product_categories,id,'.decrypt($this->id).',category_code|min:3',
            'category_name' => 'required|json',
            'seq_no' => 'required|integer',
            'status' => 'required|in:A,I'
        ];
    }
}
