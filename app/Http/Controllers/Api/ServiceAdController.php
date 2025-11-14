<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceAdRequest;
use App\Http\Resources\ServiceAdResource;
use App\Models\ServiceAd;
use App\Helpers\ApiResponse;
use App\Http\Requests\UpdateServiceAdRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\HandlesMediaUploads;
use Illuminate\Http\Request;

class ServiceAdController extends Controller
{
    use HandlesMediaUploads;

    public function index()
    {
        $ads = ServiceAd::with(['category', 'serviceType', 'city', 'region'])->get();
        if ($ads->isEmpty()) {
            return ApiResponse::SendResponse(404, 'No service ads found', []);
        }
        return ApiResponse::SendResponse(200, 'All service ads retrieved successfully', ServiceAdResource::collection($ads));
    }

    public function store(StoreServiceAdRequest $request)
    {
        $data = $request->validated();

        if (!Auth::check()) {
            return ApiResponse::SendResponse(401, 'You must be logged in to create a service ad.', []);
        }

        if (Auth::user()->role !== 'provider') {
            return ApiResponse::SendResponse(403, 'You are not authorized to create a service ad.', []);
        }

        $data['user_id'] = Auth::id();

        $ad = ServiceAd::create($data);
        // استدعاء التريت لرفع الميديا
        $this->handleMediaUpload($request, $ad->id);

        return ApiResponse::SendResponse(
            201,
            'Service ad created successfully',
            new ServiceAdResource($ad->load(['city', 'region', 'category', 'media']))
        );
    }

    public function update(UpdateServiceAdRequest $request, $id)
    {
        $ad = ServiceAd::find($id);
        if (!$ad) {
            return ApiResponse::SendResponse(404, 'Service ad not found.', []);
        }
        if (Auth::id() !== $ad->user_id && Auth::user()->role !== 'admin') {
            return ApiResponse::SendResponse(403, 'You are not authorized to update this service ad.', []);
        }

        $data = $request->validated();
        dd($data);
        if (!empty($data)) {
            $ad->update($data);
        }
        if ($request->hasFile('media')) {
            foreach ($ad->media as $media) {
                $filePath = storage_path('app/public/' . $media->file_path);
                if (file_exists($filePath)) unlink($filePath);
                $media->delete();
            }
            $this->handleMediaUpload($request, $ad->id);
        }
        return ApiResponse::SendResponse(
            200,
            'Service ad updated successfully',
            new ServiceAdResource($ad->load(['city', 'region', 'category', 'media']))
        );
    }

    public function destroy($id)
    {
        $ad = ServiceAd::find($id);
        if (!$ad) {
            return ApiResponse::SendResponse(404, 'Service ad not found', []);
        }
        $ad->delete();
        return ApiResponse::SendResponse(200, 'Service ad deleted successfully', []);
    }


    public function filter(Request $request)
    {
        // بداية الاستعلام
        $query = ServiceAd::with(['user', 'category', 'city']);

        // فلترة حسب التصنيف إذا موجود
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // فلترة حسب المدينة إذا موجود
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // فلترة حسب النوع (enum: service, show, exchange) إذا موجود
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // ترتيب النتائج حسب الأحدث أولاً
        $ads = $query->latest()->get();

        // إرجاع النتائج بشكل موحد
        return ApiResponse::SendResponse(200, 'Service ads retrieved successfully', $ads);
    }
}
