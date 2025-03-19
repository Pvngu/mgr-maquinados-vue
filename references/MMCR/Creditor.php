<?php

namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Creditor extends BaseModel
{
    use HasFactory;
    
    protected $table = 'creditors';

    protected $default = ['name'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // you must hide the id column and any foreign key columns and then append the hashid column of the foreign keys. Also include the foreign keys in the hashableGetterFunctions and casts
    protected $hidden = ['id', 'state_id'];

    // xid is mandatory
    protected $appends = ['xid', 'x_state_id'];

    // Add filters and extra filters here. Id is mandatory
    protected $filterable = ['id', 'status'];

    protected $hashableGetterFunctions = [
        'getXStateIdAttribute' => 'state_id',
    ];

    protected $casts = [
        'state_id' => Hash::class . ':hash',
    ];

    protected static function boot()
    {
        parent::boot();
    }
}
