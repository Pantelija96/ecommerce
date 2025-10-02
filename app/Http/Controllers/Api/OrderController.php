<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
    use ApiResponse;
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

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
        $order = $this->orderService->createOrderFromCart($user);
        if (!$order) {
            return $this->apiResponse(null, 'Cart is empty or some products are out of stock', 400, false);
        }
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
