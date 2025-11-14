<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\ServiceAd;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Http\Resources\FavoriteResource;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with('serviceAd')->where('user_id', Auth::id())->get();

        return ApiResponse::SendResponse(200, 'Favorites retrieved successfully', FavoriteResource::collection( $favorites));
    }

    public function store($serviceAdId)
    {
        $serviceAd = ServiceAd::find($serviceAdId);
        if (!$serviceAd) {
            return ApiResponse::SendResponse(404, 'Service ad not found', []);
        }

        $favorite = Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'service_ad_id' => $serviceAdId,
        ]);

        return ApiResponse::SendResponse(201, 'Added to favorites', $favorite);
    }

    public function destroy($serviceAdId)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('service_ad_id', $serviceAdId)
            ->first();

        if (!$favorite) {
            return ApiResponse::SendResponse(404, 'This ad is not in favorites', []);
        }

        $favorite->delete();
        return ApiResponse::SendResponse(200, 'Removed from favorites', []);
    }
}
