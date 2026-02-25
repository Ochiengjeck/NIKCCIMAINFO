<div>
    <div class="flex items-center justify-between mb-6">
        <div><flux:heading size="xl">Event Sponsors</flux:heading><flux:subheading>Track sponsorship agreements per event</flux:subheading></div>
        @can('events.edit')<flux:button icon="plus" wire:click="$set('showForm',true)">Add Sponsor</flux:button>@endcan
    </div>
    @if(session('success'))<flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>@endif
    @if($showForm)
    <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <flux:field><flux:label>Event</flux:label><flux:select wire:model="event_id"><option value="">Select event…</option>@foreach($events as $e)<option value="{{ $e->id }}">{{ $e->title }}</option>@endforeach</flux:select><flux:error name="event_id" /></flux:field>
            <flux:field><flux:label>Sponsor Name</flux:label><flux:input wire:model="sponsor_name" /><flux:error name="sponsor_name" /></flux:field>
            <flux:field><flux:label>Tier</flux:label><flux:select wire:model="tier"><option value="title">Title</option><option value="gold">Gold</option><option value="silver">Silver</option><option value="bronze">Bronze</option></flux:select></flux:field>
            <flux:field><flux:label>Amount (USD)</flux:label><flux:input type="number" wire:model="amount" step="0.01" /></flux:field>
            <div class="sm:col-span-2 flex gap-3"><flux:button type="submit" variant="primary">Save</flux:button><flux:button wire:click="$set('showForm',false)" variant="ghost">Cancel</flux:button></div>
        </form>
    </div>
    @endif
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Sponsor</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Event</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Tier</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Amount</th>
            </tr></thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($sponsors as $s)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $s->sponsor_name }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $s->event?->title }}</td>
                    <td class="px-4 py-3"><span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800">{{ ucfirst($s->tier) }}</span></td>
                    <td class="px-4 py-3 text-sm">{{ $s->amount ? '$'.number_format($s->amount,2) : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-zinc-500">No sponsors yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $sponsors->links() }}</div>
</div>
