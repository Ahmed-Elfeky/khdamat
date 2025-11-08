<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['user_id', 'rating', 'service_provider_id','comment'];


    // المستخدم اللي أضاف التقييم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // مزود الخدمة اللي تم تقييمه
    public function serviceProvider()
    {
        return $this->belongsTo(User::class, 'service_provider_id');
    }

}
