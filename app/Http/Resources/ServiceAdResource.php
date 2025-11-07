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
            'price_type'    => $this->price_type,
            'category'      => new CategoryResource($this->whenLoaded('category')),
            'service_type'  => new ServiceTypeResource($this->whenLoaded('serviceType')),
            'city'          => new CityResource($this->whenLoaded('city')),
            'region'        => new RegionResource($this->whenLoaded('region')),
            'is_active'     => $this->is_active,
            'created_at'    => $this->created_at->format('Y-m-d'),
        ];
    }
}
