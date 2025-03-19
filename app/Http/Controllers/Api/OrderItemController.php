<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderItem;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\OrderItem\IndexRequest;
use App\Http\Requests\Api\OrderItem\StoreRequest;
use App\Http\Requests\Api\OrderItem\UpdateRequest;
use App\Http\Requests\Api\OrderItem\DeleteRequest;

class OrderItemController extends ApiBaseController
{
    protected $model = OrderItem::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query) {
        $request = request();

        return $query;
    }
}
