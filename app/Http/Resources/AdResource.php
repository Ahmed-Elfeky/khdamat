<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
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
            'city'          => new CityResource($this->whenLoaded('city')),
            'region'        => new RegionResource($this->whenLoaded('region')),
            'media'         =>  AdMediaResourc::collection($this->whenLoaded('media')),
            'status'        => $this->status,
            'created_at'    => $this->created_at->format('Y-m-d'),
            'category'      => new CategoryResource($this->category),
        ];
    }
}
