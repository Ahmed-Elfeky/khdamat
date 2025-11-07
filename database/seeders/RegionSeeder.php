<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\City;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            // Cairo
            ['name' => 'Nasr City', 'city_id' => City::where('name', 'Cairo')->first()->id],
            ['name' => 'Heliopolis', 'city_id' => City::where('name', 'Cairo')->first()->id],
            ['name' => 'Maadi', 'city_id' => City::where('name', 'Cairo')->first()->id],

            // Giza
            ['name' => 'Dokki', 'city_id' => City::where('name', 'Giza')->first()->id],
            ['name' => 'Mohandessin', 'city_id' => City::where('name', 'Giza')->first()->id],

            // Alexandria
            ['name' => 'Sidi Gaber', 'city_id' => City::where('name', 'Alexandria')->first()->id],
            ['name' => 'Smouha', 'city_id' => City::where('name', 'Alexandria')->first()->id],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}
