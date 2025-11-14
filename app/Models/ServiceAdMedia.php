<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class ServiceAdMedia extends Model
{
    protected $fillable = [
        'service_ad_id',
        'file_path',
        'type',
    ];

    public function serviceAd()
    {
        return $this->belongsTo(ServiceAd::class);
    }

    // اختيارياً: URL كامل للملف
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

}
