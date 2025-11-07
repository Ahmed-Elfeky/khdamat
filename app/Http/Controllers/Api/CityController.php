<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Helpers\ApiResponse;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('regions')->get();

        if ($cities->isEmpty()) {
            return ApiResponse::SendResponse(404, 'No cities found', []);
        }

        return ApiResponse::SendResponse(200, 'All cities retrieved successfully', CityResource::collection($cities));
    }

    public function store(StoreCityRequest $request)
    {
        $city = City::create($request->validated());

        return ApiResponse::SendResponse(201, 'City created successfully', new CityResource($city));
    }

    public function show($id)
    {
        $city = City::with('regions')->find($id);

        if (!$city) {
            return ApiResponse::SendResponse(404, 'City not found', []);
        }

        return ApiResponse::SendResponse(200, 'City retrieved successfully', new CityResource($city));
    }

    public function destroy($id)
    {
        $city = City::find($id);

        if (!$city) {
            return ApiResponse::SendResponse(404, 'City not found', []);
        }

        $city->delete();

        return ApiResponse::SendResponse(200, 'City deleted successfully');
    }
}
