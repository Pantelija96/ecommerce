<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cartItems = $request->user()->carts()->with('product')->get();
        return $this->apiResponse(CartResource::collection($cartItems), 'Cart retrieved successfully');
    }

    public function store(CartRequest $request)
    {
        $user = $request->user();
        $productId = $request->product_id;
        $quantity = $request->quantity;

        $product = Product::findOrFail($productId);

        if ($product->stock < $quantity) {
            return $this->apiResponse(null, 'Not enough stock', 400, false);
        }

        $cartItem = Cart::updateOrCreate(
            ['user_id' => $user->id, 'product_id' => $productId],
            ['quantity' => $quantity]
        );

        return $this->apiResponse(new CartResource($cartItem), 'Product added/updated in cart', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CartRequest $request, Cart $cart)
    {
        $quantity = $request->quantity;
        $product = $cart->product;
        if ($product->stock < $quantity) {
            return $this->apiResponse(null, 'Not enough stock', 400, false);
        }

        $cart->update(['quantity' => $quantity]);
        $cart->refresh();
        return $this->apiResponse(new CartResource($cart), 'Cart updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Cart $cart)
    {
        if ($cart->user_id !== $request->user()->id) {
            return $this->apiResponse(null, 'Unauthorized', 403, false);
        }
        $cart->delete();
        return $this->apiResponse(null, 'Product removed from cart', 204);
    }
}
