<?php

namespace App\Models;

use App\Models\BaseModel;

class SelectOption extends BaseModel
{
    protected $table = 'select_options';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();
    }
}
