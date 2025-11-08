<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'specialization',
        'whatsapp',
        'otp_code',
        'otp_expires_at',
        'is_verified',
        'email_verified_at',
    ];

    /**
     * الخصائص اللي لازم تتخفي عند الإرجاع (مثل API response)
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    /**
     * تحويل الأنواع تلقائيًا
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];




    public function ratings()
    {
        return $this->hasMany(Rating::class, 'service_provider_id');
    }


    /**
     * علاقة المستخدم بالتقييمات اللي أنشأها (هو اللي كتبها)
     */
    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    /**
     * علاقة المستخدم بالتقييمات اللي استلمها (كمزوّد خدمة)
     */
    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'service_provider_id');
    }

    /**
     * دالة بسيطة لمعرفة نوع المستخدم
     */
    public function isProvider()
    {
        return $this->role === 'provider';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
