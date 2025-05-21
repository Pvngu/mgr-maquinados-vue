<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get count from command or default to 25
        $count = request()->command ? request()->command->argument('count') : 25;
        
        // Create orders
        Order::factory()
            ->count($count)
            ->create()
            ->each(function ($order) {
                // For each order, create between 1-5 order items
                $itemCount = rand(1, 5);
                
                // Generate order items
                $orderItems = OrderItem::factory()
                    ->count($itemCount)
                    ->make([
                        'order_id' => $order->id
                    ]);
                
                // Calculate total amount from items
                $totalAmount = 0;
                foreach ($orderItems as $item) {
                    $totalAmount += $item->price * $item->quantity;
                }
                
                // Save each item
                foreach ($orderItems as $item) {
                    $item->save();
                }
                
                // Update order with calculated total amount
                $order->update([
                    'total_amount' => $totalAmount
                ]);
            });
    }
}
