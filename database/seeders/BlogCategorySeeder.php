<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'News', 'description' => 'General chamber news and updates.'],
            ['name' => 'Press Release', 'description' => 'Official statements and press releases.'],
            ['name' => 'Announcement', 'description' => 'Important notices and announcements.'],
            ['name' => 'Insights', 'description' => 'Analysis and thought leadership on AfCFTA trade.'],
            ['name' => 'Trade & Investment', 'description' => 'Opportunities, corridors and investment news.'],
        ];

        foreach ($categories as $index => $category) {
            BlogCategory::updateOrCreate(
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
