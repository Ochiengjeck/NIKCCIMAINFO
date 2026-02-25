<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Anchor Investors</flux:heading>
            <flux:subheading>Manage investor pipeline and due diligence</flux:subheading>
        </div>
        @can('trade.create')
            <flux:button variant="primary" icon="plus" wire:click="openForm()">Add Investor</flux:button>
        @endcan
    </div>

    {{-- Form --}}
    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 p-6">
            <flux:heading size="sm" class="mb-4">{{ $editingId ? 'Edit Investor' : 'New Investor' }}</flux:heading>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Company Name</flux:label>
                    <flux:input wire:model="company_name" placeholder="Acme Corp" />
                    <flux:error name="company_name" />
                </flux:field>
                <flux:field>
                    <flux:label>Contact Name</flux:label>
                    <flux:input wire:model="contact_name" placeholder="John Doe" />
                    <flux:error name="contact_name" />
                </flux:field>
                <flux:field>
                    <flux:label>Email</flux:label>
                    <flux:input wire:model="email" type="email" placeholder="contact@example.com" />
                    <flux:error name="email" />
                </flux:field>
                <flux:field>
                    <flux:label>Phone</flux:label>
                    <flux:input wire:model="phone" placeholder="+234 800 000 0000" />
                    <flux:error name="phone" />
                </flux:field>
                <flux:field>
                    <flux:label>Sector</flux:label>
                    <flux:select wire:model="sector_id">
                        <option value="">Select sector…</option>
                        @foreach($sectors as $sector)
                            <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="sector_id" />
                </flux:field>
                <flux:field>
                    <flux:label>Investment Range</flux:label>
                    <flux:input wire:model="investment_range" placeholder="e.g. $1M–$5M" />
                    <flux:error name="investment_range" />
                </flux:field>
                <flux:field>
                    <flux:label>Status</flux:label>
                    <flux:select wire:model="status">
                        <option value="prospecting">Prospecting</option>
                        <option value="onboarded">Onboarded</option>
                        <option value="active">Active</option>
                    </flux:select>
                    <flux:error name="status" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Pipeline Notes</flux:label>
                    <flux:textarea wire:model="pipeline_notes" rows="3" placeholder="Notes on investor pipeline…" />
                    <flux:error name="pipeline_notes" />
                </flux:field>
            </div>
            <div class="mt-4 flex gap-3">
                <flux:button wire:click="save" variant="primary">Save</flux:button>
                <flux:button wire:click="closeForm" variant="ghost">Cancel</flux:button>
            </div>
        </div>
    @endif

    {{-- Search --}}
    <div class="mb-4">
        <flux:input wire:model.live="search" placeholder="Search company, contact, email…" icon="magnifying-glass" class="sm:w-72" />
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Company</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Contact</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Sector</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Range</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($investors as $investor)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $investor->company_name }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                            <div>{{ $investor->contact_name }}</div>
                            <div class="text-xs text-zinc-400">{{ $investor->email }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $investor->sector?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $investor->investment_range ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $investor->status === 'active' ? 'bg-green-100 text-green-800' : ($investor->status === 'onboarded' ? 'bg-blue-100 text-blue-800' : 'bg-zinc-100 text-zinc-700') }}">
                                {{ ucfirst($investor->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @can('trade.edit')
                                <flux:button size="sm" variant="ghost" wire:click="openForm({{ $investor->id }})">Edit</flux:button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No investors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $investors->links() }}</div>
</div>
