<div>
    @if($submitted)
        <div class="rounded bg-brand-50 border border-brand-200 p-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-brand-100">
                <svg class="h-7 w-7 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold font-serif text-zinc-900 mb-2">You're registered!</h3>
            <p class="text-[15px] text-zinc-600">Thank you for registering for <span class="font-medium text-zinc-800">{{ $event->title }}</span>.</p>
            @if($ticketNumber)
                <p class="mt-3 text-sm text-zinc-600">Your reference number:</p>
                <p class="mt-1 inline-block rounded-lg bg-white border border-brand-200 px-4 py-2 font-mono text-base font-semibold tracking-wider text-brand-700">{{ $ticketNumber }}</p>
            @endif
            <div>
                <button wire:click="$set('submitted', false)"
                    class="mt-6 inline-block bg-brand-500 text-white px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                    Register another participant
                </button>
            </div>
        </div>
    @else
        <div class="mb-6">
            <h3 class="text-xl font-bold font-serif text-zinc-900">Register for this Event</h3>
            <p class="mt-1 text-[15px] text-zinc-600">Reserve your spot — it's free. Fields marked <span class="text-crimson-600">*</span> are required.</p>
        </div>

        <form wire:submit.prevent="submit" class="space-y-5">
            {{-- Name (full width) --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-2">Name of Participant <span class="text-crimson-600">*</span></label>
                <input wire:model="attendee_name" type="text" placeholder="Full name"
                    class="bg-zinc-50 border border-zinc-200 rounded px-4 py-3 w-full text-zinc-900 text-[15px] focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                @error('attendee_name') <p class="text-sm text-crimson-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Organisation + Designation --}}
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">Organisation</label>
                    <input wire:model="organisation" type="text" placeholder="Company / institution"
                        class="bg-zinc-50 border border-zinc-200 rounded px-4 py-3 w-full text-zinc-900 text-[15px] focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                    @error('organisation') <p class="text-sm text-crimson-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">Designation</label>
                    <input wire:model="designation" type="text" placeholder="Job title / role"
                        class="bg-zinc-50 border border-zinc-200 rounded px-4 py-3 w-full text-zinc-900 text-[15px] focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                    @error('designation') <p class="text-sm text-crimson-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Contact Email + WhatsApp --}}
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">Contact Email <span class="text-crimson-600">*</span></label>
                    <input wire:model="attendee_email" type="email" placeholder="you@company.com"
                        class="bg-zinc-50 border border-zinc-200 rounded px-4 py-3 w-full text-zinc-900 text-[15px] focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                    @error('attendee_email') <p class="text-sm text-crimson-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">WhatsApp Number</label>
                    <input wire:model="whatsapp_number" type="tel" placeholder="+254 700 000 000"
                        class="bg-zinc-50 border border-zinc-200 rounded px-4 py-3 w-full text-zinc-900 text-[15px] focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                    @error('whatsapp_number') <p class="text-sm text-crimson-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- OOC11 engagement Yes/No --}}
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-2">Call to NIKCCIMA post-OOC11 2026 Engagement</label>
                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input wire:model="ooc11_engagement" type="radio" value="1"
                            class="h-4 w-4 border-zinc-300 text-brand-600 focus:ring-brand-500">
                        <span class="text-[15px] text-zinc-700">Yes</span>
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input wire:model="ooc11_engagement" type="radio" value="0"
                            class="h-4 w-4 border-zinc-300 text-brand-600 focus:ring-brand-500">
                        <span class="text-[15px] text-zinc-700">No</span>
                    </label>
                </div>
                @error('ooc11_engagement') <p class="text-sm text-crimson-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 bg-brand-500 text-white px-10 py-3 rounded text-sm font-medium hover:opacity-90 transition-all disabled:opacity-60">
                <span wire:loading.remove wire:target="submit" class="inline-flex items-center gap-2">
                    Register Now
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </span>
                <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Registering...
                </span>
            </button>
        </form>
    @endif
</div>
