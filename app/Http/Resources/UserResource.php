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
            'specialization' => $this->specialization,
            'whatsapp' => $this->whatsapp,
            'avatar' => $this->avatar ? asset($this->avatar) : null,
            'ratings' => RatingResource::collection($this->whenLoaded('ratings')),
            'is_verified' => $this->is_verified ,
        ];
    }
}
