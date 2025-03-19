<?php

namespace App\Http\Requests\Api\OrderItem;

use App\Http\Requests\Api\BaseRequest;

class IndexRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // ...validation rules...
        ];
    }
}
