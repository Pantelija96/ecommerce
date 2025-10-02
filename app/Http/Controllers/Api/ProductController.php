<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cacheKey = 'products_'.md5(json_encode($request->all()));

        $products = Cache::remember($cacheKey, 60 * 60, function () use ($request) {
            $query = \App\Models\Product::query()
                ->filterByPrice($request->price_min, $request->price_max)
                ->searchByName($request->search);

            if ($request->category) {
                $query = $query->filterCategory($request->category);
            }

            return $query->get();
        });

        return $this->apiResponse(ProductResource::collection($products), 'Products retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());

        return $this->apiResponse(new ProductResource($product), 'Product created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $this->apiResponse(new ProductResource($product), 'Product details retrieved');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        $product->refresh();

        return $this->apiResponse(new ProductResource($product), 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return $this->apiResponse(null, 'Product deleted successfully', 204);
    }
}
