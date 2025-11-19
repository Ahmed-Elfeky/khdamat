<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Resources\RatingResource;
use App\Http\Resources\TopProviderResource;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{

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

        if (!Auth::check()) {
            return ApiResponse::SendResponse(401, 'You must be logged in to rate.', []);
        }

        $data['user_id'] = Auth::id(); // ← هنا نضمن أن الـ user_id صحيح

        // منع المستخدم من تقييم نفسه
        if (Auth::id() == $data['service_provider_id']) {
            return ApiResponse::SendResponse(400, 'You cannot rate yourself.', []);
        }

        // التأكد أن المزود موجود
        if (!User::where('id', $data['service_provider_id'])->exists()) {
            return ApiResponse::SendResponse(404, 'Service provider not found.', []);
        }

        // التأكد أن المستخدم لم يقم بتقييم هذا المزود مسبقًا
        if (Rating::where('user_id', Auth::id())
            ->where('service_provider_id', $data['service_provider_id'])
            ->exists()
        ) {
            return ApiResponse::SendResponse(400, 'You have already rated this provider.', []);
        }

        $rating = Rating::create($data);

        return ApiResponse::SendResponse(201, 'Rating created successfully.', new RatingResource($rating));
    }

    public function topProviders()
    {
        $topProviders = User::withAvg('receivedRatings', 'rating')
            ->withCount('receivedRatings')
            ->orderByDesc('received_ratings_avg_rating')
            ->take(10)->get(['id', 'name', 'avatar']);
        return ApiResponse::SendResponse(
            200,
            'Top rated providers',
            TopProviderResource::collection($topProviders)
        );
    }

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
