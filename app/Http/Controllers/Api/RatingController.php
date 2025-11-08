<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($serviceProviderId)
    {
        $serviceProviderId = User::find($serviceProviderId);
        dd($serviceProviderId);
        $ratings  = Rating::with('serviceProvider')->latest()->get();

        return ApiResponse::SendResponse(200, 'ratings retrived sussecfully', RatingResource::collection($ratings));
    }

    public function store(StoreRatingRequest $request)
    {
        $data = $request->validated();

        // منع المستخدم من تقييم نفسه
        if (Auth::id() == $data['service_provider_id']) {
            return ApiResponse::SendResponse(400, 'You cannot rate yourself.', []);
        }

        // التأكد أن المزود موجود
        if (!User::where('id', $data['service_provider_id'])->exists()) {
            return ApiResponse::SendResponse(404, 'Service provider not found.', []);
        }
        if (!Auth::check()) {
            return ApiResponse::SendResponse(401, 'You must be logged in to rate.', []);
        }
        // إضافة user_id تلقائيًا من الـAuth
        $data['user_id'] = Auth::id();

        // التأكد أن المستخدم لم يقم بتقييم هذا المزود مسبقًا
        if (Rating::where('user_id', $data['user_id'])
            ->where('service_provider_id', $data['service_provider_id'])
            ->exists()
        ) {
            return ApiResponse::SendResponse(400, 'You have already rated this provider.', []);
        }


        $rating = Rating::create($data);
        return ApiResponse::SendResponse(201, 'Rating created successfully.', new RatingResource($rating));
    }

    public function topRatedProvider()
    {
        $provider = User::withAvg('ratings', 'rating')   // حساب متوسط التقييم
            ->withCount('ratings')          // عدد التقييمات
            ->has('ratings')               // فقط المزودين اللي عندهم تقييمات
            ->orderByDesc('ratings_avg_rating') // ترتيب حسب الأعلى تقييمًا
            ->first();                     // جلب المزود الأعلى فقط

        if (!$provider) {
            return ApiResponse::SendResponse(404, 'No rated providers found.', []);
        }

        $result = [
            'provider_id' => $provider->id,
            'provider_name' => $provider->name,
            'avg_rating' => round($provider->ratings_avg_rating, 2),
            'total_ratings' => $provider->ratings_count,
        ];

        return ApiResponse::SendResponse(200, 'Top rated provider retrieved successfully.', $result);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rating = Rating::find($id);
        if (!$rating) {
            return ApiResponse::SendResponse(404, 'Rating not found.', []);
        }

        // التأكد أن المستخدم صاحب التقييم أو Admin
        if ($rating->user_id != Auth::id()) {
            return ApiResponse::SendResponse(403, 'You are not allowed to delete this rating.', []);
        }

        $rating->delete();
        return ApiResponse::SendResponse(200, 'Rating deleted successfully.', []);
    }
}
