<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => Client::inRandomOrder()->first()->id ?? Client::factory(),
            'order_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'total_amount' => $this->faker->randomFloat(2, 100, 10000),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'contact_name' => $this->faker->name,
            'contact_email' => $this->faker->safeEmail,
            'contact_phone' => $this->faker->phoneNumber,
        ];
    }
}
