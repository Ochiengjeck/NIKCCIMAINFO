<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">Technical Platforms</flux:heading>
            <flux:subheading>Manage joint technical collaboration platforms</flux:subheading>
        </div>
        <flux:button wire:click="openCreate" icon="plus">New Platform</flux:button>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    {{-- Form --}}
    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-4">{{ $editingId ? 'Edit Platform' : 'New Platform' }}</flux:heading>
            <form wire:submit="save" class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Platform Name <span class="text-red-500">*</span></flux:label>
                    <flux:input wire:model="name" placeholder="e.g. AfCFTA Trade Intelligence Hub" />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Status</flux:label>
                    <flux:select wire:model="status">
                        <flux:select.option value="active">Active</flux:select.option>
                        <flux:select.option value="inactive">Inactive</flux:select.option>
                    </flux:select>
                    <flux:error name="status" />
                </flux:field>

                <flux:field>
                    <flux:label>Coordinator</flux:label>
                    <flux:select wire:model="coordinatorId">
                        <flux:select.option value="">— None —</flux:select.option>
                        @foreach($coordinators as $user)
                            <flux:select.option value="{{ $user->id }}">{{ $user->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:field>

                <div class="sm:col-span-2">
                    <flux:field>
                        <flux:label>Description</flux:label>
                        <flux:textarea wire:model="description" rows="3" placeholder="Brief description of the platform's purpose" />
                    </flux:field>
                </div>

                <div class="sm:col-span-2 flex gap-2">
                    <flux:button type="submit" variant="primary">{{ $editingId ? 'Update' : 'Create' }} Platform</flux:button>
                    <flux:button wire:click="$set('showForm', false)" variant="ghost">Cancel</flux:button>
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
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Coordinator</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Description</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($platforms as $platform)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $platform->name }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $platform->coordinator?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $platform->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-zinc-100 text-zinc-600' }}">
                                {{ ucfirst($platform->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500 max-w-xs truncate">{{ $platform->description ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex gap-2 justify-end">
                                <flux:button size="sm" href="{{ route('admin.platforms.show', $platform) }}" wire:navigate>View</flux:button>
                                <flux:button size="sm" variant="ghost" wire:click="edit({{ $platform->id }})">Edit</flux:button>
                                <flux:button size="sm" variant="danger" wire:click="delete({{ $platform->id }})" wire:confirm="Delete this platform?">Delete</flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-zinc-500">No platforms found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $platforms->links() }}</div>
</div>
