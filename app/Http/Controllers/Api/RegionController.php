<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Resources\RegionResource;
use App\Models\Region;
use App\Helpers\ApiResponse;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::with('city')->get();

        if ($regions->isEmpty()) {
            return ApiResponse::SendResponse(404, 'No regions found', []);
        }

        return ApiResponse::SendResponse(200, 'All regions retrieved successfully', RegionResource::collection($regions));
    }

    public function store(StoreRegionRequest $request)
    {
        $region = Region::create($request->validated());

        return ApiResponse::SendResponse(201, 'Region created successfully', new RegionResource($region));
    }

    public function show($id)
    {
        $region = Region::with('city')->find($id);

        if (!$region) {
            return ApiResponse::SendResponse(404, 'Region not found', []);
        }

        return ApiResponse::SendResponse(200, 'Region retrieved successfully', new RegionResource($region));
    }

    public function destroy($id)
    {
        $region = Region::find($id);

        if (!$region) {
            return ApiResponse::SendResponse(404, 'Region not found', []);
        }

        $region->delete();

        return ApiResponse::SendResponse(200, 'Region deleted successfully');
    }
}
