<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Policy Briefs</flux:heading>
            <flux:subheading>Research publications and policy position papers</flux:subheading>
        </div>
        @can('policy.create')
            <flux:button variant="primary" icon="plus" wire:click="openForm()">New Brief</flux:button>
        @endcan
    </div>

    {{-- Form --}}
    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 p-6">
            <flux:heading size="sm" class="mb-4">{{ $editingId ? 'Edit Policy Brief' : 'New Policy Brief' }}</flux:heading>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:field class="sm:col-span-2">
                    <flux:label>Title</flux:label>
                    <flux:input wire:model="title" placeholder="Brief title" />
                    <flux:error name="title" />
                </flux:field>
                <flux:field class="sm:col-span-2">
                    <flux:label>Body</flux:label>
                    <flux:textarea wire:model="body" rows="8" placeholder="Policy brief content…" />
                    <flux:error name="body" />
                </flux:field>
                <flux:field>
                    <flux:label>Reviewer</flux:label>
                    <flux:select wire:model="reviewer_id">
                        <option value="">No reviewer</option>
                        @foreach($reviewers as $reviewer)
                            <option value="{{ $reviewer->id }}">{{ $reviewer->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="reviewer_id" />
                </flux:field>
                <flux:field>
                    <flux:label>Status</flux:label>
                    <flux:select wire:model="status">
                        <option value="draft">Draft</option>
                        <option value="in-review">In Review</option>
                        <option value="approved">Approved</option>
                        <option value="published">Published</option>
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
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Author</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Reviewer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase text-zinc-500">Published</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                @forelse($briefs as $brief)
                    <tr>
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $brief->title }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $brief->author?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $brief->reviewer?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                @switch($brief->status)
                                    @case('draft') bg-zinc-100 text-zinc-700 @break
                                    @case('in-review') bg-yellow-100 text-yellow-800 @break
                                    @case('approved') bg-blue-100 text-blue-800 @break
                                    @case('published') bg-green-100 text-green-800 @break
                                @endswitch
                            ">
                                {{ ucfirst(str_replace('-', ' ', $brief->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-zinc-500">{{ $brief->published_at?->format('d M Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-right flex gap-2 justify-end">
                            @can('policy.edit')
                                <flux:button size="sm" variant="ghost" wire:click="openForm({{ $brief->id }})">Edit</flux:button>
                                @if($brief->status !== 'published')
                                    <flux:button size="sm" variant="primary" wire:click="publish({{ $brief->id }})">Publish</flux:button>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-zinc-500">No policy briefs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $briefs->links() }}</div>
</div>
