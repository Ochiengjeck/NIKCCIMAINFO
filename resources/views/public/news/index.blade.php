<x-layouts::website :title="'News & Insights — NiKCCIMA'">

    {{-- Hero --}}
    <section class="relative overflow-hidden py-24 text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-900 to-brand-800">
            <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dp" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dp)"/>
            </svg>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span class="mb-4 inline-flex rounded-full bg-crimson-700 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">Press</span>
            <h1 class="font-serif text-4xl font-bold lg:text-6xl">News & Insights</h1>
            <p class="mt-4 max-w-2xl text-lg text-brand-200">Latest press releases, trade outcomes, policy insights, and institutional updates from NiKCCIMA.</p>
        </div>
    </section>

    {{-- Articles Grid --}}
    <section class="py-20 bg-white dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if($articles->isEmpty())
                <div class="flex h-64 flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                    <svg class="mb-4 h-10 w-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">No articles published yet. Check back soon.</p>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($articles as $article)
                        <a href="{{ route('news.show', $article->slug) }}"
                           class="group flex flex-col overflow-hidden rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 transition hover:-translate-y-0.5 hover:shadow-lg">

                            {{-- Image --}}
                            @if($article->featuredImageUrl())
                                <div class="overflow-hidden">
                                    <img src="{{ $article->featuredImageUrl() }}"
                                         alt="{{ $article->title }}"
                                         class="h-52 w-full object-cover transition duration-500 group-hover:scale-105">
                                </div>
                            @else
                                <div class="flex h-52 items-center justify-center bg-gradient-to-br from-brand-50 to-zinc-100 dark:from-zinc-800 dark:to-zinc-900">
                                    <svg class="h-12 w-12 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                </div>
                            @endif

                            {{-- Body --}}
                            <div class="flex flex-1 flex-col p-6">
                                <div class="mb-2 flex items-center gap-2">
                                    <span class="inline-flex rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:bg-brand-900/30 dark:text-brand-400">
                                        {{ ucwords(str_replace('-', ' ', $article->category)) }}
                                    </span>
                                    <span class="text-xs text-zinc-400">{{ $article->published_at?->format('d M Y') }}</span>
                                </div>

                                <h2 class="mt-2 line-clamp-2 font-semibold text-zinc-900 dark:text-white transition group-hover:text-brand-700 dark:group-hover:text-brand-400">
                                    {{ $article->title }}
                                </h2>

                                @if($article->excerpt)
                                    <p class="mt-2 line-clamp-3 text-sm text-zinc-500 dark:text-zinc-400">{{ $article->excerpt }}</p>
                                @endif

                                <p class="mt-3 text-sm font-medium text-brand-700 dark:text-brand-400">Read More &rarr;</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-10">{{ $articles->links() }}</div>
            @endif

        </div>
    </section>

</x-layouts::website>
