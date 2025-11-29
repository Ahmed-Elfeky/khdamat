<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class AdMedia extends Model
{
    protected $fillable = [
        'ad_id',
        'file_path',
        'type',
    ];

    public function ads()
    {
        return $this->belongsTo(Ad::class);
    }

    // اختيارياً: URL كامل للملف
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

}
