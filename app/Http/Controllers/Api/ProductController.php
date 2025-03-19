<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Product\IndexRequest;
use App\Http\Requests\Api\Product\StoreRequest;
use App\Http\Requests\Api\Product\UpdateRequest;
use App\Http\Requests\Api\Product\DeleteRequest;

class ProductController extends ApiBaseController
{
    protected $model = Product::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query) {
        $request = request();

        return $query;
    }
}
