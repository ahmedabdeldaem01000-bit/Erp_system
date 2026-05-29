<?php

namespace App\Http\Controllers;

use App\Service\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }



    public function index()
    {
        $product = $this->productService->index();
        return response()->json([
            'message' => 'success',
            'data' => $product
        ]);
    }


    public function store(Request $request)
    {
       
        $product = $this->productService->store($request);

        return response()->json([
            'message' => 'successfully',
            'data' => $product
        ]);
    }


    public function show(string $id)
    {
        $product = $this->productService->show($id);
        return response()->json([
            'message' => 'success',
            'data' => $product
        ]);
    }

    public function update(Request $request, string $id)
    {
    
        $product = $this->productService->update($request, $id);
        return response()->json([
            'message' => 'Successfully Updated Product',
            'data' => $product
        ]);

    }

    public function destroy(string $id){
        $this->productService->destroy($id);
        return response()->json([
            'message'=>'Product Deleted Successfully',
             
        ]);

    }

}
