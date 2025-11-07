<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'صيانة كهربية'],
            ['name' => 'صيانة سيارات'],
            ['name' => ' خدمة نظافة'],
            ['name' => 'اعمال منزلية'],

        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
