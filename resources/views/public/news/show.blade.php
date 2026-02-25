<x-layouts::website :title="$article->title . ' — NiKCCIMA'">

    {{-- Header Image or Gradient --}}
    @if($article->featuredImageUrl())
        <div class="h-80 w-full overflow-hidden lg:h-[480px]">
            <img src="{{ $article->featuredImageUrl() }}"
                 alt="{{ $article->title }}"
                 class="h-full w-full object-cover">
        </div>
    @else
        <div class="relative h-64 bg-gradient-to-br from-green-900 to-green-800">
            <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dp" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dp)"/>
            </svg>
        </div>
    @endif

    {{-- Article Content --}}
    <section class="py-16 bg-white dark:bg-zinc-950">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="mb-8 flex items-center gap-2 text-sm text-zinc-400 dark:text-zinc-500">
                <a href="{{ route('news.index') }}"
                   class="font-medium text-green-700 dark:text-green-400 hover:underline">News</a>
                <svg class="h-4 w-4 shrink-0 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="truncate text-zinc-500 dark:text-zinc-400">{{ Str::limit($article->title, 60) }}</span>
            </nav>

            {{-- Meta Row --}}
            <div class="mb-4 flex flex-wrap items-center gap-3">
                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400">
                    {{ ucwords(str_replace('-', ' ', $article->category)) }}
                </span>
                <span class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ $article->published_at?->format('d M Y') }}
                </span>
                @if($article->author)
                    <span class="text-sm text-zinc-500 dark:text-zinc-400">by {{ $article->author->name }}</span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="mb-6 font-serif text-4xl font-bold text-zinc-900 dark:text-white lg:text-5xl">
                {{ $article->title }}
            </h1>

            {{-- Excerpt --}}
            @if($article->excerpt)
                <p class="mb-8 border-l-4 border-green-400 pl-4 text-lg text-zinc-500 dark:text-zinc-400">
                    {{ $article->excerpt }}
                </p>
            @endif

            {{-- Body --}}
            <div class="prose prose-zinc dark:prose-invert max-w-none">
                {!! $article->body !!}
            </div>

            {{-- Back Link --}}
            <div class="mt-12 border-t border-zinc-200 dark:border-zinc-800 pt-8">
                <a href="{{ route('news.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-green-700 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to News
                </a>
            </div>

        </div>
    </section>

</x-layouts::website>
