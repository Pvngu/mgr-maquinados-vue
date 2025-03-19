<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Order\IndexRequest;
use App\Http\Requests\Api\Order\StoreRequest;
use App\Http\Requests\Api\Order\UpdateRequest;
use App\Http\Requests\Api\Order\DeleteRequest;

class OrderController extends ApiBaseController
{
    protected $model = Order::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query) {
        $request = request();

        return $query;
    }
}
