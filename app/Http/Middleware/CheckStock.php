<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    use ApiResponse;

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $cartItems = $user->carts()->with('product')->get();

        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return $this->apiResponse(
                    null,
                    "Not enough stock for product: {$item->product->name}",
                    400,
                    false
                );
            }
        }

        return $next($request);
    }
}
