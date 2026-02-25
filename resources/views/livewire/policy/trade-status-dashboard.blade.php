<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Trade Status Reviews</flux:heading>
            <flux:subheading>Periodic bilateral trade performance reviews</flux:subheading>
        </div>
        @can('policy.create')
            <flux:button variant="primary" icon="plus" wire:click="$toggle('showForm')">Add Review</flux:button>
        @endcan
    </div>

    {{-- Form --}}
    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 p-6">
            <flux:heading size="sm" class="mb-4">New Trade Status Review</flux:heading>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Period</flux:label>
                    <flux:input wire:model="period" placeholder="e.g. 2026-Q1" />
                    <flux:error name="period" />
                </flux:field>
                <flux:field>
                    <flux:label>Bilateral Trade Data (JSON)</flux:label>
                    <flux:input wire:model="bilateral_trade_data" placeholder='{"export": 1000000}' />
                    <flux:error name="bilateral_trade_data" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Notes</flux:label>
                    <flux:textarea wire:model="notes" rows="4" placeholder="Observations and analysis…" />
                    <flux:error name="notes" />
                </flux:field>
            </div>
            <div class="mt-4 flex gap-3">
                <flux:button wire:click="save" variant="primary">Save Review</flux:button>
                <flux:button wire:click="$set('showForm', false)" variant="ghost">Cancel</flux:button>
            </div>
        </div>
    @endif

    {{-- Reviews List --}}
    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
                <div class="flex items-center justify-between mb-2">
                    <flux:heading size="sm">{{ $review->period }}</flux:heading>
                    <span class="text-xs text-zinc-400">{{ $review->created_at?->format('d M Y') }}</span>
                </div>
                @if($review->notes)
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3">{{ $review->notes }}</p>
                @endif
                @if($review->bilateral_trade_data)
                    <div class="rounded bg-zinc-50 dark:bg-zinc-800 p-3 font-mono text-xs text-zinc-600 dark:text-zinc-300">
                        {{ json_encode($review->bilateral_trade_data, JSON_PRETTY_PRINT) }}
                    </div>
                @endif
            </div>
        @empty
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-8 text-center text-zinc-500">
                No trade status reviews yet.
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $reviews->links() }}</div>
</div>
