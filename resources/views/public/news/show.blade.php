@php
    $articleImage = $article->featuredImageUrl();
    $articleDescription = $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->body), 160);
    $shareUrl = route('news.show', $article->slug);
    $related = $article->relatedPosts(3);
    $comments = $article->approvedComments;
    $articleJsonLd = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $article->title,
        'description' => $articleDescription,
        'image' => $articleImage ? [$articleImage] : null,
        'datePublished' => optional($article->published_at)->toIso8601String(),
        'dateModified' => optional($article->updated_at)->toIso8601String(),
        'author' => ['@type' => $article->author ? 'Person' : 'Organization', 'name' => $article->author?->name ?? 'NiKCCIMA'],
        'publisher' => ['@type' => 'Organization', 'name' => 'NiKCCIMA'],
        'mainEntityOfPage' => $shareUrl,
    ]);
@endphp
<x-layouts::website
    :title="$article->title . ' — NiKCCIMA'"
    :meta-description="$articleDescription"
    :og-image="$articleImage"
    og-type="article"
    :json-ld="$articleJsonLd">

    {{-- Header Image or Gradient --}}
    @if($articleImage)
        <div class="h-80 w-full overflow-hidden lg:h-[480px]">
            <img src="{{ $articleImage }}" alt="{{ $article->title }}" class="h-full w-full object-cover">
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

    <section class="py-16 bg-white">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="mb-8 flex items-center gap-2 text-sm text-zinc-400">
                <a href="{{ route('news.index') }}" class="font-medium text-brand-700 hover:underline">News</a>
                <svg class="h-4 w-4 shrink-0 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="truncate text-zinc-500">{{ Str::limit($article->title, 60) }}</span>
            </nav>

            {{-- Meta Row --}}
            <div class="mb-4 flex flex-wrap items-center gap-3">
                @if($article->category)
                    <a href="{{ route('news.category', $article->category->slug) }}" class="inline-flex rounded-full bg-brand-100 px-3 py-1 text-xs font-medium text-brand-700 hover:bg-brand-200">
                        {{ $article->category->name }}
                    </a>
                @endif
                <span class="text-sm text-zinc-500">{{ $article->published_at?->format('d M Y') }}</span>
                <span class="text-sm text-zinc-400">&middot; {{ $article->reading_time }} min read</span>
                @if($article->author)
                    <span class="text-sm text-zinc-500">by {{ $article->author->name }}</span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="mb-6 font-serif text-4xl font-bold text-zinc-900 lg:text-5xl">{{ $article->title }}</h1>

            {{-- Excerpt --}}
            @if($article->excerpt)
                <p class="mb-8 border-l-4 border-brand-300 pl-4 text-lg text-zinc-500">{{ $article->excerpt }}</p>
            @endif

            {{-- Body --}}
            <div class="prose prose-zinc max-w-none">{!! $article->body !!}</div>

            {{-- Tags --}}
            @if($article->tags->isNotEmpty())
                <div class="mt-8 flex flex-wrap gap-2">
                    @foreach($article->tags as $tag)
                        <a href="{{ route('news.tag', $tag->slug) }}" class="rounded-full bg-zinc-100 px-3 py-1 text-xs text-zinc-600 hover:bg-zinc-200">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            @endif

            {{-- Share --}}
            <div class="mt-8 flex items-center gap-3 border-t border-zinc-200 pt-6">
                <span class="text-sm font-medium text-zinc-500">Share:</span>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($article->title) }}" target="_blank" rel="noopener"
                   class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-100 text-zinc-600 transition hover:bg-brand-600 hover:text-white" aria-label="Share on X">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener"
                   class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-100 text-zinc-600 transition hover:bg-brand-600 hover:text-white" aria-label="Share on LinkedIn">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.13 1.45-2.13 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.45v6.29zM5.34 7.43a2.07 2.07 0 110-4.14 2.07 2.07 0 010 4.14zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.8 0 0 .78 0 1.74v20.52C0 23.22.8 24 1.77 24h20.45c.98 0 1.78-.78 1.78-1.74V1.74C24 .78 23.2 0 22.22 0z"/></svg>
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener"
                   class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-100 text-zinc-600 transition hover:bg-brand-600 hover:text-white" aria-label="Share on Facebook">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07c0 6.03 4.39 11.03 10.13 11.93v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.89v2.25h3.33l-.53 3.49h-2.8v8.44C19.61 23.1 24 18.1 24 12.07z"/></svg>
                </a>
            </div>

            {{-- Author Bio --}}
            @if($article->author)
                <div class="mt-10 flex gap-4 rounded-2xl border border-zinc-200 bg-zinc-50 p-6">
                    @if($article->author->profilePhotoUrl())
                        <img src="{{ $article->author->profilePhotoUrl() }}" alt="{{ $article->author->name }}" class="h-16 w-16 shrink-0 rounded-full object-cover">
                    @else
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-brand-600 text-xl font-bold text-white">
                            {{ \Illuminate\Support\Str::of($article->author->name)->explode(' ')->map(fn ($p) => \Illuminate\Support\Str::substr($p, 0, 1))->take(2)->implode('') }}
                        </div>
                    @endif
                    <div>
                        <p class="font-serif text-lg font-bold text-zinc-900">{{ $article->author->name }}</p>
                        @if($article->author->job_title)
                            <p class="text-sm text-brand-700">{{ $article->author->job_title }}</p>
                        @endif
                        @if($article->author->bio)
                            <p class="mt-2 text-sm text-zinc-600">{{ $article->author->bio }}</p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Comments --}}
            <div class="mt-12 border-t border-zinc-200 pt-10">
                <h2 class="mb-6 font-serif text-2xl font-bold text-zinc-900">
                    {{ $comments->count() }} {{ \Illuminate\Support\Str::plural('Comment', $comments->count()) }}
                </h2>

                @if($comments->isNotEmpty())
                    <div class="mb-10 space-y-6">
                        @foreach($comments as $comment)
                            <div class="flex gap-4">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-zinc-200 text-sm font-bold text-zinc-600">
                                    {{ \Illuminate\Support\Str::substr($comment->author_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-zinc-900">{{ $comment->author_name }}</span>
                                        <span class="text-xs text-zinc-400">{{ $comment->created_at->format('d M Y') }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-zinc-600">{{ $comment->body }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mb-10 text-sm text-zinc-500">Be the first to comment.</p>
                @endif

                <h3 class="mb-4 font-serif text-xl font-bold text-zinc-900">Leave a comment</h3>
                <livewire:public.news-comment-form :article-id="$article->id" :key="'news-comment-form-'.$article->id" />
            </div>

            {{-- Related Articles --}}
            @if($related->isNotEmpty())
                <div class="mt-16 border-t border-zinc-200 pt-10">
                    <h2 class="mb-6 font-serif text-2xl font-bold text-zinc-900">Related News</h2>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        @foreach($related as $rel)
                            <a href="{{ route('news.show', $rel->slug) }}" class="group block">
                                @if($rel->featuredImageUrl())
                                    <img src="{{ $rel->featuredImageUrl() }}" alt="{{ $rel->title }}" class="mb-3 h-32 w-full rounded-lg object-cover">
                                @else
                                    <div class="mb-3 h-32 w-full rounded-lg bg-gradient-to-br from-brand-600 to-brand-800"></div>
                                @endif
                                <p class="text-sm font-semibold text-zinc-800 group-hover:text-crimson-700 line-clamp-2">{{ $rel->title }}</p>
                                <p class="mt-1 text-xs text-zinc-400">{{ $rel->published_at?->format('d M Y') }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Back Link --}}
            <div class="mt-12 border-t border-zinc-200 pt-8">
                <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-brand-700 hover:text-brand-800 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to News
                </a>
            </div>

        </div>
    </section>

</x-layouts::website>
