<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\ServiceAdController;
use App\Http\Controllers\Api\ServiceAdMediaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\RatingController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login']);


Route::get('/send-mail', [EmailController::class, 'send']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});




Route::apiResource('categories', CategoryController::class);
Route::apiResource('regions', RegionController::class);
Route::apiResource('cities', CityController::class);
Route::apiResource('ads', ServiceAdController::class);
// Route::middleware('auth:sanctum')->group(function () {
    Route::post('/ratings', [RatingController::class, 'store']); // إضافة تقييم
// });

Route::get('/ratings/top-Rated', [RatingController::class, 'topRatedProvider']); // عرض تقييمات مزود خدمة
Route::get('/ratings/{serviceProviderId}', [RatingController::class, 'index']); // عرض تقييمات مزود خدمة


Route::post('service-ads/{id}/media', [ServiceAdMediaController::class, 'store']);
Route::delete('service-ads/media/{id}', [ServiceAdMediaController::class, 'destroy']);
