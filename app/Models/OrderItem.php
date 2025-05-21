<?php

namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends BaseModel
{
    use HasFactory;

    protected $table = 'order_items';

    protected $appends = ['xid', 'x_order_id', 'x_product_id'];

    protected $filterable = [];

    protected $hashableGetterFunctions = [
        'getXOrderIdAttribute' => 'order_id',
        'getXProductIdAttribute' => 'product_id',
    ];

    protected $casts = [
        'order_id' => Hash::class . ':hash',
        'product_id' => Hash::class . ':hash',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
