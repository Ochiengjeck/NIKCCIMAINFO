<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Membership Categories</flux:heading>
            <flux:subheading>Manage membership tiers and fee structures</flux:subheading>
        </div>
        @can('settings.edit')
            <flux:button icon="plus" wire:click="create">New Category</flux:button>
        @endcan
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>
    @endif

    {{-- Form --}}
    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-4">{{ $editingId ? 'Edit Category' : 'New Category' }}</flux:heading>
            <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Name</flux:label>
                    <flux:input wire:model.live="name" placeholder="e.g. Corporate Member" />
                    <flux:error name="name" />
                </flux:field>
                <flux:field>
                    <flux:label>Slug</flux:label>
                    <flux:input wire:model="slug" placeholder="corporate-member" />
                    <flux:error name="slug" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="description" rows="2" />
                    <flux:error name="description" />
                </flux:field>
                <flux:field>
                    <flux:label>Fee (NGN)</flux:label>
                    <flux:input type="number" wire:model="fee_ngn" step="0.01" />
                    <flux:error name="fee_ngn" />
                </flux:field>
                <flux:field>
                    <flux:label>Fee (KES)</flux:label>
                    <flux:input type="number" wire:model="fee_kes" step="0.01" />
                    <flux:error name="fee_kes" />
                </flux:field>
                <flux:field>
                    <flux:label>Sort Order</flux:label>
                    <flux:input type="number" wire:model="sort_order" />
                </flux:field>
                <flux:field class="flex items-end">
                    <flux:checkbox wire:model="is_active" label="Active" />
                </flux:field>
                <div class="sm:col-span-2 flex gap-3">
                    <flux:button type="submit" variant="primary">Save</flux:button>
                    <flux:button wire:click="cancel" variant="ghost">Cancel</flux:button>
                </div>
            </form>
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Slug</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Fee NGN</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Fee KES</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($categories as $cat)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $cat->name }}</td>
                        <td class="px-4 py-3 text-zinc-500 text-sm font-mono">{{ $cat->slug }}</td>
                        <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">{{ number_format($cat->fee_ngn, 2) }}</td>
                        <td class="px-4 py-3 text-zinc-700 dark:text-zinc-300">{{ number_format($cat->fee_kes, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $cat->is_active ? 'bg-green-100 text-green-800' : 'bg-zinc-100 text-zinc-600' }}">
                                {{ $cat->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @can('settings.edit')
                                <flux:button size="sm" wire:click="edit({{ $cat->id }})">Edit</flux:button>
                                <flux:button size="sm" variant="ghost" wire:click="toggleActive({{ $cat->id }})">
                                    {{ $cat->is_active ? 'Deactivate' : 'Activate' }}
                                </flux:button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No categories yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
</div>
