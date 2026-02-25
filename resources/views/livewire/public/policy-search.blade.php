<div>
    {{--
        CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Policy & Research"
        banner_image  : Hero background image (upload via Media Library — PLACEHOLDER UNTIL UPLOADED)
        hero_title    : Page heading (default: "Policy & Research")
        hero_subtitle : Subheading paragraph
    --}}

    {{-- Hero --}}
    <section class="relative overflow-hidden py-24 text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-green-950 to-green-900">
            <svg class="absolute inset-0 h-full w-full opacity-[0.04]" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="dp-policy" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.5" fill="white"/></pattern></defs>
                <rect width="100%" height="100%" fill="url(#dp-policy)"/>
            </svg>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span class="mb-4 inline-flex items-center gap-1.5 rounded-full bg-red-600 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Research
            </span>
            <h1 class="font-serif text-4xl font-bold lg:text-6xl">Policy & Research</h1>
            <p class="mt-4 max-w-2xl text-lg text-green-200">Evidence-based policy briefs, trade reports, NTB analysis, and AfCFTA corridor insights from NiKCCIMA.</p>

            {{-- Prominent search bar --}}
            <div class="mt-8 max-w-lg">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        placeholder="Search policy briefs..."
                        class="w-full rounded-2xl border-0 bg-white/10 py-3.5 pl-12 pr-5 text-sm text-white placeholder:text-green-300 backdrop-blur-sm ring-1 ring-white/20 focus:bg-white/15 focus:outline-none focus:ring-2 focus:ring-white/40">
                </div>
            </div>
        </div>
    </section>

    {{-- Policy Briefs --}}
    <section class="bg-white py-16 dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <span class="mb-1 inline-block text-xs font-semibold uppercase tracking-widest text-green-600">Published</span>
                <h2 class="font-serif text-2xl font-bold text-zinc-900 dark:text-white">Policy Briefs</h2>
            </div>

            @if($briefs->isEmpty())
                <div class="flex h-48 flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                    <svg class="mb-3 h-8 w-8 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="font-medium text-zinc-500 dark:text-zinc-400">
                        @if($search) No policy briefs match "{{ $search }}" @else No policy briefs published yet @endif
                    </p>
                    @if($search)
                        <button wire:click="$set('search', '')" class="mt-2 text-xs text-green-600 hover:underline dark:text-green-400">Clear search</button>
                    @endif
                </div>
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($briefs as $brief)
                        <div class="group rounded-2xl border border-zinc-200 bg-white p-6 transition hover:border-green-300 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-green-700">
                            <div class="mb-3 flex items-center justify-between">
                                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Policy Brief
                                </span>
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $brief->published_at?->format('d M Y') }}</span>
                            </div>
                            <h3 class="mb-3 font-semibold text-zinc-900 transition group-hover:text-green-700 dark:text-white dark:group-hover:text-green-400">{{ $brief->title }}</h3>
                            @if($brief->body)
                                <p class="text-sm leading-relaxed text-zinc-500 line-clamp-3 dark:text-zinc-400">{{ strip_tags($brief->body) }}</p>
                            @endif
                            @if($brief->author)
                                <div class="mt-4 flex items-center gap-2 border-t border-zinc-100 pt-4 dark:border-zinc-800">
                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-green-700 text-[10px] font-bold text-white">
                                        {{ strtoupper(substr($brief->author->name, 0, 1)) }}
                                    </div>
                                    <p class="text-xs text-zinc-400 dark:text-zinc-500">{{ $brief->author->name }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $briefs->links() }}</div>
            @endif
        </div>
    </section>

    {{-- Downloadable Documents --}}
    @if($documents->isNotEmpty())
        <section class="bg-zinc-50 py-16 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <span class="mb-1 inline-block text-xs font-semibold uppercase tracking-widest text-green-600">Downloads</span>
                    <h2 class="font-serif text-2xl font-bold text-zinc-900 dark:text-white">Trade Reports & Whitepapers</h2>
                </div>
                <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-950">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                        <thead class="bg-zinc-50 dark:bg-zinc-900">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Document</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Type</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Size</th>
                                <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Download</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach($documents as $doc)
                                @php $ext = strtoupper(pathinfo($doc->original_filename, PATHINFO_EXTENSION)); @endphp
                                <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-900/50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl
                                                {{ $ext === 'PDF' ? 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400' :
                                                   ($ext === 'DOCX' || $ext === 'DOC' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' :
                                                   'bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400') }}">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $doc->alt_text ?? $doc->original_filename }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium uppercase text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">{{ $ext }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">{{ $doc->humanSize() }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ $doc->url() }}" target="_blank" download
                                            class="inline-flex items-center gap-1.5 rounded-xl border border-green-200 bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700 transition hover:bg-green-100 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/40">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif
</div>
