<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['user_id', 'service_ad_id'];

    

    public function serviceAd()
    {
        return $this->belongsTo(ServiceAd::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
