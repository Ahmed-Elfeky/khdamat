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
use Illuminate\Support\Facades\Storage;

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
        if (!Auth::check()) {
            return ApiResponse::SendResponse(401, 'You must be logged in to create a service ad.', []);
        }
        if (Auth::user()->role !== 'provider') {
            return ApiResponse::SendResponse(403, 'You are not authorized to create a service ad.', []);
        }
        $data = $request->validated();

        $data['user_id'] = Auth::id();
        //  Logic ل reward و exchange
        if ($data['type'] !== 'request') {
            $data['reward'] = null;
        }
        if ($data['type'] !== 'exchange') {
            $data['exchange'] = null;
        }

        $ad = ServiceAd::create($data);
        $this->handleMediaUpload($request, $ad->id);
        // إعادة الإعلان مع تحميل العلاقات
        $ad->load(['city', 'region', 'category', 'media']);

        return ApiResponse::SendResponse(
            201,
            'Service ad created successfully',
            new ServiceAdResource($ad)
        );
    }
    public function update(UpdateServiceAdRequest $request, $id)
    {

        if (!Auth::check()) {
            return ApiResponse::SendResponse(401, 'You must be logged in to update a service ad.', []);
        }

        $ad = ServiceAd::find($id);
        if (!$ad) {
            return ApiResponse::SendResponse(404, 'Service ad not found.', []);
        }

        if (Auth::id() !== $ad->user_id) {
            return ApiResponse::SendResponse(403, 'You are not authorized to update this service ad.', []);
        }

        $data = $request->validated();

        if (($data['type'] ?? $ad->type) !== 'request') {
            $data['reward'] = null;
        }
        if (($data['type'] ?? $ad->type) !== 'exchange') {
            $data['exchange'] = null;
        }

        $ad->update($data);

        if ($request->hasFile('files')) {
            foreach ($ad->media as $media) {
                Storage::disk('public')->delete($media->file_path);
                $media->delete();
            }
        }
        //  إضافة الجديدة (لو في)
        $this->handleMediaUpload($request, $ad->id);
        // تحميل العلاقات
        $ad->load(['city', 'region', 'category', 'media']);

        return ApiResponse::SendResponse(
            200,
            'Service ad updated successfully',
            new ServiceAdResource($ad)
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

        // فلترة حسب النوع (enum: service, show, exchange)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        // فلترة حسب المستخدم صاحب الإعلان
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        // ترتيب النتائج حسب الأحدث أولاً
        $ads = $query->latest()->get();

        return ApiResponse::SendResponse(200, 'Service ads retrieved successfully', $ads);
    }
    public function getAllServices()
    {
        $ads = ServiceAd::with(['user', 'category', 'city', 'region', 'media'])
            ->where('type', 'service')
            ->latest()
            ->get();
        if ($ads->isEmpty()) {
            return ApiResponse::SendResponse(404, 'No service ads found', []);
        }
        return ApiResponse::SendResponse(
            200,
            "All service ads retrieved successfully",
            ServiceAdResource::collection($ads)
        );
    }
    public function allExchange()
    {
        $ads = ServiceAd::with(['user', 'category', 'city', 'region', 'media'])->where('type', 'exchange')->latest()->get();
        if ($ads->isEmpty()) {
            return ApiResponse::SendResponse(404, 'No Exchange ads found', []);
        }
        return ApiResponse::SendResponse(
            200,
            "All Exchange ads retrieved successfully",
            ServiceAdResource::collection($ads)
        );
    }
    public function allRequest()
    {
        $ads = ServiceAd::with(['user', 'category', 'city', 'region', 'media'])->where('type', 'request')->latest()->get();
        if ($ads->isEmpty()) {
            return ApiResponse::SendResponse(404, 'No Requests ads found', []);
        }
        return ApiResponse::SendResponse(
            200,
            "All Requests ads retrieved successfully",
            ServiceAdResource::collection($ads)
        );
    }
}
