<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Resources\RatingResource;
use App\Http\Resources\AdResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($request->password);

        // رفع الصورة لو موجودة
        if ($request->hasFile('avatar')) {
            $extension = $request->avatar->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $request->avatar->move(public_path('uploads/users'), $filename);
            $data['avatar'] = 'uploads/users/' . $filename;
        }
        $user = User::create($data);

        // توليد كود التفعيل
        $otp = rand(1000, 9999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);


        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::SendResponse(201, 'User registered successfully. Please verify your OTP.', [
            new UserResource($user),
            'otp_code' => $user->otp_code,
            'is_verified' => false,
        ]);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return ApiResponse::SendResponse(404, 'User not found', []);
        }

        if (!$user->isOtpValid($request->otp_code)) {
            return ApiResponse::SendResponse(400, 'Invalid or expired OTP', []);
        }

        $user->update([
            'is_verified' => true,
            'otp_code' => null,
            'otp_expires_at' => null,
            'email_verified_at' => now(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::SendResponse(
            200,
            'Email verified successfully',
            [
                'user' => new UserResource($user),
                'token' => $token,

            ]
        );
    }


    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || ! Hash::check($request->password, $user->password)) {
            return ApiResponse::SendResponse(401, 'Invalid credentials', []);
        }

        if (!$user->is_verified) {
            return ApiResponse::SendResponse(403, 'Please verify your email first.', []);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return ApiResponse::SendResponse(200, 'Login successfully', [
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }


    public function show($id)
    {
        $user = User::with([
            'ads' => function ($q) {
                $q->latest();
            },
            'ratings.user',
        ])->find($id);

        if (!$user) {
            return ApiResponse::SendResponse(404, 'User not found', []);
        }

        $averageRating = $user->ratings()->avg('rating') ?? 0;

        // توزيع عدد كل نجمة
        $ratingDistribution = $user->ratings()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        return ApiResponse::SendResponse(200, 'Provider profile', [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar ? asset($user->avatar) : null,
            'phone' => $user->phone,
            'whatsapp' => $user->whatsapp,
            //  تقييمات
            'rating' => [
                'average' => round($averageRating, 1),
                'count' => $user->ratings()->count(),
                'distribution' => $ratingDistribution, // مثال: [5=>10, 4=>2 ...]
            ],
            //  آراء العملاء
            'reviews' => RatingResource::collection($user->ratings()->latest()->get()),
            //  الخدمات (قسم الخدمات)
            'ads' => AdResource::collection(
                $user->ads->where('type', 'service')
            ),
            //  الإعلانات (قسم السيارات مثلاً)
            'shows' => AdResource::collection(
                $user->ads->where('type', 'show')
            ),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::SendResponse(200, 'Logged out successfully', []);
    }
}
