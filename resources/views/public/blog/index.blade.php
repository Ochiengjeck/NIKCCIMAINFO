<x-layouts::website
    :title="$heading . ' — NiKCCIMA Blog'"
    :meta-description="'Latest news, insights and announcements from NiKCCIMA and the Nigeria-Kenya AfCFTA trade corridor.'">

    {{-- Hero --}}
    <section class="relative overflow-hidden py-32 text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-800 to-crimson-950">
            <div class="absolute inset-0 bg-zinc-950/60"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-serif text-4xl font-bold lg:text-6xl mb-4">{{ $heading }}</h1>
            <p class="max-w-2xl mx-auto text-lg text-zinc-300">Insights, news and announcements from NiKCCIMA and the AfCFTA trade corridor.</p>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if($activeCategory || $activeTag)
                <div class="mb-10 flex items-center gap-3 text-sm">
                    <a href="{{ route('blog.index') }}" class="font-medium text-brand-700 hover:underline">All posts</a>
                    <span class="text-zinc-300">/</span>
                    <span class="text-zinc-500">{{ $activeCategory?->name ?? '#'.$activeTag?->name }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">

                {{-- Main column --}}
                <div class="lg:col-span-2">
                    @if($articles->isEmpty())
                        <div class="flex h-64 flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-zinc-50">
                            <svg class="mb-4 h-10 w-10 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-4.5-4.5A2 2 0 0015.5 3H15"/>
                            </svg>
                            <p class="text-sm text-zinc-500">No posts published yet. Check back soon.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                            @foreach($articles as $article)
                                <article class="rounded bg-white shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:shadow-lg transition-all duration-500 overflow-hidden flex flex-col">
                                    <a href="{{ route('blog.show', $article->slug) }}" class="block overflow-hidden">
                                        @if($article->featuredImageUrl())
                                            <img src="{{ $article->featuredImageUrl() }}" alt="{{ $article->title }}" class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-48 bg-gradient-to-br from-brand-600 to-brand-800 flex items-center justify-center">
                                                <svg class="h-12 w-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-4.5-4.5A2 2 0 0015.5 3H15"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </a>

                                    <div class="p-6 flex flex-col flex-1">
                                        <div class="flex items-center gap-3 mb-3">
                                            @if($article->category)
                                                <a href="{{ route('blog.category', $article->category->slug) }}" class="text-xs font-semibold uppercase tracking-widest text-crimson-700 bg-crimson-50 px-2 py-0.5 rounded hover:bg-crimson-100">{{ $article->category->name }}</a>
                                            @endif
                                            <span class="text-xs text-zinc-400">{{ $article->published_at?->format('d M Y') }}</span>
                                        </div>

                                        <h4 class="text-lg font-bold font-serif text-zinc-900 mb-3 line-clamp-2 leading-snug">
                                            <a href="{{ route('blog.show', $article->slug) }}" class="hover:text-crimson-700 transition-colors">{{ $article->title }}</a>
                                        </h4>

                                        @if($article->excerpt)
                                            <p class="text-[15px] leading-[26px] text-zinc-600 line-clamp-3 flex-1">{{ $article->excerpt }}</p>
                                        @endif

                                        <div class="mt-5 flex items-center justify-between">
                                            <a href="{{ route('blog.show', $article->slug) }}" class="text-sm font-medium text-brand-600 hover:text-crimson-700 transition-colors">Read More &rarr;</a>
                                            <span class="text-xs text-zinc-400">{{ $article->reading_time }} min read</span>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-12">{{ $articles->links() }}</div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <aside class="space-y-10">
                    {{-- Categories --}}
                    @if($categories->isNotEmpty())
                        <div>
                            <h3 class="mb-4 font-serif text-lg font-bold text-zinc-900">Categories</h3>
                            <ul class="space-y-1">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('blog.category', $category->slug) }}"
                                           class="flex items-center justify-between rounded-lg px-3 py-2 text-sm transition
                                                {{ $activeCategory?->id === $category->id ? 'bg-brand-50 font-semibold text-brand-700' : 'text-zinc-600 hover:bg-zinc-50' }}">
                                            <span>{{ $category->name }}</span>
                                            <span class="text-xs text-zinc-400">{{ $category->published_posts_count }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Recent --}}
                    @if($recent->isNotEmpty())
                        <div>
                            <h3 class="mb-4 font-serif text-lg font-bold text-zinc-900">Recent Posts</h3>
                            <ul class="space-y-3">
                                @foreach($recent as $post)
                                    <li>
                                        <a href="{{ route('blog.show', $post->slug) }}" class="group block">
                                            <p class="text-sm font-medium text-zinc-800 group-hover:text-crimson-700 line-clamp-2">{{ $post->title }}</p>
                                            <p class="text-xs text-zinc-400">{{ $post->published_at?->format('d M Y') }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Tags --}}
                    @if($tags->isNotEmpty())
                        <div>
                            <h3 class="mb-4 font-serif text-lg font-bold text-zinc-900">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}"
                                       class="rounded-full px-3 py-1 text-xs transition
                                            {{ $activeTag?->id === $tag->id ? 'bg-brand-600 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- RSS --}}
                    <div>
                        <a href="{{ route('blog.feed') }}" class="inline-flex items-center gap-2 text-sm font-medium text-brand-700 hover:text-crimson-700">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M4 11a9 9 0 019 9h-2.5A6.5 6.5 0 004 13.5V11zm0-5a14 14 0 0114 14h-2.5A11.5 11.5 0 004 8.5V6zm1.5 9a2 2 0 110 4 2 2 0 010-4z"/></svg>
                            RSS Feed
                        </a>
                    </div>
                </aside>

            </div>
        </div>
    </section>

</x-layouts::website>
