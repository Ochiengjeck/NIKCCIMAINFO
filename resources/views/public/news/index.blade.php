<x-layouts::website
    :title="'News — NiKCCIMA'"
    :meta-description="'Latest news, press releases and announcements from NiKCCIMA and the Nigeria-Kenya AfCFTA trade corridor.'">

    {{-- Hero --}}
    <section class="relative overflow-hidden py-32 text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-800 to-crimson-950">
            <div class="absolute inset-0 bg-zinc-950/60"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="font-serif text-4xl font-bold lg:text-6xl mb-4">News &amp; Updates</h1>
            <p class="max-w-2xl mx-auto text-lg text-zinc-300">Latest news, insights and updates from NiKCCIMA and the AfCFTA trade corridor.</p>
        </div>
    </section>

    {{-- Articles Grid --}}
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <span class="block text-xs font-bold uppercase tracking-widest text-crimson-700 text-center mb-3">News</span>
            <h2 class="text-3xl lg:text-4xl font-bold font-serif text-zinc-900 text-center mb-4">Latest Updates</h2>
            <div class="w-20 h-1.5 bg-brand-500 rounded-full mx-auto mb-14"></div>

            @if($articles->isEmpty())
                <div class="flex h-64 flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-zinc-50">
                    <svg class="mb-4 h-10 w-10 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-4.5-4.5A2 2 0 0015.5 3H15"/>
                    </svg>
                    <p class="text-sm text-zinc-500">No articles published yet. Check back soon.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto px-4">
                    @foreach($articles as $article)
                        <div class="rounded bg-white shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:shadow-lg transition-all duration-500 overflow-hidden flex flex-col">

                            {{-- Image area --}}
                            <a href="{{ route('news.show', $article->slug) }}" class="block overflow-hidden">
                                @if($article->featuredImageUrl())
                                    <img src="{{ $article->featuredImageUrl() }}" alt="{{ $article->title }}" class="w-full h-52 object-cover hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-52 bg-gradient-to-br from-brand-600 to-brand-800 flex items-center justify-center">
                                        <svg class="h-12 w-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-4.5-4.5A2 2 0 0015.5 3H15"/>
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            <div class="p-8 flex flex-col flex-1">
                                <div class="flex items-center gap-3 mb-4">
                                    @if($article->category)
                                        <span class="text-xs font-semibold uppercase tracking-widest text-crimson-700 bg-crimson-50 px-2 py-0.5 rounded">{{ ucwords(str_replace('-', ' ', $article->category)) }}</span>
                                    @endif
                                    <span class="text-xs text-zinc-400">{{ $article->published_at?->format('d M Y') }}</span>
                                </div>

                                <h4 class="text-lg font-bold font-serif text-zinc-900 mb-3 line-clamp-2 leading-snug">
                                    <a href="{{ route('news.show', $article->slug) }}" class="hover:text-crimson-700 transition-colors">{{ $article->title }}</a>
                                </h4>

                                @if($article->excerpt)
                                    <p class="text-[15px] leading-[26px] text-zinc-600 line-clamp-3 flex-1">{{ $article->excerpt }}</p>
                                @endif

                                <a href="{{ route('news.show', $article->slug) }}" class="mt-6 inline-block text-sm font-medium text-brand-600 hover:text-crimson-700 transition-colors">Read More &rarr;</a>
                            </div>

                        </div>
                    @endforeach
                </div>

                <div class="mt-12 text-center">{{ $articles->links() }}</div>
            @endif

        </div>
    </section>

</x-layouts::website>
