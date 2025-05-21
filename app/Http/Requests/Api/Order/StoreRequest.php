<?php

namespace App\Http\Requests\Api\Order;

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
            'client_id' => 'required',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'user_id' => 'required|exists',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:16|regex:/^\+?[0-9\s\-()]{7,20}$/',
            'order_items' => 'nullable|array',
            'order_items.*.product_id' => 'required_with:order_items|exists:products,id',
            'order_items.*.quantity' => 'required_with:order_items|integer|min:1',
            'order_items.*.price' => 'required_with:order_items|numeric|min:0',
        ];
    }
}
