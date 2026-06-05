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

    @if(session('success'))
        <flux:callout variant="success" class="mb-4" dismissible>{{ session('success') }}</flux:callout>
    @endif

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
                <flux:field class="sm:col-span-2">
                    <flux:label>Attached file <span class="text-zinc-400 font-normal">(optional — PDF shown publicly when published)</span></flux:label>
                    <livewire:components.media-picker
                        wire:model="file_media_item_id"
                        disk="local"
                        type="document"
                        folder="policy-briefs"
                        accept=".pdf,.doc,.docx"
                        key="brief-file-picker"
                    />
                    <flux:error name="file_media_item_id" />
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
                        <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">
                            {{ $brief->title }}
                            @if($brief->file)
                                <span class="ml-1 inline-flex items-center gap-1 rounded bg-zinc-100 px-1.5 py-0.5 text-[10px] font-medium text-zinc-600 dark:bg-zinc-700 dark:text-zinc-300" title="{{ $brief->file->original_filename }}">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    File
                                </span>
                            @endif
                        </td>
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
                        <td class="px-4 py-3 text-right">
                            <flux:dropdown position="bottom" align="end">
                                <flux:button size="sm" variant="ghost" icon="ellipsis-horizontal" />
                                <flux:menu>
                                    <flux:menu.item :href="route('admin.policy.briefs.show', $brief)" wire:navigate icon="eye">View</flux:menu.item>
                                    @can('policy.edit')
                                        <flux:menu.item wire:click="openForm({{ $brief->id }})" icon="pencil-square">Edit</flux:menu.item>
                                    @endcan
                                    @can('policy.publish')
                                        @if($brief->status !== 'published')
                                            <flux:menu.item wire:click="publish({{ $brief->id }})" icon="megaphone">Publish</flux:menu.item>
                                        @else
                                            <flux:menu.item wire:click="unpublish({{ $brief->id }})" icon="eye-slash">Unpublish</flux:menu.item>
                                        @endif
                                    @endcan
                                    @can('policy.delete')
                                        <flux:menu.separator />
                                        <flux:menu.item wire:click="destroy({{ $brief->id }})" wire:confirm="Delete this policy brief permanently?" icon="trash" variant="danger">Delete</flux:menu.item>
                                    @endcan
                                </flux:menu>
                            </flux:dropdown>
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
