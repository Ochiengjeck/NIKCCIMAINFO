{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Events & Missions"
    banner_image : Banner background (PLACEHOLDER UNTIL UPLOADED)
    hero_title   : Page heading
    hero_subtitle: Subheading
    intro_body   : Intro paragraph
--}}
<x-layouts::website :title="'Events & Missions — NiKCCIMA'">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden py-24 text-white lg:py-32">
        {{-- Background --}}
        @if($page?->section('banner_image'))
            <div class="absolute inset-0">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('banner_image')) }}"
                     alt=""
                     class="h-full w-full object-cover"
                     aria-hidden="true">
                <div class="absolute inset-0 bg-gradient-to-br from-green-950/90 via-green-900/80 to-emerald-800/70"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-green-950 via-green-900 to-emerald-800"></div>
        @endif

        {{-- Dot pattern --}}
        <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <defs>
                <pattern id="dp-events" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dp-events)"/>
        </svg>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <span class="mb-4 inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-green-200">
                    Calendar
                </span>
                <h1 class="mb-5 font-serif text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                    {{ $page?->section('hero_title', 'Events &amp; Trade Missions') }}
                </h1>
                <p class="max-w-2xl text-lg text-green-100 sm:text-xl">
                    {{ $page?->section('hero_subtitle', 'Join NiKCCIMA flagship events, bilateral trade missions, and sector forums driving measurable corridor outcomes.') }}
                </p>
            </div>
        </div>
    </section>

    {{-- ===================== INTRO ===================== --}}
    @if($page?->section('intro_body'))
        <section class="border-b border-zinc-100 py-8 dark:border-zinc-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="max-w-3xl text-lg leading-relaxed text-zinc-600 dark:text-zinc-300">
                    {{ $page->section('intro_body') }}
                </p>
            </div>
        </section>
    @endif

    {{-- ===================== UPCOMING EVENTS ===================== --}}
    <section class="py-20 bg-white dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mb-10">
                <span class="mb-2 inline-block text-xs font-semibold uppercase tracking-widest text-green-600 dark:text-green-400">
                    Upcoming
                </span>
                <h2 class="font-serif text-3xl font-bold text-zinc-900 dark:text-white sm:text-4xl">
                    Upcoming Events &amp; Missions
                </h2>
            </div>

            @if($upcoming->isEmpty())
                <div class="flex h-48 items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="text-center">
                        <svg class="mx-auto mb-3 h-10 w-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-zinc-500 dark:text-zinc-400">No upcoming events scheduled. Check back soon.</p>
                    </div>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($upcoming as $event)
                        @php
                            $typeLower = strtolower($event->type ?? '');
                            $isFlagship = str_contains($typeLower, 'flagship') || str_contains($typeLower, 'summit');
                            $isMission  = str_contains($typeLower, 'mission');
                            $stripColor = $isFlagship ? 'bg-red-600' : ($isMission ? 'bg-green-700' : 'bg-green-800');
                            $badgeColor = $isFlagship ? 'bg-red-600' : 'bg-green-700';
                        @endphp
                        <a href="{{ route('events.show', $event->id) }}"
                           class="group overflow-hidden rounded-2xl border border-zinc-200 bg-white transition hover:-translate-y-0.5 hover:shadow-lg dark:border-zinc-700 dark:bg-zinc-900">

                            {{-- Coloured top strip --}}
                            <div class="h-1.5 w-full {{ $stripColor }}"></div>

                            <div class="p-6">
                                {{-- Date box --}}
                                <div class="mb-3 flex items-end gap-2">
                                    <span class="font-serif text-4xl font-bold leading-none text-zinc-900 dark:text-white">
                                        {{ $event->starts_at->format('d') }}
                                    </span>
                                    <span class="mb-0.5 text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                                        {{ $event->starts_at->format('M Y') }}
                                    </span>
                                </div>

                                {{-- Type badge --}}
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold text-white {{ $badgeColor }}">
                                    {{ ucwords(str_replace('-', ' ', $event->type ?? 'Event')) }}
                                </span>

                                {{-- Title --}}
                                <h3 class="mt-2 font-semibold leading-snug text-zinc-900 line-clamp-2 transition group-hover:text-green-700 dark:text-white dark:group-hover:text-green-400">
                                    {{ $event->title }}
                                </h3>

                                {{-- Venue --}}
                                @if($event->venue)
                                    <div class="mt-2 flex items-center gap-1.5 text-sm text-zinc-500 dark:text-zinc-400">
                                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $event->venue }}
                                    </div>
                                @endif

                                {{-- Date range for multi-day events --}}
                                @if($event->ends_at && !$event->starts_at->isSameDay($event->ends_at))
                                    <p class="mt-2 text-xs text-zinc-400 dark:text-zinc-500">
                                        {{ $event->starts_at->format('d M') }} &ndash; {{ $event->ends_at->format('d M Y') }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $upcoming->links() }}
                </div>
            @endif
        </div>
    </section>

    {{-- ===================== PAST EVENTS ===================== --}}
    @if($past->isNotEmpty())
        <section class="py-16 bg-zinc-50 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div class="mb-8">
                    <span class="mb-2 inline-block text-xs font-semibold uppercase tracking-widest text-zinc-400 dark:text-zinc-500">
                        Archive
                    </span>
                    <h2 class="font-serif text-2xl font-bold text-zinc-700 dark:text-zinc-300">
                        Past Events
                    </h2>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($past as $event)
                        <a href="{{ route('events.show', $event->id) }}"
                           class="group rounded-2xl border border-zinc-200 bg-white p-5 transition hover:shadow-sm dark:border-zinc-700/60 dark:bg-zinc-800/50">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">
                                    {{ $event->starts_at->format('d M Y') }}
                                </span>
                                <span class="inline-flex rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400">
                                    Past
                                </span>
                            </div>
                            <h3 class="text-sm font-semibold text-zinc-600 line-clamp-2 transition group-hover:text-zinc-800 dark:text-zinc-400 dark:group-hover:text-zinc-200">
                                {{ $event->title }}
                            </h3>
                            @if($event->venue)
                                <div class="mt-1.5 flex items-center gap-1 text-xs text-zinc-400 dark:text-zinc-500">
                                    <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $event->venue }}
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-layouts::website>
