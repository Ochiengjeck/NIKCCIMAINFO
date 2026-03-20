<div>
    @if($submitted)
        <div class="rounded-2xl border border-brand-200 bg-brand-50 p-8 text-center dark:border-brand-800 dark:bg-brand-900/20">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-brand-100 dark:bg-brand-800/40">
                <svg class="h-7 w-7 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="mb-2 text-lg font-bold text-brand-800 dark:text-brand-300">Inquiry Received</h3>
            <p class="text-sm text-brand-700 dark:text-brand-400">Thank you — we've received your message and will respond within 2 business days.</p>
            <button wire:click="$set('submitted', false)"
                class="mt-5 inline-flex items-center gap-1.5 rounded-lg border border-brand-300 bg-white px-4 py-2 text-sm font-medium text-brand-700 transition hover:bg-brand-50 dark:border-brand-700 dark:bg-zinc-800 dark:text-brand-400 dark:hover:bg-zinc-700">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Send another inquiry
            </button>
        </div>
    @else
        <form wire:submit.prevent="submit" class="space-y-5">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Your Name <span class="text-crimson-500">*</span></label>
                    <input wire:model="name" type="text" placeholder="John Doe"
                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500">
                    @error('name') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Email Address <span class="text-crimson-500">*</span></label>
                    <input wire:model="email" type="email" placeholder="you@company.com"
                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500">
                    @error('email') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Subject <span class="text-crimson-500">*</span></label>
                    <input wire:model="subject" type="text" placeholder="Membership / Trade Inquiry / General"
                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500">
                    @error('subject') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Chapter</label>
                    <select wire:model="chapter"
                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        <option value="general">General Inquiry</option>
                        <option value="nigeria">🇳🇬 Nigeria Chapter</option>
                        <option value="kenya">🇰🇪 Kenya Chapter</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Message <span class="text-crimson-500">*</span></label>
                <textarea wire:model="message" rows="5" placeholder="Write your message here (minimum 20 characters)..."
                    class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 placeholder:text-zinc-400 transition focus:border-brand-400 focus:outline-none focus:ring-2 focus:ring-brand-400/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100 dark:placeholder:text-zinc-500"></textarea>
                @error('message') <p class="mt-1 text-xs text-crimson-500">{{ $message }}</p> @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 rounded-xl bg-crimson-700 px-7 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-crimson-800 disabled:opacity-60 dark:bg-crimson-700 dark:hover:bg-crimson-600">
                <span wire:loading.remove wire:target="submit">Send Inquiry</span>
                <span wire:loading wire:target="submit" class="flex items-center gap-2">
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
