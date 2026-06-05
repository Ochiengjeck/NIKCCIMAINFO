<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\MembershipApplication;
use App\Models\MembershipCategory;
use App\Models\User;
use App\Notifications\ApplicationApproved;
use App\Notifications\ApplicationReceived;
use App\Notifications\ApplicationStageAdvanced;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ApplicationEmailsTest extends TestCase
{
    use RefreshDatabase;

    private function makeApplication(array $categoryAttrs = [], string $status = 'pending'): MembershipApplication
    {
        $chapter = Chapter::firstOrCreate(['code' => 'NGA'], ['name' => 'Nigeria Chapter', 'country' => 'Nigeria']);
        $category = MembershipCategory::create(array_merge([
            'name' => 'Gold Member', 'slug' => 'gold', 'is_active' => true, 'sort_order' => 1,
            'fee_usd' => 250, 'fee_ngn' => 300000,
        ], $categoryAttrs));

        return MembershipApplication::create([
            'chapter_id' => $chapter->id,
            'category_id' => $category->id,
            'applicant_name' => 'Acme Ltd',
            'organization' => 'Acme Ltd',
            'email' => 'acme@example.com',
            'phone' => '+2348000000000',
            'address' => '12 Trade Road',
            'country' => 'Nigeria',
            'status' => $status,
            'submitted_at' => now(),
        ]);
    }

    public function test_charge_helpers(): void
    {
        $priced = $this->makeApplication();
        $this->assertTrue($priced->hasPayableAmount());
        $this->assertStringContainsString('$250.00', $priced->chargeLabel());

        $onRequest = $this->makeApplication(['slug' => 'plat', 'price_on_request' => true]);
        $this->assertFalse($onRequest->hasPayableAmount());
        $this->assertSame('On request', $onRequest->chargeLabel());
    }

    public function test_first_email_includes_summary_and_fee(): void
    {
        $app = $this->makeApplication();
        $mail = (new ApplicationReceived($app))->toMail(new AnonymousNotifiable);

        $body = collect($mail->introLines)->merge($mail->outroLines)->implode(' ');
        $this->assertStringContainsString('Gold Member', $body);
        $this->assertStringContainsString('$250.00', $body);
    }

    public function test_every_approval_step_emails_the_applicant(): void
    {
        Notification::fake();

        $app = $this->makeApplication();
        $approver = User::factory()->create();

        $app->approveAtStage('membership-officer', $approver);
        $app->refresh();
        $app->approveAtStage('chairman', $approver);
        $app->refresh();
        $app->approveAtStage('director-general', $approver);

        Notification::assertSentOnDemand(ApplicationStageAdvanced::class);
        Notification::assertSentOnDemand(ApplicationApproved::class);
    }

    public function test_approved_email_attaches_invoice_only_when_payable(): void
    {
        $payable = $this->makeApplication([], 'payment-pending');
        $payableMail = (new ApplicationApproved($payable))->toMail(new AnonymousNotifiable);
        $this->assertNotEmpty($payableMail->rawAttachments);

        $onRequest = $this->makeApplication(['slug' => 'plat', 'price_on_request' => true], 'payment-pending');
        $onRequestMail = (new ApplicationApproved($onRequest))->toMail(new AnonymousNotifiable);
        $this->assertEmpty($onRequestMail->rawAttachments);
    }
}
