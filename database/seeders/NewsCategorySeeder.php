<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'News', 'description' => 'General chamber news and updates.'],
            ['name' => 'Press Release', 'description' => 'Official statements and press releases.'],
            ['name' => 'Announcement', 'description' => 'Important notices and announcements.'],
            ['name' => 'Insights', 'description' => 'Analysis and commentary on AfCFTA trade.'],
        ];

        foreach ($categories as $index => $category) {
            NewsCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'sort_order' => $index,
                ],
            );
        }
    }
}
