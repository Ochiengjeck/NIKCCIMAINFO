<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">Chapters</flux:heading>
            <flux:subheading>Manage NiKCCIMA chapters</flux:subheading>
        </div>
        <flux:button wire:click="openCreate" icon="plus">New Chapter</flux:button>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    {{-- Form --}}
    @if($showForm)
        <div class="mb-6 max-w-lg rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-4">{{ $editingId ? 'Edit Chapter' : 'New Chapter' }}</flux:heading>
            <form wire:submit="save" class="space-y-4">
                <flux:field>
                    <flux:label>Chapter Name <span class="text-red-500">*</span></flux:label>
                    <flux:input wire:model="name" placeholder="e.g. Nigeria Chapter" />
                    <flux:error name="name" />
                </flux:field>
                <flux:field>
                    <flux:label>Code <span class="text-red-500">*</span></flux:label>
                    <flux:input wire:model="code" placeholder="e.g. NG" maxlength="10" />
                    <flux:error name="code" />
                    <p class="mt-1 text-xs text-zinc-500">Short alphanumeric code, max 10 characters (auto-uppercased)</p>
                </flux:field>
                <flux:field>
                    <div class="flex items-center gap-2">
                        <flux:checkbox wire:model="isActive" id="chapterActive" />
                        <label for="chapterActive" class="text-sm text-zinc-600 dark:text-zinc-400">Active</label>
                    </div>
                </flux:field>
                <div class="flex gap-2">
                    <flux:button type="submit" variant="primary">{{ $editingId ? 'Update' : 'Create' }} Chapter</flux:button>
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
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Members</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($chapters as $chapter)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $chapter->name }}</td>
                        <td class="px-4 py-3 font-mono text-sm text-zinc-600 dark:text-zinc-400">{{ $chapter->code }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $chapter->is_active ? 'bg-green-100 text-green-800' : 'bg-zinc-100 text-zinc-500' }}">
                                {{ $chapter->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $chapter->users->count() }} users</td>
                        <td class="px-4 py-3 text-right">
                            <flux:button size="sm" variant="ghost" wire:click="edit({{ $chapter->id }})">Edit</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-zinc-500">No chapters found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
