<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
     protected $fillable = [
        'title', 'description', 'price', 'price_type',
        'category_id', 'service_type_id', 'city_id', 'region_id',
        'user_id', 'is_active'
    ];


public function user(){
    return $this->belongsTo(User::class);
}
   public function category()
    {
        return $this->belongsTo(Category::class);
    }
public function cities(){
    return $this->hasMany(City::class);
}
public function regions(){
    return $this->hasMany(Region::class);
}
public function type(){
    return $this->belongsTo(ServiceType::class);
}


}
