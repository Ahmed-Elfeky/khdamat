<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAdMedia extends Model
{
    protected $fillable = ['service_ad_id', 'file_path', 'type'];

    public function serviceAd()
    {
        return $this->belongsTo(ServiceAd::class);
    }

    public function getFullUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
