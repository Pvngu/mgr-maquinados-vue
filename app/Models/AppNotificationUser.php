<?php

namespace App\Models;

use App\Models\BaseModel;

class AppNotificationUser extends BaseModel
{

    protected $table = 'app_notification_users';
    protected $appends = ['xid'];

    protected static function boot()
    {
        parent::boot();
    }

    public function notification()
    {
        return $this->belongsTo(AppNotification::class, 'app_notification_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
