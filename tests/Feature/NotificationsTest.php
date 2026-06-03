<?php

namespace Tests\Feature;

use App\Livewire\Public\ContactForm;
use App\Livewire\Public\MembershipApply;
use App\Models\Chapter;
use App\Models\FinancialTransaction;
use App\Models\Member;
use App\Models\MembershipCategory;
use App\Notifications\ApplicationReceived;
use App\Notifications\ApplicationSubmittedAdmin;
use App\Notifications\ContactInquiryAcknowledged;
use App\Notifications\ContactInquiryReceived;
use App\Notifications\PaymentReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_emails_admin_and_acknowledges_sender(): void
    {
        Notification::fake();

        Livewire::test(ContactForm::class)
            ->set('name', 'Jane Doe')
            ->set('email', 'jane@example.com')
            ->set('subject', 'Partnership inquiry')
            ->set('message', 'I would like to discuss a partnership opportunity with the chamber.')
            ->set('chapter', 'general')
            ->call('submit')
            ->assertSet('submitted', true);

        Notification::assertSentOnDemand(ContactInquiryReceived::class);
        Notification::assertSentOnDemand(ContactInquiryAcknowledged::class);
    }

    public function test_public_membership_application_notifies_applicant_and_admin(): void
    {
        Notification::fake();

        Chapter::create(['name' => 'Nigeria Chapter', 'code' => 'NGA', 'country' => 'Nigeria']);
        $category = MembershipCategory::create([
            'name' => 'Standard', 'slug' => 'standard', 'is_active' => true, 'sort_order' => 1,
        ]);

        Livewire::test(MembershipApply::class)
            ->set('applicant_name', 'Acme Ltd')
            ->set('email', 'acme@example.com')
            ->set('phone', '+2348000000000')
            ->set('organization', 'Acme Ltd')
            ->set('chapter', 'nigeria')
            ->set('category_id', $category->id)
            ->set('purpose_of_membership', str_repeat('We want to expand trade across the corridor. ', 3))
            ->set('business_type', 'Manufacturing')
            ->set('years_in_operation', '5')
            ->set('annual_turnover', 'USD 1M')
            ->set('declaration_accepted', true)
            ->set('step', 4)
            ->call('submit')
            ->assertSet('submitted', true);

        Notification::assertSentOnDemand(ApplicationReceived::class);
        Notification::assertSentOnDemand(ApplicationSubmittedAdmin::class);
    }

    public function test_successful_flutterwave_webhook_marks_paid_and_emails_receipt(): void
    {
        Notification::fake();

        config(['services.flutterwave.secret_hash' => '']);

        $chapter = Chapter::create(['name' => 'Nigeria Chapter', 'code' => 'NGA', 'country' => 'Nigeria']);
        $category = MembershipCategory::create([
            'name' => 'Standard', 'slug' => 'standard', 'is_active' => true, 'sort_order' => 1,
        ]);
        $member = Member::create([
            'chapter_id' => $chapter->id,
            'category_id' => $category->id,
            'membership_number' => 'M-0001',
            'status' => 'active',
            'first_name' => 'Pay',
            'last_name' => 'Er',
            'email' => 'payer@example.com',
            'joined_at' => now(),
        ]);
        $tx = FinancialTransaction::create([
            'chapter_id' => $chapter->id,
            'member_id' => $member->id,
            'type' => 'membership-fee',
            'amount' => 50000,
            'currency' => 'NGN',
            'reference' => 'TX-REF-123',
            'status' => 'pending',
        ]);

        $this->postJson('/webhook/flutterwave', [
            'status' => 'successful',
            'data' => ['tx_ref' => 'TX-REF-123', 'id' => 99887766],
        ])->assertOk();

        $this->assertSame('paid', $tx->fresh()->status);
        Notification::assertSentOnDemand(PaymentReceived::class);
    }
}
