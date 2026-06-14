<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Service\Order\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate();

        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return new OrderResource($order);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = $this->orderService->create($validated);

        return new OrderResource($order);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = $this->orderService->update($order, $validated);

        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $this->orderService->delete($order);

        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }
}