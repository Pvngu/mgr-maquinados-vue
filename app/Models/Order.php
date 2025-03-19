<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends BaseModel
{
    use HasFactory;

    protected $table = 'orders';

    protected $hidden = ['id', 'client_id', 'user_id'];

    protected $appends = ['xid', 'x_client_id', 'x_user_id'];

    protected $filterable = [];

    protected $hashableGetterFunctions = [
        'getXClientIdAttribute' => 'client_id',
        'getXUserIdAttribute' => 'user_id',
    ];

    protected $casts = [
        'client_id' => Hash::class . ':hash',
        'user_id' => Hash::class . ':hash',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
