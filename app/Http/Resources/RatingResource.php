<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user?->name,
            'service_provider_name' => $this->serviceProvider?->name,
            'rating' => $this->rating,
            'comment' => $this->comment,
            // 'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
