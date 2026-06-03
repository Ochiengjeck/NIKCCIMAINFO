<x-layouts::website :title="($event->title).' — NiKCCIMA Events'">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden py-20 text-white lg:py-24">
        {{-- Gradient bg --}}
        <div class="absolute inset-0 bg-gradient-to-br from-brand-950 via-brand-900 to-brand-800"></div>

        {{-- Dot pattern --}}
        <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <defs>
                <pattern id="dp-event-show" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dp-event-show)"/>
        </svg>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="mb-6 flex items-center gap-2 text-sm text-brand-200" aria-label="Breadcrumb">
                <a href="{{ route('events.index') }}" class="transition hover:text-white">Events</a>
                <svg class="h-3.5 w-3.5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-brand-100">{{ Str::limit($event->title, 60) }}</span>
            </nav>

            {{-- Event type badge --}}
            @php
                $typeLower = strtolower($event->type ?? '');
                $isFlagship = str_contains($typeLower, 'flagship') || str_contains($typeLower, 'summit');
            @endphp
            <span class="mb-4 inline-flex rounded-full px-3 py-1 text-xs font-semibold text-white
                {{ $isFlagship ? 'bg-crimson-700/90' : 'bg-brand-700/90' }}">
                {{ ucwords(str_replace('-', ' ', $event->type ?? 'Event')) }}
            </span>

            <h1 class="mt-2 max-w-3xl font-serif text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl">
                {{ $event->title }}
            </h1>
        </div>
    </section>

    {{-- ===================== CONTENT + SIDEBAR ===================== --}}
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-3">

                {{-- ---- Main Content ---- --}}
                <div class="lg:col-span-2">
                    {{-- Poster / featured image --}}
                    @if($event->featured_image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($event->featured_image) }}"
                             alt="{{ $event->title }}"
                             class="mb-8 w-full rounded-2xl border border-zinc-100 object-cover shadow-sm" />
                    @endif

                    @if($event->description)
                        <h2 class="mb-4 font-serif text-2xl font-bold text-zinc-900">
                            About This Event
                        </h2>
                        <div class="prose prose-zinc max-w-none leading-relaxed text-zinc-700">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                    @else
                        <div class="flex h-40 items-center justify-center rounded-2xl border border-dashed border-zinc-200 bg-zinc-50">
                            <p class="text-sm text-zinc-400">Event details coming soon.</p>
                        </div>
                    @endif

                    {{-- Photo gallery --}}
                    @if(!empty($event->gallery))
                        <div class="mt-10 border-t border-zinc-100 pt-8">
                            <h2 class="mb-5 font-serif text-2xl font-bold text-zinc-900">Gallery</h2>
                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                                @foreach($event->gallery as $image)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image) }}"
                                       target="_blank" rel="noopener"
                                       class="group block overflow-hidden rounded-xl border border-zinc-100 shadow-sm">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image) }}"
                                             alt="{{ $event->title }} photo"
                                             class="aspect-[4/3] w-full object-cover transition duration-300 group-hover:scale-105" />
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-10 border-t border-zinc-100 pt-6">
                        <a href="{{ route('events.index') }}"
                           class="inline-flex items-center gap-2 text-sm font-medium text-brand-700 transition hover:text-brand-800">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Events
                        </a>
                    </div>
                </div>

                {{-- ---- Sidebar ---- --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24 rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                        <h3 class="mb-5 font-serif text-lg font-bold text-zinc-900">
                            Event Details
                        </h3>

                        <ul class="space-y-4">

                            {{-- Date --}}
                            <li class="flex items-start gap-3">
                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-50">
                                    <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Date</p>
                                    <p class="mt-0.5 text-sm font-medium text-zinc-900">
                                        {{ $event->starts_at->format('d M Y') }}
                                    </p>
                                    {{-- Time range if ends_at is set --}}
                                    @if($event->ends_at)
                                        <p class="mt-0.5 text-xs text-zinc-500">
                                            {{ $event->starts_at->format('g:i A') }}
                                            @if(!$event->starts_at->isSameDay($event->ends_at))
                                                &ndash; {{ $event->ends_at->format('d M Y, g:i A') }}
                                            @else
                                                &ndash; {{ $event->ends_at->format('g:i A') }}
                                            @endif
                                        </p>
                                    @else
                                        <p class="mt-0.5 text-xs text-zinc-500">
                                            {{ $event->starts_at->format('g:i A') }}
                                        </p>
                                    @endif
                                </div>
                            </li>

                            {{-- Venue --}}
                            @if($event->venue)
                                <li class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-50">
                                        <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Venue</p>
                                        <p class="mt-0.5 text-sm font-medium text-zinc-900">{{ $event->venue }}</p>
                                    </div>
                                </li>
                            @endif

                            {{-- Organizer --}}
                            @if($event->organizer)
                                <li class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-50">
                                        <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Organiser</p>
                                        <p class="mt-0.5 text-sm font-medium text-zinc-900">{{ $event->organizer->name }}</p>
                                    </div>
                                </li>
                            @endif

                            {{-- Capacity --}}
                            @if($event->max_capacity)
                                <li class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-50">
                                        <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Capacity</p>
                                        <p class="mt-0.5 text-sm font-medium text-zinc-900">
                                            {{ number_format($event->max_capacity) }} attendees
                                        </p>
                                    </div>
                                </li>
                            @endif
                        </ul>

                        {{-- Brochure download --}}
                        @if($event->brochure_path)
                            <div class="mt-6 border-t border-zinc-100 pt-5">
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($event->brochure_path) }}"
                                   download="{{ $event->brochure_name ?? 'event-brochure.pdf' }}"
                                   class="flex w-full items-center justify-center gap-2 rounded-xl border border-brand-200 bg-brand-50 px-5 py-3 text-sm font-semibold text-brand-800 transition hover:bg-brand-100">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download Brochure
                                </a>
                            </div>
                        @endif

                        {{-- CTA --}}
                        <div class="mt-6 border-t border-zinc-100 pt-5">
                            <a href="{{ route('contact') }}?subject=Event+Inquiry%3A+{{ urlencode($event->title) }}"
                               class="flex w-full items-center justify-center gap-2 rounded-xl bg-crimson-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-crimson-800 hover:shadow-md">
                                Inquire About This Event
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</x-layouts::website>
