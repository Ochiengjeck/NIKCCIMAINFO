<div class="max-w-3xl mx-auto">
    <flux:heading size="xl" class="mb-1">New Membership Application</flux:heading>
    <flux:subheading class="mb-6">Complete all steps to submit your application</flux:subheading>

    {{-- Progress --}}
    <div class="mb-8 flex items-center gap-2">
        @foreach(range(1, $totalSteps) as $s)
            <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-medium
                    {{ $s < $step ? 'bg-green-600 text-white' : ($s === $step ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'border border-zinc-300 text-zinc-400') }}">
                    {{ $s < $step ? '✓' : $s }}
                </div>
                @if(!$loop->last)
                    <div class="h-0.5 flex-1 {{ $s < $step ? 'bg-green-600' : 'bg-zinc-200 dark:bg-zinc-700' }}"></div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">

        {{-- Step 1: Applicant Info --}}
        @if($step === 1)
            <flux:heading size="lg" class="mb-4">Applicant Information</flux:heading>
            <form wire:submit="nextStep" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:field class="sm:col-span-2">
                    <flux:label>Full Name / Organisation</flux:label>
                    <flux:input wire:model="applicant_name" placeholder="First Last / Company Ltd" />
                    <flux:error name="applicant_name" />
                </flux:field>
                <flux:field>
                    <flux:label>Contact Person (if organisation)</flux:label>
                    <flux:input wire:model="contact_person" />
                    <flux:error name="contact_person" />
                </flux:field>
                <flux:field>
                    <flux:label>Email Address</flux:label>
                    <flux:input type="email" wire:model="email" />
                    <flux:error name="email" />
                </flux:field>
                <flux:field>
                    <flux:label>Phone / WhatsApp</flux:label>
                    <flux:input wire:model="phone" placeholder="+234..." />
                    <flux:error name="phone" />
                </flux:field>
                <flux:field>
                    <flux:label>Country</flux:label>
                    <flux:input wire:model="country" placeholder="e.g. Nigeria" />
                    <flux:error name="country" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Organisation / Company Name</flux:label>
                    <flux:input wire:model="organization" />
                    <flux:error name="organization" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Address</flux:label>
                    <flux:input wire:model="address" />
                    <flux:error name="address" />
                </flux:field>
                <flux:field>
                    <flux:label>Website / Social Media</flux:label>
                    <flux:input wire:model="website" placeholder="https://..." />
                    <flux:error name="website" />
                </flux:field>
                <flux:field>
                    <flux:label>Recommended / Sponsored by</flux:label>
                    <flux:input wire:model="sponsored_by" />
                    <flux:error name="sponsored_by" />
                </flux:field>
                <div class="sm:col-span-2">
                    <flux:button type="submit" variant="primary">Next &rarr;</flux:button>
                </div>
            </form>
        @endif

        {{-- Step 2: Category --}}
        @if($step === 2)
            <flux:heading size="lg" class="mb-4">Membership Category</flux:heading>
            <form wire:submit="nextStep" class="grid grid-cols-1 gap-4">
                @if($grouped)
                    <flux:field>
                        <flux:label>Member Type</flux:label>
                        <flux:select wire:model.live="member_type">
                            <option value="">Select type…</option>
                            <option value="corporate">Corporate</option>
                            <option value="individual">Individual</option>
                        </flux:select>
                        <flux:error name="member_type" />
                    </flux:field>
                    @php $available = $member_type ? $categories->filter(fn ($c) => $c->availableForGroup($member_type)) : collect(); @endphp
                    <flux:field>
                        <flux:label>Category</flux:label>
                        <flux:select wire:model="category_id" :disabled="!$member_type">
                            <option value="">{{ $member_type ? 'Select category…' : 'Choose a member type first' }}</option>
                            @foreach($available as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }} — {{ $cat->priceLabelUsd($member_type) }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="category_id" />
                    </flux:field>
                @else
                    <flux:field>
                        <flux:label>Category</flux:label>
                        <flux:select wire:model="category_id">
                            <option value="">Select category…</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }} — {{ $cat->priceLabelUsd() }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="category_id" />
                    </flux:field>
                @endif
                <div class="flex gap-3">
                    <flux:button wire:click="prevStep" variant="ghost">&larr; Back</flux:button>
                    <flux:button type="submit" variant="primary">Next &rarr;</flux:button>
                </div>
            </form>
        @endif

        {{-- Step 3: Business Profile --}}
        @if($step === 3)
            <flux:heading size="lg" class="mb-4">Business / Professional Profile</flux:heading>
            <form wire:submit="nextStep" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:field class="sm:col-span-2">
                    <flux:label>Primary Sector / Industry</flux:label>
                    <flux:input wire:model="primary_sector" placeholder="e.g. Agriculture, Manufacturing, Financial Services" />
                    <flux:error name="primary_sector" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Brief Summary of Activities</flux:label>
                    <flux:textarea wire:model="activity_summary" rows="3" placeholder="2–3 lines describing the organisation's activities" />
                    <flux:error name="activity_summary" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Business Type <span class="text-zinc-400 font-normal">(optional)</span></flux:label>
                    <flux:input wire:model="business_type" placeholder="e.g. Manufacturing, Agro-processing…" />
                </flux:field>
                <flux:field>
                    <flux:label>Years in Operation <span class="text-zinc-400 font-normal">(optional)</span></flux:label>
                    <flux:input wire:model="years_in_operation" placeholder="e.g. 5" />
                </flux:field>
                <flux:field>
                    <flux:label>Annual Turnover Range <span class="text-zinc-400 font-normal">(optional)</span></flux:label>
                    <flux:input wire:model="annual_turnover" placeholder="e.g. USD 500K – 1M" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Export Markets <span class="text-zinc-400 font-normal">(optional)</span></flux:label>
                    <flux:input wire:model="export_markets" placeholder="e.g. Nigeria, Kenya, UK…" />
                </flux:field>
                <div class="sm:col-span-2 flex gap-3">
                    <flux:button wire:click="prevStep" variant="ghost">&larr; Back</flux:button>
                    <flux:button type="submit" variant="primary">Next &rarr;</flux:button>
                </div>
            </form>
        @endif

        {{-- Step 4: Declaration --}}
        @if($step === 4)
            <flux:heading size="lg" class="mb-4">Purpose & Declaration</flux:heading>
            <form wire:submit="submit" class="grid grid-cols-1 gap-4">
                <flux:field>
                    <flux:label>Purpose of Membership</flux:label>
                    <flux:textarea wire:model="purpose_of_membership" rows="4" placeholder="Briefly state why you wish to join NiKCCIMA (minimum 50 characters)..." />
                    <flux:error name="purpose_of_membership" />
                </flux:field>
                <div class="rounded-lg bg-zinc-50 p-4 text-sm text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                    I hereby declare that all information provided is accurate and complete. I agree to abide by
                    the NiKCCIMA Code of Conduct and bylaws, and I understand that providing false information
                    may result in disqualification or membership revocation.
                </div>
                <flux:field>
                    <flux:checkbox wire:model="declaration_accepted" label="I accept the declaration above and consent to NiKCCIMA processing my information." />
                    <flux:error name="declaration_accepted" />
                </flux:field>
                <div class="flex gap-3">
                    <flux:button wire:click="prevStep" variant="ghost">&larr; Back</flux:button>
                    <flux:button type="submit" variant="primary">Submit Application</flux:button>
                </div>
            </form>
        @endif

    </div>
</div>
