<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // fav id //
            'id'           => $this->id,
            //الاعلان
            'service_ad'   => [
                'id'       => $this->serviceAd->id,
                'title'    => $this->serviceAd->title,
                'price'    => $this->serviceAd->price,
                // صاحب الاعلان
                'user'     => [
                    'id'   => $this->serviceAd->user->id,
                    'name' => $this->serviceAd->user->name,
                ],
            ],
        ];
    }
}
