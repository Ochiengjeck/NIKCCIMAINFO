<x-layouts::website
    :title="$brief->title . ' — NiKCCIMA Policy Brief'"
    :meta-description="\Illuminate\Support\Str::limit(strip_tags($brief->body), 155)">

    {{-- Hero --}}
    <section class="relative overflow-hidden py-20 text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-950 to-brand-900">
            <svg class="absolute inset-0 h-full w-full opacity-[0.04]" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="dp-brief" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.5" fill="white"/></pattern></defs>
                <rect width="100%" height="100%" fill="url(#dp-brief)"/>
            </svg>
        </div>
        <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('policy') }}" wire:navigate class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-brand-200 transition hover:text-white">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Policy &amp; Research
            </a>
            <span class="mb-4 inline-flex items-center gap-1.5 rounded-full bg-crimson-700 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">Policy Brief</span>
            <h1 class="font-serif text-3xl font-bold lg:text-5xl">{{ $brief->title }}</h1>
            <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-brand-200">
                @if($brief->author)<span>{{ $brief->author->name }}</span>@endif
                @if($brief->published_at)<span>&bull; {{ $brief->published_at->format('d M Y') }}</span>@endif
            </div>
        </div>
    </section>

    {{-- Body --}}
    <section class="bg-white py-16">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <article class="prose prose-zinc max-w-none leading-relaxed text-zinc-700">
                {!! nl2br(e($brief->body)) !!}
            </article>

            @if($brief->file)
                <div class="mt-10 flex items-center justify-between gap-3 rounded-2xl border border-brand-200 bg-brand-50 p-5">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-crimson-600 shadow-sm">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-zinc-900">Full brief document</p>
                            <p class="truncate text-xs text-zinc-500">{{ $brief->file->original_filename }} &bull; {{ $brief->file->humanSize() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('public.brief.download', $brief) }}"
                       class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-brand-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download PDF
                    </a>
                </div>
            @endif
        </div>
    </section>

</x-layouts::website>
