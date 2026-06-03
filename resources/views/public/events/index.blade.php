{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Events & Missions"
    banner_image : Banner background (PLACEHOLDER UNTIL UPLOADED)
    hero_title   : Page heading
    hero_subtitle: Subheading
    intro_body   : CTA band heading text
--}}
<x-layouts::website :title="'Events &amp; Missions — NiKCCIMA'">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden py-28 text-white lg:py-36">
        @if($page?->section('banner_image'))
            <div class="absolute inset-0">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('banner_image')) }}"
                     alt="" class="h-full w-full object-cover" aria-hidden="true">
                <div class="absolute inset-0 bg-zinc-950/70"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-800 to-crimson-950"></div>
        @endif

        {{-- Dot pattern --}}
        <svg class="absolute inset-0 h-full w-full opacity-[0.07]" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <defs>
                <pattern id="dp-events-hero" x="0" y="0" width="26" height="26" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dp-events-hero)"/>
        </svg>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <span class="mb-3 block text-xs font-bold uppercase tracking-widest text-brand-200">Calendar</span>
            <h1 class="mb-4 font-serif text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                {{ $page?->section('hero_title', 'Events & Missions') }}
            </h1>
            <p class="mx-auto max-w-2xl text-lg text-zinc-300 sm:text-xl">
                {{ $page?->section('hero_subtitle', 'Join NiKCCIMA flagship events, bilateral trade missions, and sector forums driving measurable corridor outcomes.') }}
            </p>
        </div>
    </section>

    {{-- ===================== UPCOMING EVENTS ===================== --}}
    <section class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <span class="mb-3 block text-center text-xs font-bold uppercase tracking-widest text-crimson-700">What's On</span>
            <h2 class="mb-4 text-center font-serif text-3xl font-bold text-zinc-900 lg:text-4xl">Upcoming Events</h2>
            <div class="mx-auto mb-14 h-1.5 w-20 rounded-full bg-brand-500"></div>

            @if($upcoming->isEmpty())
                <div class="flex h-64 flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-zinc-50">
                    <svg class="mb-4 h-10 w-10 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-zinc-500">No upcoming events scheduled. Please check back soon.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($upcoming as $event)
                        @php
                            $typeLower = strtolower($event->type ?? '');
                            $isFlagship = str_contains($typeLower, 'flagship') || str_contains($typeLower, 'summit');
                        @endphp
                        <article class="group flex flex-col overflow-hidden rounded-xl bg-white shadow-[0_0_15px_rgba(0,0,0,0.08)] transition-all duration-500 hover:-translate-y-1 hover:shadow-xl">

                            {{-- Poster / image with date badge --}}
                            <a href="{{ route('events.show', $event->id) }}" class="relative block overflow-hidden">
                                @if($event->featured_image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($event->featured_image) }}"
                                         alt="{{ $event->title }}"
                                         class="h-56 w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="flex h-56 w-full items-center justify-center bg-gradient-to-br {{ $isFlagship ? 'from-crimson-700 to-crimson-950' : 'from-brand-600 to-brand-900' }}">
                                        <svg class="h-14 w-14 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Date badge --}}
                                <div class="absolute left-4 top-4 flex flex-col items-center rounded-lg bg-white/95 px-3 py-1.5 text-center shadow-md backdrop-blur">
                                    <span class="text-lg font-bold leading-none text-crimson-700">{{ $event->starts_at->format('d') }}</span>
                                    <span class="text-[10px] font-semibold uppercase tracking-wide text-zinc-500">{{ $event->starts_at->format('M Y') }}</span>
                                </div>

                                {{-- Type pill --}}
                                <span class="absolute right-4 top-4 inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold text-white {{ $isFlagship ? 'bg-crimson-700/90' : 'bg-brand-700/90' }}">
                                    {{ ucwords(str_replace('-', ' ', $event->type ?? 'Event')) }}
                                </span>
                            </a>

                            {{-- Body --}}
                            <div class="flex flex-1 flex-col p-6">
                                <h3 class="mb-3 font-serif text-lg font-bold leading-snug text-zinc-900 line-clamp-2">
                                    <a href="{{ route('events.show', $event->id) }}" class="transition-colors hover:text-crimson-700">{{ $event->title }}</a>
                                </h3>

                                @if($event->description)
                                    <p class="mb-5 flex-1 text-[15px] leading-relaxed text-zinc-600 line-clamp-3">{{ Str::limit(strip_tags($event->description), 120) }}</p>
                                @endif

                                <div class="mt-auto space-y-2 border-t border-zinc-100 pt-4 text-sm text-zinc-500">
                                    {{-- Date / range --}}
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 shrink-0 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>
                                            {{ $event->starts_at->format('d M Y') }}@if($event->ends_at && !$event->starts_at->isSameDay($event->ends_at)) &ndash; {{ $event->ends_at->format('d M Y') }}@endif
                                        </span>
                                    </div>
                                    {{-- Venue --}}
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 shrink-0 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="line-clamp-1">{{ $event->venue ?? 'Venue TBC' }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('events.show', $event->id) }}" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-crimson-700 transition-colors hover:text-crimson-900">
                                    View details
                                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12 text-center">{{ $upcoming->links() }}</div>
            @endif
        </div>
    </section>

    {{-- ===================== PAST EVENTS ===================== --}}
    @if($past->isNotEmpty())
        <section class="bg-zinc-50 py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <span class="mb-3 block text-center text-xs font-bold uppercase tracking-widest text-zinc-500">Archive</span>
                <h2 class="mb-4 text-center font-serif text-3xl font-bold text-zinc-900 lg:text-4xl">Past Events</h2>
                <div class="mx-auto mb-14 h-1.5 w-20 rounded-full bg-zinc-300"></div>

                <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($past as $event)
                        <a href="{{ route('events.show', $event->id) }}"
                           class="group flex flex-col overflow-hidden rounded-xl border border-zinc-200 bg-white transition-all duration-300 hover:shadow-md">
                            <div class="relative overflow-hidden">
                                @if($event->featured_image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($event->featured_image) }}"
                                         alt="{{ $event->title }}"
                                         class="h-36 w-full object-cover opacity-90 transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="flex h-36 w-full items-center justify-center bg-zinc-100">
                                        <svg class="h-9 w-9 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col p-4">
                                <span class="mb-1 text-xs text-zinc-400">{{ $event->starts_at->format('d M Y') }}</span>
                                <h4 class="text-sm font-semibold leading-snug text-zinc-700 line-clamp-2 transition-colors group-hover:text-crimson-700">{{ $event->title }}</h4>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== CTA BAND ===================== --}}
    <section class="bg-crimson-700 py-16 text-white">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="mb-4 font-serif text-2xl font-bold sm:text-3xl">
                {{ $page?->section('intro_body', 'Have questions about our events and missions?') }}
            </h2>
            <p class="mx-auto mb-8 max-w-2xl text-crimson-100">
                Trade missions, bilateral summits, B2B matchmaking sessions, and AfCFTA corridor events — get in touch with the secretariat.
            </p>
            <a href="{{ route('contact') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-white px-8 py-3 text-sm font-semibold text-crimson-700 shadow-sm transition hover:bg-crimson-50">
                Contact Us
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </section>

</x-layouts::website>
