<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Non-Tariff Barriers (NTBs)</flux:heading>
            <flux:subheading>Track and resolve trade barriers affecting the corridor</flux:subheading>
        </div>
        @can('policy.create')
            <flux:button variant="primary" icon="plus" wire:click="openForm()">Report NTB</flux:button>
        @endcan
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row">
        <flux:input wire:model.live="search" placeholder="Search title or description…" icon="magnifying-glass" class="sm:w-72" />
        <flux:select wire:model.live="statusFilter" class="sm:w-44">
            <option value="">All Statuses</option>
            <option value="submitted">Submitted</option>
            <option value="escalated">Escalated</option>
            <option value="under-review">Under Review</option>
            <option value="resolved">Resolved</option>
            <option value="closed">Closed</option>
        </flux:select>
    </div>

    {{-- Form --}}
    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 p-6">
            <flux:heading size="sm" class="mb-4">{{ $editingId ? 'Edit NTB' : 'Report New NTB' }}</flux:heading>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:field class="sm:col-span-2">
                    <flux:label>Title</flux:label>
                    <flux:input wire:model="title" placeholder="Short description of the barrier" />
                    <flux:error name="title" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="description" rows="4" placeholder="Detailed description of the non-tariff barrier…" />
                    <flux:error name="description" />
                </flux:field>
                <flux:field>
                    <flux:label>Product/Sector</flux:label>
                    <flux:input wire:model="product_sector" placeholder="e.g. Agricultural goods" />
                    <flux:error name="product_sector" />
                </flux:field>
                <flux:field>
                    <flux:label>Barrier Type</flux:label>
                    <flux:input wire:model="barrier_type" placeholder="e.g. Import quota, Licensing" />
                    <flux:error name="barrier_type" />
                </flux:field>
                <flux:field>
                    <flux:label>Status</flux:label>
                    <flux:select wire:model="status">
                        <option value="submitted">Submitted</option>
                        <option value="escalated">Escalated</option>
                        <option value="under-review">Under Review</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </flux:select>
                    <flux:error name="status" />
                </flux:field>
            </div>
            <div class="mt-4 flex gap-3">
                <flux:button wire:click="save" variant="primary">Save</flux:button>
                <flux:button wire:click="closeForm" variant="ghost">Cancel</flux:button>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Barrier Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Submitter</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Submitted</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($ntbs as $ntb)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $ntb->title }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $ntb->barrier_type ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                @switch($ntb->status)
                                    @case('submitted') bg-zinc-100 text-zinc-700 @break
                                    @case('escalated') bg-red-100 text-red-800 @break
                                    @case('under-review') bg-yellow-100 text-yellow-800 @break
                                    @case('resolved') bg-green-100 text-green-800 @break
                                    @case('closed') bg-zinc-200 text-zinc-600 @break
                                @endswitch
                            ">
                                {{ ucfirst(str_replace('-', ' ', $ntb->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $ntb->submitter?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $ntb->created_at?->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            @can('policy.edit')
                                <flux:button size="sm" variant="ghost" wire:click="openForm({{ $ntb->id }})">Edit</flux:button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No NTBs reported.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $ntbs->links() }}</div>
</div>
