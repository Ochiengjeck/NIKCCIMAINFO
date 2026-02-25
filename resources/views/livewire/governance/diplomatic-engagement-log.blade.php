<div>
    <div class="flex items-center justify-between mb-6">
        <div><flux:heading size="xl">Government Liaison Tracker</flux:heading><flux:subheading>Log diplomatic meetings and stakeholder engagements</flux:subheading></div>
        @can('governance.upload')<flux:button icon="plus" wire:click="$set('showForm',true)">Log Engagement</flux:button>@endcan
    </div>
    @if(session('success'))<flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>@endif
    @if($showForm)
    <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <flux:field><flux:label>Ministry / Body</flux:label><flux:input wire:model="ministry_or_body" /><flux:error name="ministry_or_body" /></flux:field>
            <flux:field><flux:label>Contact Name</flux:label><flux:input wire:model="contact_name" /></flux:field>
            <flux:field><flux:label>Date of Meeting</flux:label><flux:input type="date" wire:model="date" /><flux:error name="date" /></flux:field>
            <flux:field><flux:label>Follow-up Date</flux:label><flux:input type="date" wire:model="follow_up_date" /></flux:field>
            <flux:field class="sm:col-span-2"><flux:label>Purpose</flux:label><flux:textarea wire:model="purpose" rows="2" /><flux:error name="purpose" /></flux:field>
            <flux:field class="sm:col-span-2"><flux:label>Outcome</flux:label><flux:textarea wire:model="outcome" rows="2" /></flux:field>
            <div class="sm:col-span-2 flex gap-3"><flux:button type="submit" variant="primary">Save</flux:button><flux:button wire:click="$set('showForm',false)" variant="ghost">Cancel</flux:button></div>
        </form>
    </div>
    @endif
    <div class="space-y-3">
        @forelse($engagements as $eng)
        <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $eng->ministry_or_body }}</p>
                    <p class="text-sm text-zinc-500">{{ $eng->date->format('d M Y') }} @if($eng->contact_name) &bull; {{ $eng->contact_name }}@endif</p>
                </div>
                @if($eng->follow_up_date)<span class="text-xs text-orange-600">Follow-up: {{ $eng->follow_up_date->format('d M Y') }}</span>@endif
            </div>
            <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300"><strong>Purpose:</strong> {{ $eng->purpose }}</p>
            @if($eng->outcome)<p class="mt-1 text-sm text-zinc-700 dark:text-zinc-300"><strong>Outcome:</strong> {{ $eng->outcome }}</p>@endif
            <p class="mt-2 text-xs text-zinc-400">Logged by {{ $eng->loggedBy?->name }}</p>
        </div>
        @empty
        <p class="text-center text-zinc-500 py-8">No engagements logged yet.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $engagements->links() }}</div>
</div>
