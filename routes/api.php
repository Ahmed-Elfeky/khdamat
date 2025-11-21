<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\ServiceAdController;
use App\Http\Controllers\Api\ServiceAdMediaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\RatingController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login']);



// filter route //
Route::get('ads/filter', [ServiceAdController::class, 'filter']);
Route::get('categories/{id}/users', [CategoryController::class, 'getUsersByCategory']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('categories/{id}/providers', [CategoryController::class, 'providers']);
    Route::get('ads/all-exchange', [ServiceAdController::class, 'allExchange']);
    Route::get('ads/services', [ServiceAdController::class, 'getAllServices']);
    Route::get('ads/all-request', [ServiceAdController::class, 'allRequest']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('regions', RegionController::class);
    Route::apiResource('cities', CityController::class);
    Route::apiResource('ads', ServiceAdController::class);

    Route::post('update-ad/{id}' , [ServiceAdController::class , 'update']);
    
    Route::post('service-ads/{id}/media', [ServiceAdMediaController::class, 'store']);
    Route::delete('service-ads/media/{id}', [ServiceAdMediaController::class, 'destroy']);

    // rate route //
    Route::post('ratings', [RatingController::class, 'store']);
    // favorites route  //
    Route::get('favorites', [FavoriteController::class, 'index']);
    Route::post('favorites/{serviceAdId}', [FavoriteController::class, 'store']);
    Route::delete('favorites/{serviceAdId}', [FavoriteController::class, 'destroy']);
});

Route::get('/ratings/top-Rated', [RatingController::class, 'topProviders']);
Route::get('/ratings/{serviceProviderId}', [RatingController::class, 'index']);
