<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Client\IndexRequest;
use App\Http\Requests\Api\Client\StoreRequest;
use App\Http\Requests\Api\Client\UpdateRequest;
use App\Http\Requests\Api\Client\DeleteRequest;

class ClientController extends ApiBaseController
{
    protected $model = Client::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query) {
        $request = request();

        return $query;
    }
}
