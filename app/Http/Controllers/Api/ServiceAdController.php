<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceAdRequest;
use App\Http\Resources\ServiceAdResource;
use App\Models\ServiceAd;
use App\Helpers\ApiResponse;

class ServiceAdController extends Controller
{
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
        $ad = ServiceAd::create($data);
        return ApiResponse::SendResponse(201, 'Service ad created successfully', new ServiceAdResource($ad->load(['city', 'region', 'category'])));
    }

    public function show($id)
    {
        $ad = ServiceAd::with(['category', 'serviceType', 'city', 'region'])->find($id);
        if (!$ad) {
            return ApiResponse::SendResponse(404, 'Service ad not found', []);
        }
        return ApiResponse::SendResponse(200, 'Service ad retrieved successfully', new ServiceAdResource($ad));
    }

    public function update(StoreServiceAdRequest $request, $id)
    {
        $ad = ServiceAd::find($id);
        if (!$ad) {
            return ApiResponse::SendResponse(404, 'Service ad not found', []);
        }

        $ad->update($request->validated());
        return ApiResponse::SendResponse(200, 'Service ad updated successfully', new ServiceAdResource($ad));
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
}
