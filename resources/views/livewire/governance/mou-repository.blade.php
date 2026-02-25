<div>
    <div class="flex items-center justify-between mb-6">
        <div><flux:heading size="xl">MoU Repository</flux:heading><flux:subheading>Track bilateral agreements and memoranda of understanding</flux:subheading></div>
        @can('governance.upload')<flux:button icon="plus" wire:click="$set('showForm',true)">New MoU</flux:button>@endcan
    </div>
    @if(session('success'))<flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>@endif
    @if($showForm)
    <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <flux:field class="sm:col-span-2"><flux:label>Title</flux:label><flux:input wire:model="title" /><flux:error name="title" /></flux:field>
            <flux:field><flux:label>Partner Name</flux:label><flux:input wire:model="partner_name" /><flux:error name="partner_name" /></flux:field>
            <flux:field><flux:label>Partner Type</flux:label><flux:select wire:model="partner_type"><option value="government">Government</option><option value="private">Private</option><option value="bilateral">Bilateral</option></flux:select></flux:field>
            <flux:field><flux:label>Signed Date</flux:label><flux:input type="date" wire:model="signed_at" /></flux:field>
            <flux:field><flux:label>Expiry Date</flux:label><flux:input type="date" wire:model="expiry_at" /></flux:field>
            <flux:field><flux:label>Status</flux:label><flux:select wire:model="status"><option value="under-negotiation">Under Negotiation</option><option value="active">Active</option><option value="expired">Expired</option></flux:select></flux:field>
            <div class="sm:col-span-2 flex gap-3"><flux:button type="submit" variant="primary">Save</flux:button><flux:button wire:click="$set('showForm',false)" variant="ghost">Cancel</flux:button></div>
        </form>
    </div>
    @endif
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Title</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Partner</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Type</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Signed</th>
                <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Expires</th>
            </tr></thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($mous as $mou)
                <tr>
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $mou->title }}</td>
                    <td class="px-4 py-3 text-sm">{{ $mou->partner_name }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ ucfirst($mou->partner_type) }}</td>
                    <td class="px-4 py-3">
                        @php $sc = match($mou->status){'active'=>'bg-green-100 text-green-800','expired'=>'bg-red-100 text-red-800',default=>'bg-yellow-100 text-yellow-800'}; @endphp
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $sc }}">{{ ucwords(str_replace('-',' ',$mou->status)) }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $mou->signed_at?->format('d M Y') ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $mou->expiry_at?->format('d M Y') ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500">No MoUs recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $mous->links() }}</div>
</div>
