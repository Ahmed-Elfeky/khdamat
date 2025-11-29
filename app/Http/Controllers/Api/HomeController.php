<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ServiceAdResource;
use App\Http\Resources\UserResource;
use App\Models\Banner;
use App\Models\Category;
use App\Models\ServiceAd;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        $ads = ServiceAd::all();
        $categories = Category::all();
        $users = User::all();
        $data = [
            'banners'    => BannerResource::collection($banners),
            'categories' => CategoryResource::collection($categories),
            'users'      => UserResource::collection($users),
            'ads'        => ServiceAdResource::collection($ads),
        ];
        return ApiResponse::SendResponse(200, 'Home data fetched successfully', $data);
    }
}
