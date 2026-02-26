<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Events & Trade Missions</flux:heading>
            <flux:subheading>Manage NiKCCIMA flagship events and trade missions</flux:subheading>
        </div>
        @can('events.create')
            <flux:button icon="plus" wire:click="create">New Event</flux:button>
        @endcan
    </div>
    @if(session('success'))<flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>@endif
    @if($showForm)
    <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:heading size="lg" class="mb-4">{{ $editingId ? 'Edit Event' : 'New Event' }}</flux:heading>
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @if($canSelectChapter)
            <flux:field>
                <flux:label>Chapter</flux:label>
                <flux:select wire:model="chapter_id">
                    <option value="">Select chapter</option>
                    @foreach($chapters as $chapter)
                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="chapter_id" />
            </flux:field>
            @endif
            <flux:field class="sm:col-span-2"><flux:label>Title</flux:label><flux:input wire:model="title" /><flux:error name="title" /></flux:field>
            <flux:field><flux:label>Type</flux:label><flux:select wire:model="type"><option value="flagship">Flagship</option><option value="trade-mission">Trade Mission</option><option value="sector-forum">Sector Forum</option></flux:select></flux:field>
            <flux:field><flux:label>Status</flux:label><flux:select wire:model="status"><option value="draft">Draft</option><option value="published">Published</option><option value="ongoing">Ongoing</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option></flux:select></flux:field>
            <flux:field><flux:label>Starts At</flux:label><flux:input type="datetime-local" wire:model="starts_at" /><flux:error name="starts_at" /></flux:field>
            <flux:field><flux:label>Ends At</flux:label><flux:input type="datetime-local" wire:model="ends_at" /><flux:error name="ends_at" /></flux:field>
            <flux:field><flux:label>Venue</flux:label><flux:input wire:model="venue" /></flux:field>
            <flux:field><flux:label>Max Capacity</flux:label><flux:input type="number" wire:model="max_capacity" /></flux:field>
            <flux:field class="sm:col-span-2"><flux:label>Description</flux:label><flux:textarea wire:model="description" rows="3" /></flux:field>
            <div class="sm:col-span-2 flex gap-3"><flux:button type="submit" variant="primary">Save</flux:button><flux:button wire:click="cancel" variant="ghost">Cancel</flux:button></div>
        </form>
    </div>
    @endif
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Starts</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Venue</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($events as $event)
                <tr>
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $event->title }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ ucwords(str_replace('-',' ',$event->type)) }}</td>
                    <td class="px-4 py-3">
                        @php $sc = match($event->status) { 'published'=>'bg-green-100 text-green-800','ongoing'=>'bg-blue-100 text-blue-800','completed'=>'bg-zinc-100 text-zinc-600','cancelled'=>'bg-red-100 text-red-800',default=>'bg-yellow-100 text-yellow-800' }; @endphp
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $sc }}">{{ ucfirst($event->status) }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $event->starts_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $event->venue ?? '—' }}</td>
                    <td class="px-4 py-3 text-right">@can('events.edit')<flux:button size="sm" wire:click="edit({{ $event->id }})">Edit</flux:button>@endcan</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500">No events yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
</div>
