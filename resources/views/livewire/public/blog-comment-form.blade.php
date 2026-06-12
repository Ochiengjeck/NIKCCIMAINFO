<div>
    @if($submitted)
        <div class="rounded-xl border border-green-200 bg-green-50 p-5 text-sm text-green-800">
            Thank you — your comment has been submitted and is awaiting moderation.
        </div>
    @endif

    <form wire:submit="submit" class="space-y-4">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-zinc-700">Name <span class="text-crimson-600">*</span></label>
                <input type="text" wire:model="name"
                    class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-brand-500" />
                @error('name') <p class="mt-1 text-xs text-crimson-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-zinc-700">Email <span class="text-crimson-600">*</span></label>
                <input type="email" wire:model="email"
                    class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-brand-500" />
                <p class="mt-1 text-xs text-zinc-400">Your email will not be published.</p>
                @error('email') <p class="mt-1 text-xs text-crimson-600">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-zinc-700">Comment <span class="text-crimson-600">*</span></label>
            <textarea wire:model="body" rows="4"
                class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
            @error('body') <p class="mt-1 text-xs text-crimson-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">
            <span wire:loading.remove wire:target="submit">Post Comment</span>
            <span wire:loading wire:target="submit">Posting…</span>
        </button>
    </form>
</div>
