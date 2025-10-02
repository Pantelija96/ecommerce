<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class OrderService
{
    public function createOrderFromCart(User $user): ?Order
    {
        $cartItems = $user->carts()->with('product')->get();
        if ($cartItems->isEmpty()) {
            return null;
        }

        $totalAmount = 0;

        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return null;
            }
            $totalAmount += $item->quantity * $item->product->price;
        }

        $totalAmount = $this->applyDiscount($totalAmount);
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($cartItems as $item) {
            $item->product->decrement('stock', $item->quantity);
        }

        $user->carts()->delete();
        return $order;
    }

    private function applyDiscount(float $total): float
    {
        if ($total > 100) {
            $total *= 0.9; // 10% discount
        }

        return round($total, 2);
    }
}
