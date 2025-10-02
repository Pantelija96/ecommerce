<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Order;
use App\Models\Payment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponse;
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Order $order)
    {
        $user = $request->user();
        if ($order->user_id !== $user->id) {
            return $this->apiResponse(null, 'Unauthorized', 403, false);
        }

        if ($order->payments()->where('status', 'success')->exists()) {
            return $this->apiResponse(null, 'Order already paid', 400, false);
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'amount'   => $order->total_amount,
            'status'   => 'success'
        ]);

        return $this->apiResponse(new PaymentResource($payment), 'Payment processed successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return $this->apiResponse(new PaymentResource($payment), 'Payment details retrieved');
    }
}
