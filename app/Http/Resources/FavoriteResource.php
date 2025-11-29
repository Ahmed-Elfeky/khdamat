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
            'ads'   => [
                'id'       => $this->ads->id,
                'title'    => $this->ads->title,
                'price'    => $this->ads->price,
                // صاحب الاعلان
                'user'     => [
                    'id'   => $this->ads->user->id,
                    'name' => $this->ads->user->name,
                ],
            ],
        ];
    }
}
