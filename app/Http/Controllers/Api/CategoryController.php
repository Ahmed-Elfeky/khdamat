<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Helpers\ApiResponse;

class CategoryController extends Controller
{
    /**
     * عرض جميع التصنيفات
     */
    public function index()
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return ApiResponse::SendResponse(404, 'No categories found', []);
        }

        return ApiResponse::SendResponse(
            200,
            'All categories retrieved successfully',
            CategoryResource::collection($categories)
        );
    }

    /**
     * إضافة تصنيف جديد
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $category = Category::create($data);

        return ApiResponse::SendResponse(
            201,
            'Category created successfully',
            new CategoryResource($category)
        );
    }

  
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return ApiResponse::SendResponse(404, 'Category not found', []);
        }

        $category->delete();

        return ApiResponse::SendResponse(200, 'Category deleted successfully');
    }
}
