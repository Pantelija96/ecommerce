<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('payments')->get();
        return $this->apiResponse(OrderResource::collection($orders), 'Orders retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $cartItems = $user->carts()->with('product')->get();
        if ($cartItems->isEmpty()) {
            return $this->apiResponse(null, 'Cart is empty', 400, false);
        }

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return $this->apiResponse(null, "Not enough stock for {$item->product->name}", 400, false);
            }
            $totalAmount += $item->quantity * $item->product->price;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);
        foreach ($cartItems as $item) {
            $item->product->decrement('stock', $item->quantity);
        }

        $user->carts()->delete();
        return $this->apiResponse(new OrderResource($order), 'Order created successfully', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(OrderRequest $request, Order $order)
    {
        $order->update([
            'status' => $request->status,
        ]);
        $order->refresh();

        return $this->apiResponse(new OrderResource($order), 'Order status updated successfully');
    }
}
