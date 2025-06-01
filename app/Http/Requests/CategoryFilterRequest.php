<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryFilterRequest extends FormRequest
{
    public function rules(): array
    {
        \Illuminate\Support\Facades\Log::debug('request:', ['request' => $this->all()]);
        return [
            'category_name' => 'required|string',
            'search' => 'nullable|string|min:2',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string',
            'rating_min' => 'nullable|numeric|min:0|max:10',
            'discount' => 'nullable|boolean',
            'sort' => 'nullable|string|in:price_asc,price_desc,rating_desc,asc,desc',
            'page' => 'required|integer|min:1',
        ];
    }
    protected function failedValidation($validator)
    {
        \Illuminate\Support\Facades\Log::error('Validation failed:', ['errors' => $validator->errors()->all()]);
        abort(422, json_encode($validator->errors()->all()));
    }
    

}
