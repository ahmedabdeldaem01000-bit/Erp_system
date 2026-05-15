<?php

namespace App\Service\Product;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class ProductService
{

    public function index()
    {
        $products = Product::with('category')->get();
        return $products;
    }





    public function store($request)
    {
        $imageName = null;

        if ($request->hasFile('image')) {

            $imageName = time() . '_' . $request->image->getClientOriginalName();

            $request->image->storeAs('product', $imageName, 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imageName,
            'description' => $request->description,
            'category_id' => $request->category_id,

        ]);



        return $product;
    }

    public function update($request, $id)
    {
        $product = Product::findOrFail($id);

        $imageName = $product->image;

        if ($request->hasFile('image')) {

            // delete old image
            if (
                $product->image &&
                Storage::disk('public')->exists('product/' . $product->image)
            ) {

                Storage::disk('public')->delete('product/' . $product->image);
            }

            $imageName = time() . '_' . $request->image->getClientOriginalName();

            $request->image->storeAs('product', $imageName, 'public');
        }

        $product->update([

            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $imageName,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return $product;
    }
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return $product;
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // delete image
        if (
            $product->image &&
            Storage::disk('public')->exists('product/' . $product->image)
        ) {

            Storage::disk('public')->delete('product/' . $product->image);
        }

        $product->delete();

        return true;
    }
}