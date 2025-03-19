<?php

namespace App\Http\Requests\Api\OrderItem;

use App\Http\Requests\Api\BaseRequest;

class DeleteRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
        ];
    }
}
