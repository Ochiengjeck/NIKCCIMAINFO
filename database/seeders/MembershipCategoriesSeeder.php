<?php

namespace Database\Seeders;

use App\Models\MembershipCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MembershipCategoriesSeeder extends Seeder
{
    /**
     * Seed the canonical member type × tier combinations. Idempotent — safe to
     * re-run; fees default to 0 (Free) and are meant to be set in the admin panel.
     */
    public function run(): void
    {
        $types = ['corporate', 'individual'];
        $tiers = ['Diamond', 'Gold', 'Bronze'];

        $sort = 0;
        foreach ($types as $type) {
            foreach ($tiers as $tier) {
                MembershipCategory::updateOrCreate(
                    ['slug' => Str::slug($type.'-'.$tier)],
                    [
                        'name' => $tier,
                        'member_type' => $type,
                        'description' => null,
                        'fee_ngn' => 0,
                        'fee_kes' => 0,
                        'is_active' => true,
                        'sort_order' => $sort++,
                    ]
                );
            }
        }
    }
}
