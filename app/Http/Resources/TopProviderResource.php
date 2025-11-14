<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopProviderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
             'id'             => $this->id,
             'name'           => $this->name,
             'avatar'         => $this->avatar ? asset('uploads/users/' . $this->avatar) : null,
             'average_rating' => round($this->received_ratings_avg_rating, 1),
             'ratings_count'  => $this->received_ratings_count,
        ];
    }
}
