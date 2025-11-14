<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\ServiceAdController;
use App\Http\Controllers\Api\ServiceAdMediaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\RatingController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login']);


// Route::get('/send-mail', [EmailController::class, 'send']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/profile', [AuthController::class, 'profile']);
//     Route::post('/logout', [AuthController::class, 'logout']);
// });

    // filter route //
    Route::get('ads/filter', [ServiceAdController::class, 'filter']);


Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('regions', RegionController::class);
    Route::apiResource('cities', CityController::class);
    Route::apiResource('ads', ServiceAdController::class);
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
