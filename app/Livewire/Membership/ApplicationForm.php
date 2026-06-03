<?php

namespace App\Livewire\Membership;

use App\Models\MembershipApplication;
use App\Models\MembershipCategory;
use App\Notifications\ApplicationReceived;
use App\Notifications\ApplicationSubmittedAdmin;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ApplicationForm extends Component
{
    public int $step = 1;

    public int $totalSteps = 4;

    // Step 1: Personal / Org info
    public string $applicant_name = '';

    public string $email = '';

    public string $phone = '';

    public string $organization = '';

    // Step 2: Type, Category & Purpose
    public string $member_type = '';

    public ?int $category_id = null;

    public string $purpose_of_membership = '';

    // Step 3: Business Profile
    public string $business_type = '';

    public string $years_in_operation = '';

    public string $annual_turnover = '';

    public string $export_markets = '';

    // Step 4: Declaration
    public bool $declaration_accepted = false;

    protected function rules(): array
    {
        return match ($this->step) {
            1 => [
                'applicant_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:30',
                'organization' => 'required|string|max:255',
            ],
            2 => [
                'member_type' => 'required|in:corporate,individual',
                'category_id' => 'required|exists:membership_categories,id',
                'purpose_of_membership' => 'required|string|min:50',
            ],
            3 => [
                'business_type' => 'required|string|max:255',
                'years_in_operation' => 'required|string|max:10',
                'annual_turnover' => 'required|string|max:100',
                'export_markets' => 'nullable|string',
            ],
            4 => [
                'declaration_accepted' => 'accepted',
            ],
            default => [],
        };
    }

    public function mount(): void
    {
        $chapter = auth()->user()->chapter;
        abort_unless($chapter, 403, 'No chapter assigned.');
    }

    public function updatedMemberType(): void
    {
        $this->category_id = null;
    }

    public function nextStep(): void
    {
        $this->validate();

        if ($this->step === 2 && $this->category_id) {
            $belongs = MembershipCategory::whereKey($this->category_id)
                ->where('member_type', $this->member_type)
                ->exists();
            if (! $belongs) {
                $this->addError('category_id', 'Please choose a tier for the selected member type.');

                return;
            }
        }

        $this->step++;
    }

    public function prevStep(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function submit(): void
    {
        $this->validate();

        $application = MembershipApplication::create([
            'chapter_id' => auth()->user()->chapter_id,
            'category_id' => $this->category_id,
            'applicant_name' => $this->applicant_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'organization' => $this->organization,
            'business_profile' => [
                'business_type' => $this->business_type,
                'years_in_operation' => $this->years_in_operation,
                'annual_turnover' => $this->annual_turnover,
                'export_markets' => $this->export_markets,
            ],
            'purpose_of_membership' => $this->purpose_of_membership,
            'declaration_accepted' => true,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        try {
            Notification::route('mail', $this->email)
                ->notify(new ApplicationReceived($application));
            Notification::route('mail', app(SettingsService::class)->adminNotificationEmail())
                ->notify(new ApplicationSubmittedAdmin($application));
        } catch (\Throwable $e) {
            report($e);
        }

        session()->flash('success', 'Application submitted successfully. Reference: #'.$application->id);

        $this->redirect(route('admin.membership.applications'));
    }

    public function render()
    {
        return view('livewire.membership.application-form', [
            'categories' => MembershipCategory::where('is_active', true)->orderBy('sort_order')->get(),
        ])->layout('layouts.admin');
    }
}
