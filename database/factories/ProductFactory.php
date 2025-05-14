<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 10000),
            'stock_quantity' => $this->faker->numberBetween(0, 1000),
            'initial_stock_quantity' => $this->faker->numberBetween(0, 1000),
            'category_id' => \App\Models\Category::factory(),
        ];
    }
}
