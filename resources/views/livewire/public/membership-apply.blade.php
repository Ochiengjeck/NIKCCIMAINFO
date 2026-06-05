<div>
    @if($submitted)
        {{-- Success State --}}
        <div class="rounded-2xl border border-brand-200 bg-brand-50 p-10 text-center">
            <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-brand-100">
                <svg class="h-10 w-10 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="mb-2 font-serif text-2xl font-bold text-brand-800">Application Submitted!</h2>
            <p class="mx-auto mb-1 max-w-md text-sm text-brand-700">Your membership application has been received and is under review.</p>
            <p class="mb-6 text-sm font-medium text-brand-800">Reference: <span class="font-mono">#{{ $applicationId }}</span></p>
            <p class="mb-6 text-sm text-brand-600">We'll review your application and contact you within <strong>5 working days</strong>.</p>
            <a href="{{ route('membership') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-brand-300 bg-white px-5 py-2.5 text-sm font-medium text-brand-700 transition hover:bg-brand-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Membership
            </a>
        </div>
    @else

        {{-- Step Indicator --}}
        <div class="mb-8">
            <div class="mb-4 flex items-center justify-between">
                @foreach(['Applicant Info', 'Category', 'Business Profile', 'Declaration'] as $i => $label)
                    <div class="flex flex-1 flex-col items-center {{ $loop->last ? '' : 'relative' }}">
                        <div class="relative z-10 flex h-9 w-9 items-center justify-center rounded-full border-2 text-sm font-semibold transition
                            {{ ($i + 1) < $step ? 'border-brand-600 bg-brand-600 text-white' :
                               (($i + 1) === $step ? 'border-brand-600 bg-white text-brand-700 shadow-md' :
                               'border-zinc-300 bg-white text-zinc-400') }}">
                            @if(($i + 1) < $step)
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <span class="mt-1.5 hidden text-xs {{ ($i + 1) <= $step ? 'font-semibold text-brand-700' : 'text-zinc-400' }} sm:block">
                            {{ $label }}
                        </span>
                        @if(!$loop->last)
                            <div class="absolute left-1/2 top-4 h-0.5 w-full
                                {{ ($i + 1) < $step ? 'bg-brand-600' : 'bg-zinc-200' }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="h-1.5 w-full overflow-hidden rounded-full bg-zinc-200">
                <div class="h-1.5 rounded-full bg-brand-600 transition-all duration-500"
                    style="width: {{ round(($step / $totalSteps) * 100) }}%"></div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm">

            {{-- Step 1: Personal Info --}}
            @if($step === 1)
                <h2 class="mb-6 font-serif text-xl font-bold text-zinc-900">Applicant Information</h2>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Full Name / Organisation <span class="text-crimson-500">*</span></label>
                        <input wire:model="applicant_name" type="text" placeholder="John Doe / Company Ltd"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                        @error('applicant_name') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Contact Person <span class="text-xs font-normal text-zinc-400">(if an organisation)</span></label>
                        <input wire:model="contact_person" type="text" placeholder="Primary contact"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                        @error('contact_person') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Email Address <span class="text-crimson-500">*</span></label>
                        <input wire:model="email" type="email" placeholder="john@company.com"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                        @error('email') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Phone / WhatsApp <span class="text-crimson-500">*</span></label>
                        <input wire:model="phone" type="tel" placeholder="+234 000 000 0000"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                        @error('phone') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Organisation <span class="text-crimson-500">*</span></label>
                        <input wire:model="organization" type="text" placeholder="Company Name Ltd"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                        @error('organization') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Country <span class="text-crimson-500">*</span></label>
                        <input wire:model="country" type="text" placeholder="e.g. Nigeria"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                        @error('country') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Address <span class="text-crimson-500">*</span></label>
                        <input wire:model="address" type="text" placeholder="Street, city, state"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                        @error('address') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Website / Social Media <span class="text-xs font-normal text-zinc-400">(optional)</span></label>
                        <input wire:model="website" type="text" placeholder="https://..."
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                        @error('website') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Recommended / Sponsored by <span class="text-xs font-normal text-zinc-400">(optional)</span></label>
                        <input wire:model="sponsored_by" type="text" placeholder="Existing member / referrer"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                        @error('sponsored_by') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Which chapter are you applying under? <span class="text-crimson-500">*</span></label>
                        <select wire:model="chapter"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                            <option value="">Select chapter</option>
                            <option value="nigeria">🇳🇬 Nigeria Chapter</option>
                            <option value="kenya">🇰🇪 Kenya Chapter</option>
                        </select>
                        @error('chapter') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            {{-- Step 2: Membership Category --}}
            @if($step === 2)
                <h2 class="mb-6 font-serif text-xl font-bold text-zinc-900">Membership Category</h2>
                <div class="space-y-5">
                    @if($grouped)
                        {{-- Member type first --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-700">Member Type <span class="text-crimson-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach(['corporate' => 'Corporate', 'individual' => 'Individual'] as $value => $label)
                                    <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border px-4 py-3 text-sm transition
                                        {{ $member_type === $value ? 'border-brand-500 bg-brand-50 text-brand-800' : 'border-zinc-300 hover:bg-zinc-50 text-zinc-700' }}">
                                        <input type="radio" wire:model.live="member_type" value="{{ $value }}" class="h-4 w-4 text-brand-600">
                                        <span class="font-medium">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('member_type') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                        </div>

                        @php $available = $member_type ? $categories->filter(fn ($c) => $c->availableForGroup($member_type)) : collect(); @endphp
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-700">Category <span class="text-crimson-500">*</span></label>
                            <select wire:model="category_id" @disabled(!$member_type)
                                class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20 disabled:bg-zinc-100 disabled:text-zinc-400">
                                <option value="">{{ $member_type ? 'Select a category' : 'Choose a member type first' }}</option>
                                @foreach($available as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }} — {{ $cat->priceLabelUsd($member_type) }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                        </div>
                    @else
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-zinc-700">Membership Category <span class="text-crimson-500">*</span></label>
                            <select wire:model="category_id"
                                class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                                <option value="">Select a category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }} — {{ $cat->priceLabelUsd() }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>
            @endif

            {{-- Step 3: Business Profile --}}
            @if($step === 3)
                <h2 class="mb-6 font-serif text-xl font-bold text-zinc-900">Business / Professional Profile</h2>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Primary Sector / Industry <span class="text-crimson-500">*</span></label>
                        <input wire:model="primary_sector" type="text" placeholder="e.g. Agriculture, Manufacturing, Financial Services"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                        @error('primary_sector') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Brief Summary of Activities <span class="text-crimson-500">*</span></label>
                        <textarea wire:model="activity_summary" rows="3" placeholder="2–3 lines describing what your organisation does"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20"></textarea>
                        @error('activity_summary') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Business Type <span class="text-xs font-normal text-zinc-400">(optional)</span></label>
                        <input wire:model="business_type" type="text" placeholder="e.g. Manufacturing, Trading, Services"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Years in Operation <span class="text-xs font-normal text-zinc-400">(optional)</span></label>
                        <input wire:model="years_in_operation" type="text" placeholder="e.g. 5"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Annual Turnover <span class="text-xs font-normal text-zinc-400">(optional)</span></label>
                        <input wire:model="annual_turnover" type="text" placeholder="e.g. $500,000 USD"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700">Current Export Markets <span class="text-xs font-normal text-zinc-400">(optional)</span></label>
                        <input wire:model="export_markets" type="text" placeholder="e.g. Nigeria, Kenya, Ghana"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20">
                    </div>
                </div>
            @endif

            {{-- Step 4: Declaration --}}
            @if($step === 4)
                <h2 class="mb-6 font-serif text-xl font-bold text-zinc-900">Purpose &amp; Declaration</h2>
                <div class="mb-6">
                    <label class="mb-1.5 block text-sm font-medium text-zinc-700">Purpose of Membership <span class="text-crimson-500">*</span></label>
                    <p class="mb-2 text-xs text-zinc-500">Minimum 50 characters. Briefly state why you wish to join NiKCCIMA.</p>
                    <textarea wire:model="purpose_of_membership" rows="5" placeholder="Describe your reason for applying..."
                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20"></textarea>
                    @error('purpose_of_membership') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                </div>
                <div class="mb-6 rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                    <p class="mb-3 text-sm font-semibold text-zinc-700">By submitting this application, you declare that:</p>
                    <ul class="space-y-2.5">
                        @foreach([
                            'All information provided is true, accurate, and complete.',
                            'You understand NiKCCIMA membership is subject to approval by the governing council.',
                            'You agree to abide by the NiKCCIMA code of conduct and bilateral trade guidelines.',
                            'You consent to NiKCCIMA contacting you regarding your application and membership matters.'
                        ] as $point)
                            <li class="flex items-start gap-2.5 text-sm text-zinc-600">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $point }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-zinc-200 p-4 transition hover:bg-zinc-50">
                    <input wire:model="declaration_accepted" type="checkbox"
                        class="mt-0.5 h-4 w-4 rounded border-zinc-300 text-brand-600">
                    <span class="text-sm leading-relaxed text-zinc-700">
                        I confirm that all information is accurate and I agree to the NiKCCIMA membership declaration above.
                        <span class="text-crimson-500">*</span>
                    </span>
                </label>
                @error('declaration_accepted') <p class="mt-2 text-xs text-crimson-500">{{ $message }}</p> @enderror
            @endif

            {{-- Navigation --}}
            <div class="mt-8 flex items-center justify-between">
                @if($step > 1)
                    <button wire:click="prevStep" type="button"
                        class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 px-5 py-2.5 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Previous
                    </button>
                @else
                    <div></div>
                @endif

                @if($step < $totalSteps)
                    <button wire:click="nextStep" type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-crimson-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-crimson-800">
                        Next
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                @else
                    <button wire:click="submit" type="button" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-xl bg-crimson-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-crimson-800 disabled:opacity-60">
                        <span wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                            Submit Application
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span wire:loading wire:target="submit" class="flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Submitting...
                        </span>
                    </button>
                @endif
            </div>

        </div>
    @endif
</div>
