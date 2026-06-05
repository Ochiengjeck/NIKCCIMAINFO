<?php

namespace Database\Seeders;

use App\Models\MembershipCategory;
use Illuminate\Database\Seeder;

class MembershipCategoriesSeeder extends Seeder
{
    /**
     * Seed the canonical membership categories from the 2026 revised registration
     * form (Section B). Idempotent — safe to re-run. Prices are USD-primary; the
     * NGN figures from the form are seeded for the four ranked tiers as a secondary
     * reference, while USD is left for an admin to set in the panel.
     */
    public function run(): void
    {
        // Remove the previous session's auto-seeded corporate/individual combos so
        // the catalog matches the official form (only touches those exact slugs).
        MembershipCategory::whereIn('slug', [
            'corporate-diamond', 'corporate-gold', 'corporate-bronze',
            'individual-diamond', 'individual-gold', 'individual-bronze',
        ])->delete();

        $categories = [
            ['name' => 'Platinum Member', 'slug' => 'platinum', 'fee_ngn' => 2500000],
            ['name' => 'Gold Member', 'slug' => 'gold', 'fee_ngn' => 1500000],
            ['name' => 'Silver Member', 'slug' => 'silver', 'fee_ngn' => 300000],
            ['name' => 'Bronze Member', 'slug' => 'bronze', 'fee_ngn' => 450000],
            ['name' => 'Government / Public Institution', 'slug' => 'government-public-institution', 'fee_ngn' => null],
            ['name' => 'Diplomatic / International Partner', 'slug' => 'diplomatic-international-partner', 'fee_ngn' => null],
            ['name' => 'Youth / Startup Member', 'slug' => 'youth-startup', 'fee_ngn' => null],
            ['name' => 'Honorary / Special Category', 'slug' => 'honorary-special', 'fee_ngn' => null],
        ];

        $sort = 0;
        foreach ($categories as $category) {
            MembershipCategory::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => null,
                    'fee_usd' => null,
                    'fee_ngn' => $category['fee_ngn'],
                    'corporate_enabled' => false,
                    'individual_enabled' => false,
                    'is_active' => true,
                    'sort_order' => $sort++,
                ]
            );
        }
    }
}
