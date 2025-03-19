<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends BaseModel
{
    use HasFactory;

    protected $table = 'categories';

    protected $hidden = ['id'];

    protected $appends = ['xid'];

    protected $filterable = [];

    protected $hashableGetterFunctions = [];

    protected $casts = [];

    protected static function boot()
    {
        parent::boot();
    }
}
