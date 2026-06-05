<?php

namespace Tests\Feature;

use App\Livewire\Public\MembershipApply;
use App\Models\Chapter;
use App\Models\MembershipApplication;
use App\Models\MembershipCategory;
use App\Services\SettingsService;
use Database\Seeders\MembershipCategoriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class MembershipTiersTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_the_eight_form_categories_idempotently(): void
    {
        (new MembershipCategoriesSeeder)->run();
        (new MembershipCategoriesSeeder)->run(); // re-run must not duplicate

        $this->assertSame(8, MembershipCategory::count());

        foreach (['platinum', 'gold', 'silver', 'bronze', 'government-public-institution',
            'diplomatic-international-partner', 'youth-startup', 'honorary-special'] as $slug) {
            $this->assertDatabaseHas('membership_categories', ['slug' => $slug, 'is_active' => true]);
        }

        // A fee-less category reports Free; the Gold tier carries its NGN figure.
        $youth = MembershipCategory::where('slug', 'youth-startup')->first();
        $this->assertTrue($youth->isFree());
        $this->assertSame('Free', $youth->priceLabel());

        $gold = MembershipCategory::where('slug', 'gold')->first();
        $this->assertStringContainsString('₦1,500,000', $gold->priceLabel());
    }

    public function test_price_label_and_group_helpers(): void
    {
        $cat = MembershipCategory::create([
            'name' => 'Gold', 'slug' => 'gold', 'is_active' => true, 'sort_order' => 1,
            'fee_usd' => 250, 'fee_ngn' => 300000,
            'corporate_enabled' => true, 'corporate_fee_usd' => 500,
            'individual_enabled' => false,
        ]);

        $this->assertSame('$250.00 (₦300,000)', $cat->priceLabel());
        $this->assertTrue($cat->availableForGroup('corporate'));
        $this->assertFalse($cat->availableForGroup('individual'));
        $this->assertTrue($cat->availableForGroup(null));
        $this->assertStringContainsString('$500.00', $cat->priceLabel('corporate'));

        // Public surfaces show USD only.
        $this->assertSame('$250.00', $cat->priceLabelUsd());
        $this->assertSame('$500.00', $cat->priceLabelUsd('corporate'));

        // NGN-only (no USD set) ⇒ prompts admin rather than showing a Naira figure publicly.
        $ngnOnly = MembershipCategory::create([
            'name' => 'Bronze', 'slug' => 'bronze', 'is_active' => true, 'sort_order' => 2,
            'fee_ngn' => 450000,
        ]);
        $this->assertSame('On request', $ngnOnly->priceLabelUsd());

        $freebie = MembershipCategory::create([
            'name' => 'Youth', 'slug' => 'youth', 'is_active' => true, 'sort_order' => 3,
        ]);
        $this->assertSame('Free', $freebie->priceLabelUsd());

        // price_on_request forces "On request" even when a price is set.
        $cat->update(['price_on_request' => true]);
        $this->assertSame('On request', $cat->fresh()->priceLabelUsd());
        $this->assertSame('On request', $cat->fresh()->priceLabelUsd('corporate'));
    }

    public function test_flat_apply_flow_captures_all_form_fields(): void
    {
        Notification::fake();

        Chapter::create(['name' => 'Nigeria Chapter', 'code' => 'NGA', 'country' => 'Nigeria']);
        (new MembershipCategoriesSeeder)->run();
        $gold = MembershipCategory::where('slug', 'gold')->first();

        Livewire::test(MembershipApply::class)
            ->assertSet('grouped', false)
            ->set('applicant_name', 'Acme Ltd')
            ->set('contact_person', 'Jane Doe')
            ->set('email', 'acme@example.com')
            ->set('phone', '+2348000000000')
            ->set('organization', 'Acme Ltd')
            ->set('address', '12 Trade Road, Abuja')
            ->set('country', 'Nigeria')
            ->set('website', 'https://acme.example')
            ->set('sponsored_by', 'Existing Member')
            ->set('chapter', 'nigeria')
            ->set('category_id', $gold->id)
            ->set('primary_sector', 'Manufacturing')
            ->set('activity_summary', 'We manufacture goods for the corridor.')
            ->set('purpose_of_membership', str_repeat('We want to expand corridor trade. ', 3))
            ->set('declaration_accepted', true)
            ->set('step', 4)
            ->call('submit')
            ->assertSet('submitted', true);

        $application = MembershipApplication::firstOrFail();
        $this->assertSame($gold->id, $application->category_id);
        $this->assertSame('Jane Doe', $application->contact_person);
        $this->assertSame('12 Trade Road, Abuja', $application->address);
        $this->assertSame('Nigeria', $application->country);
        $this->assertSame('https://acme.example', $application->website);
        $this->assertSame('Existing Member', $application->sponsored_by);
        $this->assertSame('Manufacturing', $application->business_profile['primary_sector']);
        $this->assertSame('We manufacture goods for the corridor.', $application->business_profile['activity_summary']);
    }

    public function test_grouping_on_filters_categories_by_group(): void
    {
        app(SettingsService::class)->set('membership_group_by_type', '1', 'membership');

        $corp = MembershipCategory::create([
            'name' => 'Platinum', 'slug' => 'platinum', 'is_active' => true, 'sort_order' => 1,
            'corporate_enabled' => true, 'corporate_fee_usd' => 1000,
            'individual_enabled' => false,
        ]);
        MembershipCategory::create([
            'name' => 'Youth', 'slug' => 'youth', 'is_active' => true, 'sort_order' => 2,
            'corporate_enabled' => false,
            'individual_enabled' => true, 'individual_fee_usd' => 50,
        ]);

        // A valid corporate category at step 2 advances to step 3.
        Livewire::test(MembershipApply::class)
            ->assertSet('grouped', true)
            ->set('step', 2)
            ->set('member_type', 'corporate')
            ->set('category_id', $corp->id)
            ->call('nextStep')
            ->assertHasNoErrors()
            ->assertSet('step', 3);

        // The same corporate-only category is rejected for an Individual applicant.
        Livewire::test(MembershipApply::class)
            ->set('step', 2)
            ->set('member_type', 'individual')
            ->set('category_id', $corp->id)
            ->call('nextStep')
            ->assertHasErrors('category_id')
            ->assertSet('step', 2);
    }
}
