<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
        'role' => $this->role,
        'phone' => $this->phone,
        'whatsapp' => $this->whatsapp,
        'avatar' => $this->avatar ? asset($this->avatar) : null,
        'ratings' => RatingResource::collection($this->whenLoaded('ratings')),
        // تقييم المتوسط
        'average_rating' => round($this->received_ratings_avg_rating, 1),
        'is_verified' => $this->is_verified,
        'ads'  => AdResource::collection($this->ads)
    ];
}

}
