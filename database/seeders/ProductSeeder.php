<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Ensure categories exist before products
        if (Category::count() === 0) {
            \App\Models\Category::factory()->count(5)->create();
        }
        Product::factory()->count(20)->create();
    }
}
