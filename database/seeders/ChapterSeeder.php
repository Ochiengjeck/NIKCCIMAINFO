<?php

namespace Database\Seeders;

use App\Models\Chapter;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    public function run(): void
    {
        $chapters = [
            ['name' => 'Nigeria', 'code' => 'NGA', 'is_active' => true],
            ['name' => 'Kenya',   'code' => 'KEN', 'is_active' => true],
            ['name' => 'Global',  'code' => 'GLOBAL', 'is_active' => true],
        ];

        foreach ($chapters as $chapter) {
            Chapter::firstOrCreate(['code' => $chapter['code']], $chapter);
        }

        $this->command->info('Chapters seeded successfully.');
    }
}
