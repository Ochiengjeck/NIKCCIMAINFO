<?php

namespace Tests\Feature;

use App\Livewire\Public\MembershipApply;
use App\Models\Chapter;
use App\Models\MembershipApplication;
use App\Models\MembershipCategory;
use Database\Seeders\MembershipCategoriesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class MembershipTiersTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_six_typed_free_tiers_idempotently(): void
    {
        (new MembershipCategoriesSeeder)->run();
        (new MembershipCategoriesSeeder)->run(); // re-run must not duplicate

        $this->assertSame(6, MembershipCategory::count());
        $this->assertSame(3, MembershipCategory::ofType('corporate')->count());
        $this->assertSame(3, MembershipCategory::ofType('individual')->count());

        $tier = MembershipCategory::ofType('corporate')->first();
        $this->assertSame(0.0, (float) $tier->fee_ngn); // Free
        $this->assertSame('Corporate — '.$tier->name, $tier->displayName());
    }

    public function test_apply_flow_creates_application_with_typed_tier(): void
    {
        Notification::fake();

        Chapter::create(['name' => 'Nigeria Chapter', 'code' => 'NGA', 'country' => 'Nigeria']);
        (new MembershipCategoriesSeeder)->run();
        $corporateTier = MembershipCategory::ofType('corporate')->first();

        Livewire::test(MembershipApply::class)
            ->set('applicant_name', 'Acme Ltd')
            ->set('email', 'acme@example.com')
            ->set('phone', '+2348000000000')
            ->set('organization', 'Acme Ltd')
            ->set('chapter', 'nigeria')
            ->set('member_type', 'corporate')
            ->set('category_id', $corporateTier->id)
            ->set('purpose_of_membership', str_repeat('We want to expand corridor trade. ', 3))
            ->set('business_type', 'Manufacturing')
            ->set('years_in_operation', '5')
            ->set('annual_turnover', 'USD 1M')
            ->set('declaration_accepted', true)
            ->set('step', 4)
            ->call('submit')
            ->assertSet('submitted', true);

        $application = MembershipApplication::firstOrFail();
        $this->assertSame($corporateTier->id, $application->category_id);
        $this->assertSame('corporate', $application->category->member_type);
    }

    public function test_changing_member_type_clears_selected_tier(): void
    {
        (new MembershipCategoriesSeeder)->run();
        $corporateTier = MembershipCategory::ofType('corporate')->first();

        Livewire::test(MembershipApply::class)
            ->set('member_type', 'corporate')
            ->set('category_id', $corporateTier->id)
            ->set('member_type', 'individual')
            ->assertSet('category_id', null);
    }
}
