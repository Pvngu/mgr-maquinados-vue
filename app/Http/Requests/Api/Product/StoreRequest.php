<?php

namespace App\Http\Requests\Api\Product;

use App\Http\Requests\Api\BaseRequest;

class StoreRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'initial_stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
