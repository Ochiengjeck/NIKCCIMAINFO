<?php

namespace Tests\Feature;

use App\Livewire\Membership\ApplicationDetail;
use App\Models\Chapter;
use App\Models\FinancialTransaction;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipCategory;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentConfirmationTest extends TestCase
{
    use RefreshDatabase;

    private Chapter $chapter;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        $this->chapter = Chapter::create(['name' => 'Nigeria', 'code' => 'NGA', 'country' => 'Nigeria']);
    }

    private function application(array $categoryAttrs = [], string $status = 'pending'): MembershipApplication
    {
        $category = MembershipCategory::create(array_merge([
            'name' => 'Gold Member', 'slug' => 'gold-'.uniqid(), 'is_active' => true, 'sort_order' => 1,
            'fee_usd' => 250,
        ], $categoryAttrs));

        return MembershipApplication::create([
            'chapter_id' => $this->chapter->id,
            'category_id' => $category->id,
            'applicant_name' => 'Acme Ltd',
            'organization' => 'Acme Ltd',
            'email' => 'acme@example.com',
            'phone' => '+2348000000000',
            'status' => $status,
            'submitted_at' => now(),
        ]);
    }

    private function admin(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create(['chapter_id' => $this->chapter->id]);
        $user->assignRole('super-admin');

        return $user;
    }

    public function test_final_approval_generates_a_sent_invoice(): void
    {
        $app = $this->application();
        $approver = User::factory()->create();

        $app->approveAtStage('membership-officer', $approver);
        $app->refresh();
        $app->approveAtStage('chairman', $approver);
        $app->refresh();
        $app->approveAtStage('director-general', $approver);

        $invoice = Invoice::where('application_id', $app->id)->first();
        $this->assertNotNull($invoice);
        $this->assertSame('sent', $invoice->status);
        $this->assertSame('USD', $invoice->currency);
        $this->assertSame('250.00', (string) $invoice->total);
    }

    public function test_on_request_application_generates_no_invoice(): void
    {
        $app = $this->application(['price_on_request' => true]);
        $approver = User::factory()->create();

        $app->approveAtStage('membership-officer', $approver);
        $app->refresh();
        $app->approveAtStage('chairman', $approver);
        $app->refresh();
        $app->approveAtStage('director-general', $approver);

        $this->assertSame(0, Invoice::where('application_id', $app->id)->count());
    }

    public function test_confirming_payment_records_transaction_and_activates(): void
    {
        $admin = $this->admin();
        $app = $this->application([], 'payment-pending');

        Livewire::actingAs($admin)->test(ApplicationDetail::class, ['application' => $app])
            ->assertSet('paymentAmount', '250')
            ->set('paymentMethod', 'bank-transfer')
            ->set('transactionRef', 'TRX-12345')
            ->call('activateMembership');

        $txn = FinancialTransaction::where('reference', 'TRX-12345')->first();
        $this->assertNotNull($txn);
        $this->assertSame('paid', $txn->status);
        $this->assertSame('bank-transfer', $txn->payment_method);
        $this->assertSame('250.00', (string) $txn->amount);
        $this->assertSame('USD', $txn->currency);

        $this->assertSame('active', $app->fresh()->status);
        $this->assertSame(1, Member::where('email', 'acme@example.com')->count());

        $invoice = Invoice::where('application_id', $app->id)->first();
        $this->assertSame('paid', $invoice->status);
        $this->assertNotNull($invoice->member_id);
    }

    public function test_blank_reference_is_auto_generated(): void
    {
        $admin = $this->admin();
        $app = $this->application([], 'payment-pending');

        Livewire::actingAs($admin)->test(ApplicationDetail::class, ['application' => $app])
            ->set('paymentMethod', 'cash')
            ->set('transactionRef', '')
            ->call('activateMembership');

        $txn = FinancialTransaction::where('member_id', Member::first()->id)->first();
        $this->assertNotNull($txn);
        $this->assertStringContainsString('CASH-APP'.$app->id, $txn->reference);
    }
}
