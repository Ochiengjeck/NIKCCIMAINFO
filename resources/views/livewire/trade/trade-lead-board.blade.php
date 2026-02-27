<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Trade Leads Board</flux:heading>
            <flux:subheading>Manage AfCFTA corridor trade opportunities</flux:subheading>
        </div>
        @can('trade.create')
            <flux:button variant="primary" icon="plus" wire:click="openForm()">New Lead</flux:button>
        @endcan
    </div>

    {{-- Form --}}
    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 bg-zinc-50 p-6 dark:border-zinc-700 dark:bg-zinc-800">
            <flux:heading size="sm" class="mb-4">New Trade Lead</flux:heading>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:field class="sm:col-span-2">
                    <flux:label>Title</flux:label>
                    <flux:input wire:model="title" placeholder="e.g. Maize export opportunity to Kenya" />
                    <flux:error name="title" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="description" rows="4" placeholder="Describe the opportunity and requirements..." />
                    <flux:error name="description" />
                </flux:field>
                <flux:field>
                    <flux:label>Sector</flux:label>
                    <flux:select wire:model="sector_id">
                        <option value="">Select sector...</option>
                        @foreach($sectors as $sector)
                            <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="sector_id" />
                </flux:field>
                <flux:field>
                    <flux:label>Member (Optional)</flux:label>
                    <flux:select wire:model="member_id">
                        <option value="">No linked member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="member_id" />
                </flux:field>
                <flux:field>
                    <flux:label>Type</flux:label>
                    <flux:select wire:model="type">
                        <option value="export">Export</option>
                        <option value="import">Import</option>
                        <option value="partnership">Partnership</option>
                    </flux:select>
                    <flux:error name="type" />
                </flux:field>
                <flux:field>
                    <flux:label>Status</flux:label>
                    <flux:select wire:model="status">
                        <option value="open">Open</option>
                        <option value="matched">Matched</option>
                        <option value="closed">Closed</option>
                    </flux:select>
                    <flux:error name="status" />
                </flux:field>
                <flux:field>
                    <flux:label>Contact Info</flux:label>
                    <flux:input wire:model="contact_info" placeholder="email@company.com / +234..." />
                    <flux:error name="contact_info" />
                </flux:field>
                <flux:field>
                    <flux:label>Expires At</flux:label>
                    <flux:input wire:model="expires_at" type="date" />
                    <flux:error name="expires_at" />
                </flux:field>
            </div>
            <div class="mt-4 flex gap-3">
                <flux:button wire:click="save" variant="primary">Save</flux:button>
                <flux:button wire:click="closeForm" variant="ghost">Cancel</flux:button>
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row">
        <flux:input wire:model.live="search" placeholder="Search title or description…" icon="magnifying-glass" class="sm:w-72" />
        <flux:select wire:model.live="typeFilter" class="sm:w-40">
            <option value="">All Types</option>
            <option value="export">Export</option>
            <option value="import">Import</option>
            <option value="partnership">Partnership</option>
        </flux:select>
        <flux:select wire:model.live="statusFilter" class="sm:w-40">
            <option value="">All Statuses</option>
            <option value="open">Open</option>
            <option value="matched">Matched</option>
            <option value="closed">Closed</option>
        </flux:select>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Sector</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Chapter</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Expires</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($leads as $lead)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $lead->title }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $lead->sector?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $lead->type === 'export' ? 'bg-blue-100 text-blue-800' : ($lead->type === 'import' ? 'bg-purple-100 text-purple-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ ucfirst($lead->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $lead->status === 'open' ? 'bg-green-100 text-green-800' : ($lead->status === 'matched' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $lead->chapter?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $lead->expires_at?->format('d M Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <flux:button size="sm" variant="ghost">View</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-zinc-500">No trade leads found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $leads->links() }}</div>
</div>
