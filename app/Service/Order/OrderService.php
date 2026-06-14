<?php

namespace App\Service\Order;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {

            $order = Order::create([
                'user_id' => $data['user_id'],
                'total' => 0,
            ]);

            return $this->syncItems($order, $data['items']);
        });
    }

    public function update(Order $order, array $data): Order
    {
        return DB::transaction(function () use ($order, $data) {

            // رجّع المخزون القديم
            foreach ($order->items as $item) {
                $item->product()->increment('stock', $item->quantity);
            }

            // امسح العناصر القديمة
            $order->items()->delete();

            $order->update([
                'user_id' => $data['user_id'],
                'total' => 0,
            ]);

            return $this->syncItems($order, $data['items']);
        });
    }

    public function delete(Order $order): void
    {
        DB::transaction(function () use ($order) {

            foreach ($order->items as $item) {
                $item->product()->increment('stock', $item->quantity);
            }

            $order->delete();
        });
    }

    /**
     * shared logic
     */
    private function syncItems(Order $order, array $items): Order
    {
        $total = 0;

        foreach ($items as $item) {

            $product = Product::lockForUpdate()
                ->findOrFail($item['product_id']);

            if ($product->stock < $item['quantity']) {
                throw new \Exception("Stock not enough for {$product->name}");
            }

            $lineTotal = $product->price * $item['quantity'];

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'total' => $lineTotal,
            ]);

            $product->decrement('stock', $item['quantity']);

            $total += $lineTotal;
        }

        $order->update(['total' => $total]);

        return $order->load('items.product', 'user');
    }
}