<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <flux:heading size="xl">News</flux:heading>
            <flux:subheading>Manage news articles, categories and comments</flux:subheading>
        </div>
        <div class="flex items-center gap-2">
            <flux:button :href="route('admin.cms.news.categories')" wire:navigate variant="ghost" icon="tag">Categories</flux:button>
            <flux:button :href="route('admin.cms.news.comments')" wire:navigate variant="ghost" icon="chat-bubble-left-right">Comments</flux:button>
            @can('cms.edit')
                <flux:button wire:click="openForm()" variant="primary" icon="plus">New Article</flux:button>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    {{-- Article Form --}}
    @if($showForm)
        <div class="mb-6 rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-5">{{ $editingId ? 'Edit Article' : 'New Article' }}</flux:heading>

            <form wire:submit="save" class="space-y-5">
                <div class="grid gap-4 lg:grid-cols-2">
                    <flux:field>
                        <flux:label>Title <span class="text-red-500">*</span></flux:label>
                        <flux:input wire:model="title" placeholder="Article title" />
                        <flux:error name="title" />
                    </flux:field>

                    <div class="grid grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label>Category</flux:label>
                            <flux:select wire:model="newsCategoryId" placeholder="Uncategorised">
                                <flux:select.option value="">Uncategorised</flux:select.option>
                                @foreach($categories as $category)
                                    <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="newsCategoryId" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Status</flux:label>
                            <flux:select wire:model="status">
                                <flux:select.option value="draft">Draft</flux:select.option>
                                <flux:select.option value="published">Published</flux:select.option>
                            </flux:select>
                        </flux:field>
                    </div>
                </div>

                <flux:field>
                    <flux:label>Tags</flux:label>
                    <flux:input wire:model="tagsInput" placeholder="Comma-separated, e.g. afcfta, exports, kenya" />
                    <flux:description>New tags are created automatically.</flux:description>
                    <flux:error name="tagsInput" />
                </flux:field>

                <flux:field>
                    <flux:label>Excerpt</flux:label>
                    <flux:textarea wire:model="excerpt" rows="2" placeholder="Short summary shown in listings (max 500 chars)" />
                    <flux:error name="excerpt" />
                </flux:field>

                <flux:field>
                    <flux:label>Body <span class="text-red-500">*</span></flux:label>
                    <flux:textarea wire:model="body" rows="12" placeholder="Full article content (HTML supported)" />
                    <flux:error name="body" />
                </flux:field>

                <flux:field>
                    <flux:label>Featured Image</flux:label>
                    <livewire:components.media-picker
                        wire:model="featuredImageId"
                        disk="public"
                        type="image"
                        folder="cms/news"
                        accept="image/*"
                        :key="'news-picker-' . ($editingId ?? 'new')"
                    />
                    <flux:error name="featuredImageId" />
                </flux:field>

                <div class="flex gap-2 pt-2">
                    <flux:button type="submit" variant="primary" icon="check">Save Article</flux:button>
                    <flux:button wire:click="closeForm()" variant="ghost">Cancel</flux:button>
                </div>
            </form>
        </div>
    @endif

    {{-- Filters --}}
    <div class="mb-4 flex flex-wrap gap-3">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Search articles..." icon="magnifying-glass" class="max-w-xs" />
        <flux:select wire:model.live="statusFilter" class="w-36">
            <flux:select.option value="">All statuses</flux:select.option>
            <flux:select.option value="draft">Draft</flux:select.option>
            <flux:select.option value="published">Published</flux:select.option>
        </flux:select>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead class="bg-zinc-50 dark:bg-zinc-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wide text-zinc-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($articles as $article)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($article->featured_image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($article->featured_image) }}"
                                        class="h-9 w-9 flex-shrink-0 rounded-lg object-cover" alt="" />
                                @else
                                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                        <svg class="h-4 w-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $article->title }}</p>
                                    <p class="text-xs text-zinc-500">{{ $article->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full bg-zinc-100 px-2 py-0.5 text-xs text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                                {{ $article->category?->name ?? 'Uncategorised' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $article->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-700 dark:text-zinc-400' }}">
                                {{ ucfirst($article->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-zinc-500">
                            {{ $article->published_at?->format('d M Y') ?? $article->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                @can('cms.edit')
                                    <flux:button wire:click="openForm({{ $article->id }})" size="sm" variant="ghost" icon="pencil" />
                                    @if($article->status === 'draft')
                                        <flux:button wire:click="publish({{ $article->id }})" size="sm" variant="ghost">Publish</flux:button>
                                    @else
                                        <flux:button wire:click="unpublish({{ $article->id }})" size="sm" variant="ghost">Draft</flux:button>
                                    @endif
                                    <flux:button wire:click="delete({{ $article->id }})" wire:confirm="Delete this article?" size="sm" variant="ghost" icon="trash" class="text-red-500" />
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm text-zinc-500">No articles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $articles->links() }}</div>
    </div>
</div>
