<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Category\IndexRequest;
use App\Http\Requests\Api\Category\StoreRequest;
use App\Http\Requests\Api\Category\UpdateRequest;
use App\Http\Requests\Api\Category\DeleteRequest;

class CategoryController extends ApiBaseController
{
    protected $model = Category::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query) {
        $request = request();

        return $query;
    }
}
