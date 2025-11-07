<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\ServiceAdController;
use App\Http\Controllers\Api\ServiceAdMediaController;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('regions', RegionController::class);
Route::apiResource('cities', CityController::class);
Route::apiResource('service-ads', ServiceAdController::class);

Route::post('service-ads/{id}/media', [ServiceAdMediaController::class, 'store']);
Route::delete('service-ads/media/{id}', [ServiceAdMediaController::class, 'destroy']);
