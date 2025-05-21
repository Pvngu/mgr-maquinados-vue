<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends BaseModel
{
    use HasFactory;

    protected $table = 'clients';

    protected $hidden = ['id'];

    protected $default = ['name', 'email', 'phone', 'address'];

    protected $appends = ['xid'];

    protected $filterable = [];

    protected $hashableGetterFunctions = [];

    protected $casts = [];

    protected static function boot()
    {
        parent::boot();
    }
}
