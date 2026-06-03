@php
    $articleImage = $article->featuredImageUrl();
    $articleDescription = $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->body), 160);
    $articleJsonLd = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $article->title,
        'description' => $articleDescription,
        'image' => $articleImage ? [$articleImage] : null,
        'datePublished' => optional($article->published_at)->toIso8601String(),
        'dateModified' => optional($article->updated_at)->toIso8601String(),
        'author' => ['@type' => 'Organization', 'name' => 'NiKCCIMA'],
        'publisher' => ['@type' => 'Organization', 'name' => 'NiKCCIMA'],
        'mainEntityOfPage' => route('news.show', $article->slug),
    ]);
@endphp
<x-layouts::website
    :title="$article->title . ' — NiKCCIMA'"
    :meta-description="$articleDescription"
    :og-image="$articleImage"
    og-type="article"
    :json-ld="$articleJsonLd">

    {{-- Header Image or Gradient --}}
    @if($article->featuredImageUrl())
        <div class="h-80 w-full overflow-hidden lg:h-[480px]">
            <img src="{{ $article->featuredImageUrl() }}"
                 alt="{{ $article->title }}"
                 class="h-full w-full object-cover">
        </div>
    @else
        <div class="relative h-64 bg-gradient-to-br from-brand-900 to-brand-800">
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
    <section class="py-16 bg-white">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="mb-8 flex items-center gap-2 text-sm text-zinc-400">
                <a href="{{ route('news.index') }}"
                   class="font-medium text-brand-700 hover:underline">News</a>
                <svg class="h-4 w-4 shrink-0 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="truncate text-zinc-500">{{ Str::limit($article->title, 60) }}</span>
            </nav>

            {{-- Meta Row --}}
            <div class="mb-4 flex flex-wrap items-center gap-3">
                <span class="inline-flex rounded-full bg-brand-100 px-3 py-1 text-xs font-medium text-brand-700">
                    {{ ucwords(str_replace('-', ' ', $article->category)) }}
                </span>
                <span class="text-sm text-zinc-500">
                    {{ $article->published_at?->format('d M Y') }}
                </span>
                @if($article->author)
                    <span class="text-sm text-zinc-500">by {{ $article->author->name }}</span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="mb-6 font-serif text-4xl font-bold text-zinc-900 lg:text-5xl">
                {{ $article->title }}
            </h1>

            {{-- Excerpt --}}
            @if($article->excerpt)
                <p class="mb-8 border-l-4 border-brand-300 pl-4 text-lg text-zinc-500">
                    {{ $article->excerpt }}
                </p>
            @endif

            {{-- Body --}}
            <div class="prose prose-zinc max-w-none">
                {!! $article->body !!}
            </div>

            {{-- Back Link --}}
            <div class="mt-12 border-t border-zinc-200 pt-8">
                <a href="{{ route('news.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-brand-700 hover:text-brand-800 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to News
                </a>
            </div>

        </div>
    </section>

</x-layouts::website>
