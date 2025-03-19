<?php

namespace App\Http\Controllers\Api;

use App\Models\Creditor;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Creditor\StoreRequest;
use App\Http\Requests\Api\Creditor\UpdateRequest;
use App\Http\Requests\Api\Creditor\DeleteRequest;
use App\Http\Requests\Api\Creditor\IndexRequest;

class CreditorController extends ApiBaseController
{
    protected $model = Creditor::class;
    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query) {
        $request = request();

        // Dates Filters
        if ($request->has('dates') && $request->dates != "") {
            $dates = explode(',', $request->dates);
            $startDate = $dates[0];
            $endDate = $dates[1];

            $query = $query->whereRaw('creditors.date_time >= ?', [$startDate])
                ->whereRaw('creditors.date_time <= ?', [$endDate]);
        }

        return $query;
    }

    public function storing($creditor) {
        return $creditor;
    }
}
