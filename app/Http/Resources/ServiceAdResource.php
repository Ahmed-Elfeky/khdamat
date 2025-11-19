<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceAdResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'price'         => $this->price,
            // logic for reward
            'reward'        => $this->when($this->type === 'request', $this->reward),
            //  logic for exchange يظهر فقط لو type = exchange
            'exchange'      => $this->when($this->type === 'exchange', $this->exchange),

            'type'          => $this->type,
            // 'city'          => new CityResource($this->whenLoaded('city')),
            // 'region'        => new RegionResource($this->whenLoaded('region')),
            'media'         => ServiceAdMediaResourc::collection($this->whenLoaded('media')),
            'is_active'     => $this->is_active,
            'created_at'    => $this->created_at->format('Y-m-d'),
            'category'      => new CategoryResource($this->category),
        ];
    }
}
