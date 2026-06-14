<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
          $users = User::all();
    $products = Product::all();

    if ($users->isEmpty() || $products->isEmpty()) {
        throw new \Exception("Seed Users and Products first before Orders.");
    }

        foreach (range(1, 20) as $i) {

            $order = Order::create([
                'user_id' => $users->random()->id,
                'total' => 0,
            ]);

            $total = 0;

            foreach (range(1, rand(1, 5)) as $x) {

                $product = $products->random();
                $quantity = rand(1, 5);

                $lineTotal = $product->price * $quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total' => $lineTotal,
                ]);

                $total += $lineTotal;
            }

            $order->update([
                'total' => $total
            ]);
        }
    }
}
