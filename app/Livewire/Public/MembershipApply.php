<?php

namespace App\Livewire\Public;

use App\Models\Chapter;
use App\Models\MembershipApplication;
use App\Models\MembershipCategory;
use App\Notifications\ApplicationReceived;
use App\Notifications\ApplicationSubmittedAdmin;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class MembershipApply extends Component
{
    public int $step = 1;

    public int $totalSteps = 4;

    // Step 1
    public string $applicant_name = '';

    public string $email = '';

    public string $phone = '';

    public string $organization = '';

    public string $chapter = ''; // 'nigeria' or 'kenya'

    // Step 2
    public string $member_type = '';

    public ?int $category_id = null;

    public string $purpose_of_membership = '';

    // Step 3
    public string $business_type = '';

    public string $years_in_operation = '';

    public string $annual_turnover = '';

    public string $export_markets = '';

    // Step 4
    public bool $declaration_accepted = false;

    public bool $submitted = false;

    public ?int $applicationId = null;

    protected function rules(): array
    {
        return match ($this->step) {
            1 => [
                'applicant_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:30',
                'organization' => 'required|string|max:255',
                'chapter' => 'required|in:nigeria,kenya',
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

    public function updatedMemberType(): void
    {
        // Reset the tier when the type changes so a mismatched category can't carry over.
        $this->category_id = null;
    }

    public function nextStep(): void
    {
        $this->validate();

        // Ensure the chosen tier actually belongs to the selected member type.
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

        $chapter = Chapter::where('name', 'like', '%'.ucfirst($this->chapter).'%')->first();

        $application = MembershipApplication::create([
            'chapter_id' => $chapter?->id,
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

        $this->applicationId = $application->id;
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.public.membership-apply', [
            'categories' => MembershipCategory::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
}
