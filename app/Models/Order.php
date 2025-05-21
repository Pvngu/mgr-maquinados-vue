<?php

namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends BaseModel
{
    use HasFactory;

    protected $table = 'orders';

    protected $hidden = ['client_id', 'user_id'];

    protected $default = [
        'client_id', 'order_date', 'total_amount', 'user_id',
        'contact_name', 'contact_email', 'contact_phone',
    ];

    protected $appends = ['xid', 'x_client_id', 'x_user_id'];

    protected $fillable = [
        'client_id', 'order_date', 'total_amount', 'user_id',
        'contact_name', 'contact_email', 'contact_phone',
    ];

    protected $filterable = [];

    protected $hashableGetterFunctions = [
        'getXClientIdAttribute' => 'client_id',
        'getXUserIdAttribute' => 'user_id',
    ];

    protected $casts = [
        'client_id' => Hash::class . ':hash',
        'user_id' => Hash::class . ':hash',
        'total_amount' => 'float',
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

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
