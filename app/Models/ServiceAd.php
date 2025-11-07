<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAd extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'price_type',
        'category_id',
        'service_type_id',
        'city_id',
        'region_id',
        'user_id',
        'is_active'
    ];


    public function media()
    {
        return $this->hasMany(ServiceAdMedia::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
