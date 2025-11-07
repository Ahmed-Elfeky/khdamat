<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'Cairo'],
            ['name' => 'Giza'],
            ['name' => 'Alexandria'],
            ['name' => 'Mansoura'],
            ['name' => 'Aswan'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
