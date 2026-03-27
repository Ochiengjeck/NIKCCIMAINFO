<div>
    @if($submitted)
        <div class="rounded bg-brand-50 border border-brand-200 p-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-brand-100">
                <svg class="h-7 w-7 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold font-serif text-zinc-900 mb-2">Inquiry Received</h3>
            <p class="text-[15px] text-zinc-600">Thank you — we've received your message and will respond within 2 business days.</p>
            <button wire:click="$set('submitted', false)"
                class="mt-6 inline-block bg-brand-500 text-white px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                Send another inquiry
            </button>
        </div>
    @else
        <h3 class="text-xl font-bold font-serif text-zinc-900 mb-8">Send a Message</h3>

        <form wire:submit.prevent="submit" class="space-y-5">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">Your Name <span class="text-crimson-600">*</span></label>
                    <input wire:model="name" type="text" placeholder="John Doe"
                        class="bg-zinc-50 border border-zinc-200 rounded px-4 py-3 w-full text-zinc-900 text-[15px] focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                    @error('name') <p class="text-sm text-crimson-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">Email Address <span class="text-crimson-600">*</span></label>
                    <input wire:model="email" type="email" placeholder="you@company.com"
                        class="bg-zinc-50 border border-zinc-200 rounded px-4 py-3 w-full text-zinc-900 text-[15px] focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                    @error('email') <p class="text-sm text-crimson-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">Subject <span class="text-crimson-600">*</span></label>
                    <input wire:model="subject" type="text" placeholder="Membership / Trade Inquiry / General"
                        class="bg-zinc-50 border border-zinc-200 rounded px-4 py-3 w-full text-zinc-900 text-[15px] focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                    @error('subject') <p class="text-sm text-crimson-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">Chapter</label>
                    <select wire:model="chapter"
                        class="bg-zinc-50 border border-zinc-200 rounded px-4 py-3 w-full text-zinc-900 text-[15px] focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all">
                        <option value="general">General Inquiry</option>
                        <option value="nigeria">🇳🇬 Nigeria Chapter</option>
                        <option value="kenya">🇰🇪 Kenya Chapter</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-2">Message <span class="text-crimson-600">*</span></label>
                <textarea wire:model="message" rows="5" placeholder="Write your message here (minimum 20 characters)..."
                    class="bg-zinc-50 border border-zinc-200 rounded px-4 py-3 w-full text-zinc-900 text-[15px] focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all min-h-[150px] resize-y"></textarea>
                @error('message') <p class="text-sm text-crimson-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled"
                class="inline-block bg-brand-500 text-white px-10 py-3 rounded text-sm font-medium hover:opacity-90 transition-all disabled:opacity-60">
                <span wire:loading.remove wire:target="submit">Send Inquiry</span>
                <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Sending...
                </span>
            </button>
        </form>
    @endif
</div>
