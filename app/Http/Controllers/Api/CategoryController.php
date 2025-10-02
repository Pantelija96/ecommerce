<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return $this->apiResponse(CategoryResource::collection($categories), 'Category list');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return $this->apiResponse(new CategoryResource($category), 'Category created successfully', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);
        $category->refresh();

        return $this->apiResponse(new CategoryResource($category), 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->apiResponse(null, 'Category deleted successfully', 204);
    }
}
