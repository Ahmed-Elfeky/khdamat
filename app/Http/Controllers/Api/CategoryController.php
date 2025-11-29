<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Helpers\ApiResponse;
use App\Models\User;
use App\Http\Resources\UserResource;

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

    public function providers($id)
    {
        // نجيب المستخدمين اللي عندهم إعلانات داخل الكاتيجوري المحدد
        $users = User::whereHas('ads', function ($q) use ($id) {
            $q->where('category_id', $id);
        })
            ->withAvg('receivedRatings as average_rating', 'rating')
            ->orderByDesc('average_rating')
            ->get();

        return ApiResponse::SendResponse(
            200,
            "Providers retrieved successfully",
            $users
        );
    }


    public function getUsersByCategory($categoryId)
    {
        $users = User::whereHas('ads', function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        })
            ->with([
                'ads' => function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                }])
            ->withAvg('receivedRatings', 'rating')
            ->orderByDesc('received_ratings_avg_rating')
            ->get();

        if ($users->isEmpty()) {
            return ApiResponse::SendResponse(404, 'No users found for this category', []);
        }

        return ApiResponse::SendResponse(
            200,
            'Users fetched successfully',
            UserResource::collection($users)
        );
    }
}
