<?php

namespace App\Service\Purchase;

use App\Models\Product;
use App\Models\Purchases;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function index()
    {
        return Purchases::with('items')->get();
    }

   public function store($request)
{
    return DB::transaction(function () use ($request) {

        $total = 0;

        $purchase = Purchases::create([
            'supplier_id' => $request->supplier_id,
            'total' => 0,
        ]);

        foreach ($request->items as $item) {

            $purchase->items()->create([
                'name'       => $item['name'],
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);

            $product = Product::findOrFail($item['product_id']);

            $product->increment('quantity', $item['quantity']);

            $total += $item['quantity'] * $item['price'];
        }

        $purchase->update([
            'total' => $total
        ]);

        return $purchase->load('items');
    });
}

 public function update($request, $id)
{
    return DB::transaction(function () use ($request, $id) {

        $purchase = Purchases::with('items')->findOrFail($id);

        
        foreach ($purchase->items as $oldItem) {

            $product = Product::find($oldItem->product_id);

            if ($product) {
                $product->decrement('quantity', $oldItem->quantity);
            }
        }

       
        $purchase->items()->delete();

        $total = 0;

       
        foreach ($request->items as $item) {

            $purchase->items()->create([
                'name'       => $item['name'],
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);

            $product = Product::findOrFail($item['product_id']);

          
            $product->increment('quantity', $item['quantity']);

         
            $total += $item['quantity'] * $item['price'];
        }

      
        $purchase->update([
            'supplier_id' => $request->supplier_id,
            'total'       => $total,
        ]);

        return $purchase->fresh()->load('items');
    });
}

    public function show(string $id)
    {
        return Purchases::with('items')->findOrFail($id);
    }

    public function destroy($id)
    {
        $purchase = Purchases::findOrFail($id);

        $purchase->items()->delete();
        $purchase->delete();

        return true;
    }
}