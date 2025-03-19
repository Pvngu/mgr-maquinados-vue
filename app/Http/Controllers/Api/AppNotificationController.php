<?php

namespace App\Http\Controllers\Api;

use App\Models\AppNotification;

use App\Http\Controllers\ApiBaseController;

class AppNotificationController extends ApiBaseController
{
    protected $model = AppNotification::class;
}
