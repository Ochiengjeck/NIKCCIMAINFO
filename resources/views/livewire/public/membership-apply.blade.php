<div>
    @if($submitted)
        {{-- Success State --}}
        <div class="rounded-2xl border border-green-200 bg-green-50 p-10 text-center dark:border-green-800 dark:bg-green-900/20">
            <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-green-100 dark:bg-green-800/40">
                <svg class="h-10 w-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="mb-2 font-serif text-2xl font-bold text-green-800 dark:text-green-300">Application Submitted!</h2>
            <p class="mx-auto mb-1 max-w-md text-sm text-green-700 dark:text-green-400">Your membership application has been received and is under review.</p>
            <p class="mb-6 text-sm font-medium text-green-800 dark:text-green-300">Reference: <span class="font-mono">#{{ $applicationId }}</span></p>
            <p class="mb-6 text-sm text-green-600 dark:text-green-500">We'll review your application and contact you within <strong>5 working days</strong>.</p>
            <a href="{{ route('membership') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-green-300 bg-white px-5 py-2.5 text-sm font-medium text-green-700 transition hover:bg-green-50 dark:border-green-700 dark:bg-zinc-800 dark:text-green-400 dark:hover:bg-zinc-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Membership
            </a>
        </div>
    @else

        {{-- Step Indicator --}}
        <div class="mb-8">
            <div class="mb-4 flex items-center justify-between">
                @foreach(['Personal Info', 'Category', 'Business Profile', 'Declaration'] as $i => $label)
                    <div class="flex flex-1 flex-col items-center {{ $loop->last ? '' : 'relative' }}">
                        <div class="relative z-10 flex h-9 w-9 items-center justify-center rounded-full border-2 text-sm font-semibold transition
                            {{ ($i + 1) < $step ? 'border-green-600 bg-green-600 text-white' :
                               (($i + 1) === $step ? 'border-green-600 bg-white text-green-700 shadow-md dark:bg-zinc-900 dark:text-green-400' :
                               'border-zinc-300 bg-white text-zinc-400 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-500') }}">
                            @if(($i + 1) < $step)
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <span class="mt-1.5 hidden text-xs {{ ($i + 1) <= $step ? 'font-semibold text-green-700 dark:text-green-400' : 'text-zinc-400 dark:text-zinc-500' }} sm:block">
                            {{ $label }}
                        </span>
                        @if(!$loop->last)
                            <div class="absolute left-1/2 top-4 h-0.5 w-full
                                {{ ($i + 1) < $step ? 'bg-green-600' : 'bg-zinc-200 dark:bg-zinc-700' }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="h-1.5 w-full overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-700">
                <div class="h-1.5 rounded-full bg-green-600 transition-all duration-500"
                    style="width: {{ round(($step / $totalSteps) * 100) }}%"></div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">

            {{-- Step 1: Personal Info --}}
            @if($step === 1)
                <h2 class="mb-6 font-serif text-xl font-bold text-zinc-900 dark:text-white">Personal & Organisation Details</h2>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Full Name <span class="text-red-500">*</span></label>
                        <input wire:model="applicant_name" type="text" placeholder="John Doe"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500">
                        @error('applicant_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Email Address <span class="text-red-500">*</span></label>
                        <input wire:model="email" type="email" placeholder="john@company.com"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Phone Number <span class="text-red-500">*</span></label>
                        <input wire:model="phone" type="tel" placeholder="+234 000 000 0000"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500">
                        @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Organisation <span class="text-red-500">*</span></label>
                        <input wire:model="organization" type="text" placeholder="Company Name Ltd"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500">
                        @error('organization') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Which chapter are you applying under? <span class="text-red-500">*</span></label>
                        <select wire:model="chapter"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">Select chapter</option>
                            <option value="nigeria">🇳🇬 Nigeria Chapter</option>
                            <option value="kenya">🇰🇪 Kenya Chapter</option>
                        </select>
                        @error('chapter') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            {{-- Step 2: Category & Purpose --}}
            @if($step === 2)
                <h2 class="mb-6 font-serif text-xl font-bold text-zinc-900 dark:text-white">Membership Category & Purpose</h2>
                <div class="space-y-5">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Membership Category <span class="text-red-500">*</span></label>
                        <select wire:model="category_id"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">Select a category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }} — ₦{{ number_format($cat->fee_ngn) }} / KES {{ number_format($cat->fee_kes) }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Purpose of Membership <span class="text-red-500">*</span></label>
                        <p class="mb-2 text-xs text-zinc-500 dark:text-zinc-400">Minimum 50 characters. Explain why you are applying and what you hope to achieve.</p>
                        <textarea wire:model="purpose_of_membership" rows="5" placeholder="Describe your reason for applying..."
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500"></textarea>
                        @error('purpose_of_membership') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            {{-- Step 3: Business Profile --}}
            @if($step === 3)
                <h2 class="mb-6 font-serif text-xl font-bold text-zinc-900 dark:text-white">Business Profile</h2>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Business Type <span class="text-red-500">*</span></label>
                        <input wire:model="business_type" type="text" placeholder="e.g. Manufacturing, Trading, Services"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500">
                        @error('business_type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Years in Operation <span class="text-red-500">*</span></label>
                        <input wire:model="years_in_operation" type="text" placeholder="e.g. 5"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500">
                        @error('years_in_operation') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Annual Turnover (approximate) <span class="text-red-500">*</span></label>
                        <input wire:model="annual_turnover" type="text" placeholder="e.g. $500,000 USD"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500">
                        @error('annual_turnover') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Current Export Markets <span class="text-xs font-normal text-zinc-400">(optional)</span></label>
                        <input wire:model="export_markets" type="text" placeholder="e.g. Nigeria, Kenya, Ghana"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500">
                    </div>
                </div>
            @endif

            {{-- Step 4: Declaration --}}
            @if($step === 4)
                <h2 class="mb-6 font-serif text-xl font-bold text-zinc-900 dark:text-white">Digital Declaration</h2>
                <div class="mb-6 rounded-2xl border border-zinc-200 bg-zinc-50 p-5 dark:border-zinc-700 dark:bg-zinc-800/50">
                    <p class="mb-3 text-sm font-semibold text-zinc-700 dark:text-zinc-300">By submitting this application, you declare that:</p>
                    <ul class="space-y-2.5">
                        @foreach([
                            'All information provided is true, accurate, and complete.',
                            'You understand NiKCCIMA membership is subject to approval by the governing council.',
                            'You agree to abide by the NiKCCIMA code of conduct and bilateral trade guidelines.',
                            'You consent to NiKCCIMA contacting you regarding your application and membership matters.'
                        ] as $point)
                            <li class="flex items-start gap-2.5 text-sm text-zinc-600 dark:text-zinc-400">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $point }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-zinc-200 p-4 transition hover:bg-zinc-50 dark:border-zinc-700 dark:hover:bg-zinc-800">
                    <input wire:model="declaration_accepted" type="checkbox"
                        class="mt-0.5 h-4 w-4 rounded border-zinc-300 text-green-600 dark:border-zinc-600">
                    <span class="text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">
                        I confirm that all information is accurate and I agree to the NiKCCIMA membership declaration above.
                        <span class="text-red-500">*</span>
                    </span>
                </label>
                @error('declaration_accepted') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
            @endif

            {{-- Navigation --}}
            <div class="mt-8 flex items-center justify-between">
                @if($step > 1)
                    <button wire:click="prevStep" type="button"
                        class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 px-5 py-2.5 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Previous
                    </button>
                @else
                    <div></div>
                @endif

                @if($step < $totalSteps)
                    <button wire:click="nextStep" type="button"
                        class="inline-flex items-center gap-2 rounded-xl bg-green-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800 dark:bg-green-700 dark:hover:bg-green-600">
                        Next
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                @else
                    <button wire:click="submit" type="button" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-xl bg-green-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-green-800 disabled:opacity-60 dark:bg-green-700 dark:hover:bg-green-600">
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
