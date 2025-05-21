<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Casts\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseModel
{
    use HasFactory;

    protected $table = 'products';

    protected $default = ['name', 'description', 'price', 'stock_quantity'];

    protected $hidden = ['category_id'];

    protected $appends = ['xid', 'x_category_id'];

    protected $filterable = [
        'name', 'description'
    ];

    protected $hashableGetterFunctions = [
        'getXCategoryIdAttribute' => 'category_id',
    ];

    protected $casts = [
        'category_id' => Hash::class . ':hash',
        'price' => 'float',
        'stock_quantity' => 'integer',
        'initial_stock_quantity' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
