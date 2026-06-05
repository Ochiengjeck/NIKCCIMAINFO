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

    public bool $grouped = false;

    // Step 1 — Applicant Information (Section A)
    public string $applicant_name = '';

    public string $contact_person = '';

    public string $email = '';

    public string $phone = '';

    public string $organization = '';

    public string $address = '';

    public string $country = '';

    public string $website = '';

    public string $sponsored_by = '';

    public string $chapter = ''; // 'nigeria' or 'kenya' — used for chapter routing

    // Step 2 — Membership Category (Section B)
    public string $member_type = ''; // transient group choice when grouping is on

    public ?int $category_id = null;

    // Step 3 — Business / Professional Profile (Section C)
    public string $primary_sector = '';

    public string $activity_summary = '';

    public string $business_type = '';

    public string $years_in_operation = '';

    public string $annual_turnover = '';

    public string $export_markets = '';

    // Step 4 — Purpose (Section D) + Declaration (Section E)
    public string $purpose_of_membership = '';

    public bool $declaration_accepted = false;

    public bool $submitted = false;

    public ?int $applicationId = null;

    public function mount(SettingsService $settings): void
    {
        $this->grouped = $settings->membershipGroupByType();
    }

    protected function rules(): array
    {
        return match ($this->step) {
            1 => [
                'applicant_name' => 'required|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:30',
                'organization' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'country' => 'required|string|max:100',
                'website' => 'nullable|string|max:255',
                'sponsored_by' => 'nullable|string|max:255',
                'chapter' => 'required|in:nigeria,kenya',
            ],
            2 => array_merge(
                $this->grouped ? ['member_type' => 'required|in:corporate,individual'] : [],
                ['category_id' => 'required|exists:membership_categories,id'],
            ),
            3 => [
                'primary_sector' => 'required|string|max:255',
                'activity_summary' => 'required|string|max:1000',
                'business_type' => 'nullable|string|max:255',
                'years_in_operation' => 'nullable|string|max:10',
                'annual_turnover' => 'nullable|string|max:100',
                'export_markets' => 'nullable|string',
            ],
            4 => [
                'purpose_of_membership' => 'required|string|min:50',
                'declaration_accepted' => 'accepted',
            ],
            default => [],
        };
    }

    public function updatedMemberType(): void
    {
        // Reset the category when the group changes so a mismatched one can't carry over.
        $this->category_id = null;
    }

    public function nextStep(): void
    {
        $this->validate();

        if ($this->step === 2 && $this->category_id) {
            $category = MembershipCategory::find($this->category_id);
            $group = $this->grouped ? $this->member_type : null;
            if (! $category || ! $category->is_active || ! $category->availableForGroup($group)) {
                $this->addError('category_id', 'Please choose a valid category for your selection.');

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
            'contact_person' => $this->contact_person ?: null,
            'email' => $this->email,
            'phone' => $this->phone,
            'organization' => $this->organization,
            'address' => $this->address ?: null,
            'country' => $this->country ?: null,
            'website' => $this->website ?: null,
            'sponsored_by' => $this->sponsored_by ?: null,
            'business_profile' => [
                'primary_sector' => $this->primary_sector,
                'activity_summary' => $this->activity_summary,
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
