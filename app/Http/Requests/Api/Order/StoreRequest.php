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
            'client_id' => 'required|exists:clients,id',
            'order_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
