<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">Blog Categories</flux:heading>
            <flux:subheading>Organise posts into categories</flux:subheading>
        </div>
        <div class="flex items-center gap-2">
            <flux:button :href="route('admin.cms.blog')" wire:navigate variant="ghost" icon="arrow-left">Back to Posts</flux:button>
            @can('cms.edit')
                <flux:button wire:click="openForm()" variant="primary" icon="plus">New Category</flux:button>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-5">{{ $editingId ? 'Edit Category' : 'New Category' }}</flux:heading>

            <form wire:submit="save" class="space-y-5">
                <div class="grid gap-4 lg:grid-cols-2">
                    <flux:field>
                        <flux:label>Name <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="name" placeholder="Category name" />
                        <flux:error name="name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Sort Order</flux:label>
                        <flux:input type="number" min="0" wire:model="sortOrder" />
                        <flux:error name="sortOrder" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea wire:model="description" rows="2" placeholder="Optional description" />
                    <flux:error name="description" />
                </flux:field>

                <div class="flex gap-2 pt-2">
                    <flux:button type="submit" variant="primary" icon="check">Save Category</flux:button>
                    <flux:button wire:click="closeForm()" variant="ghost">Cancel</flux:button>
                </div>
            </form>
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Slug</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Posts</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Order</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-zinc-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($categories as $category)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $category->name }}</p>
                            @if($category->description)
                                <p class="text-xs text-zinc-500">{{ \Illuminate\Support\Str::limit($category->description, 60) }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-zinc-500">{{ $category->slug }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $category->posts_count }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $category->sort_order }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                @can('cms.edit')
                                    <flux:button wire:click="openForm({{ $category->id }})" size="sm" variant="ghost" icon="pencil" />
                                    <flux:button wire:click="delete({{ $category->id }})" wire:confirm="Delete this category? Its posts become uncategorised." size="sm" variant="ghost" icon="trash" class="text-red-500" />
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm text-zinc-500">No categories yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
