<?php

namespace App\Http\Controllers;

use App\Service\Purchase\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $purchaseService;

    public function __construct(PurchaseService  $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }
    public function index()
    {
        $purchases = $this->purchaseService->index();
        return response()->json([
            'message' => 'success',
            'data' => $purchases
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $purchase = $this->purchaseService->store($request);
        return response()->json([
            'message' => 'purchase Created successfully',
            'data' => $purchase
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = $this->purchaseService->show($id);
        return response()->json([
            'message' => 'purchase Created successfully',
            'data' => $purchase
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $purchase = $this->purchaseService->update($request, $id);
        return response()->json([
            'message' => 'purchase Updated successfully',
            'data' => $purchase
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->purchaseService->destroy($id);
        return response()->json([
            'message' => 'purchase Deleted successfully',

        ]);
    }
}
