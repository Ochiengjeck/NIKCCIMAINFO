<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ $platform->name }}</flux:heading>
            <flux:subheading>Platform details and interaction log</flux:subheading>
        </div>
        <flux:button href="{{ route('admin.platforms.index') }}" wire:navigate variant="ghost" icon="arrow-left">Back to Platforms</flux:button>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Platform Info --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-4">Platform Details</flux:heading>
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs font-medium uppercase text-zinc-500">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                            {{ $platform->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-zinc-100 text-zinc-600' }}">
                            {{ ucfirst($platform->status) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-zinc-500">Coordinator</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-white">{{ $platform->coordinator?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-zinc-500">Description</dt>
                    <dd class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ $platform->description ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase text-zinc-500">Created</dt>
                    <dd class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">{{ $platform->created_at->format('d M Y') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Add Interaction Form --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-4">Log Interaction</flux:heading>
            <form wire:submit="addInteraction" class="space-y-4">
                <flux:field>
                    <flux:label>Interaction Type <span class="text-red-500">*</span></flux:label>
                    <flux:input wire:model="interactionType" placeholder="e.g. Meeting, Workshop, Report" />
                    <flux:error name="interactionType" />
                </flux:field>
                <flux:field>
                    <flux:label>Notes</flux:label>
                    <flux:textarea wire:model="notes" rows="3" placeholder="Describe the interaction…" />
                    <flux:error name="notes" />
                </flux:field>
                <flux:button type="submit" variant="primary" icon="plus">Log Interaction</flux:button>
            </form>
        </div>

        {{-- Interaction Log --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-4">Interaction History</flux:heading>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($platform->interactions->sortByDesc('logged_at') as $interaction)
                    <div class="rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                        <div class="flex items-start justify-between">
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $interaction->interaction_type }}</span>
                            <span class="text-xs text-zinc-400">{{ $interaction->logged_at->format('d M Y') }}</span>
                        </div>
                        @if($interaction->notes)
                            <p class="mt-1 text-xs text-zinc-600 dark:text-zinc-400">{{ $interaction->notes }}</p>
                        @endif
                        <p class="mt-1 text-xs text-zinc-400">by {{ $interaction->logger?->name ?? 'Unknown' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-zinc-500">No interactions logged yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
