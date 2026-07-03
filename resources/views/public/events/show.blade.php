@php
    $typeLower = strtolower($event->type ?? '');
    $isFlagship = str_contains($typeLower, 'flagship') || str_contains($typeLower, 'summit');
    $posterUrl = $event->featured_image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($event->featured_image)
        : null;
    $eventDescription = \Illuminate\Support\Str::limit(strip_tags((string) $event->description), 160)
        ?: ('Join NiKCCIMA for '.$event->title.' — a Nigeria-Kenya AfCFTA corridor event.');
    $eventJsonLd = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => $event->title,
        'startDate' => optional($event->starts_at)->toIso8601String(),
        'endDate' => optional($event->ends_at)->toIso8601String(),
        'eventStatus' => 'https://schema.org/EventScheduled',
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'location' => $event->venue ? ['@type' => 'Place', 'name' => $event->venue] : null,
        'image' => $posterUrl ? [$posterUrl] : null,
        'description' => $eventDescription,
        'organizer' => ['@type' => 'Organization', 'name' => 'NiKCCIMA', 'url' => route('home')],
    ]);
@endphp
<x-layouts::website
    :title="($event->title).' — NiKCCIMA Events'"
    :meta-description="$eventDescription"
    :og-image="$posterUrl"
    og-type="article"
    :json-ld="$eventJsonLd">

    {{-- ===================== HERO ===================== --}}
    @if($posterUrl)
        {{-- Split hero: text on the left, full uncropped flyer on the right,
             over the brand gradient blended with a blurred copy of the poster. --}}
        <section class="relative isolate overflow-hidden text-white">
            <div class="absolute inset-0">
                <img src="{{ $posterUrl }}" alt="" aria-hidden="true" class="h-full w-full scale-110 object-cover blur-2xl opacity-30">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-950/95 via-zinc-950/90 to-brand-900/85"></div>
                <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <defs>
                        <pattern id="dp-event-show" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1.5" fill="white"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dp-event-show)"/>
                </svg>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                <div class="grid items-center gap-10 lg:grid-cols-5">

                    {{-- Left: text --}}
                    <div class="lg:col-span-3">
                        {{-- Breadcrumb --}}
                        <nav class="mb-6 flex items-center gap-2 text-sm text-zinc-300" aria-label="Breadcrumb">
                            <a href="{{ route('events.index') }}" class="transition hover:text-white">Events</a>
                            <svg class="h-3.5 w-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="text-zinc-100">{{ Str::limit($event->title, 60) }}</span>
                        </nav>

                        <span class="mb-4 inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold text-white {{ $isFlagship ? 'bg-crimson-600' : 'bg-brand-600' }}">
                            {{ ucwords(str_replace('-', ' ', $event->type ?? 'Event')) }}
                        </span>

                        <h1 class="font-serif text-3xl font-bold leading-tight drop-shadow-sm sm:text-4xl lg:text-5xl">
                            {{ $event->title }}
                        </h1>

                        <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm font-medium text-zinc-200">
                            <span class="inline-flex items-center gap-2">
                                <svg class="h-4 w-4 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $event->starts_at->format('d M Y') }}@if($event->ends_at && !$event->starts_at->isSameDay($event->ends_at)) &ndash; {{ $event->ends_at->format('d M Y') }}@endif
                            </span>
                            @if($event->venue)
                                <span class="inline-flex items-center gap-2">
                                    <svg class="h-4 w-4 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $event->venue }}
                                </span>
                            @endif
                        </div>

                        @if($event->registration_enabled)
                            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-registration'))"
                               class="mt-8 inline-flex items-center gap-2 rounded-xl bg-crimson-600 px-7 py-3.5 text-sm font-bold text-white shadow-lg transition hover:bg-crimson-700 hover:shadow-xl">
                                Register Now
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        @endif
                    </div>

                    {{-- Right: the full flyer --}}
                    <div class="lg:col-span-2">
                        <a href="{{ $posterUrl }}" target="_blank" rel="noopener" class="group block">
                            <img src="{{ $posterUrl }}" alt="{{ $event->title }}"
                                 class="mx-auto max-h-[80vh] w-auto rounded-xl shadow-2xl ring-1 ring-white/10 transition duration-300 group-hover:ring-white/30">
                        </a>
                        <p class="mt-3 text-center text-xs text-zinc-400">Click to view full size</p>
                    </div>

                </div>
            </div>
        </section>
    @else
        {{-- Gradient hero (no poster) --}}
        <section class="relative isolate overflow-hidden text-white">
            <div class="absolute inset-0 bg-gradient-to-br from-brand-950 via-brand-900 to-brand-800"></div>
            <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <defs>
                    <pattern id="dp-event-show" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dp-event-show)"/>
            </svg>

            <div class="relative mx-auto flex min-h-[420px] max-w-7xl flex-col justify-end px-4 pb-12 pt-32 sm:px-6 lg:min-h-[540px] lg:px-8 lg:pb-16 lg:pt-44">
                {{-- Breadcrumb --}}
                <nav class="mb-5 flex items-center gap-2 text-sm text-zinc-300" aria-label="Breadcrumb">
                    <a href="{{ route('events.index') }}" class="transition hover:text-white">Events</a>
                    <svg class="h-3.5 w-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-zinc-100">{{ Str::limit($event->title, 60) }}</span>
                </nav>

                <span class="mb-4 inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold text-white {{ $isFlagship ? 'bg-crimson-600' : 'bg-brand-600' }}">
                    {{ ucwords(str_replace('-', ' ', $event->type ?? 'Event')) }}
                </span>

                <h1 class="max-w-4xl font-serif text-3xl font-bold leading-tight drop-shadow-sm sm:text-4xl lg:text-5xl">
                    {{ $event->title }}
                </h1>

                <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm font-medium text-zinc-200">
                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $event->starts_at->format('d M Y') }}@if($event->ends_at && !$event->starts_at->isSameDay($event->ends_at)) &ndash; {{ $event->ends_at->format('d M Y') }}@endif
                    </span>
                    @if($event->venue)
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $event->venue }}
                        </span>
                    @endif
                </div>

                @if($event->registration_enabled)
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-registration'))"
                       class="mt-8 inline-flex w-fit items-center gap-2 rounded-xl bg-crimson-600 px-7 py-3.5 text-sm font-bold text-white shadow-lg transition hover:bg-crimson-700 hover:shadow-xl">
                        Register Now
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                @endif
            </div>
        </section>
    @endif

    {{-- ===================== CONTENT + SIDEBAR ===================== --}}
    <section class="bg-white py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-3">

                {{-- ---- Main Content ---- --}}
                <div class="lg:col-span-2">
                    @if($event->description)
                        @php
                            $descHtml = \Illuminate\Support\Str::contains((string) $event->description, '<')
                                ? $event->description
                                : nl2br(e($event->description));
                        @endphp
                        <h2 class="mb-5 font-serif text-2xl font-bold text-zinc-900">About This Event</h2>
                        <div class="trix-content max-w-none leading-relaxed text-zinc-700">
                            {!! $descHtml !!}
                        </div>
                    @else
                        <div class="flex h-40 items-center justify-center rounded-2xl border border-dashed border-zinc-200 bg-zinc-50">
                            <p class="text-sm text-zinc-400">Event details coming soon.</p>
                        </div>
                    @endif

                    {{-- Photo gallery --}}
                    @if(!empty($event->gallery))
                        <div class="mt-12 border-t border-zinc-100 pt-10">
                            <h2 class="mb-6 font-serif text-2xl font-bold text-zinc-900">Gallery</h2>
                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                                @foreach($event->gallery as $image)
                                    @php $imgUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($image); @endphp
                                    <a href="{{ $imgUrl }}" target="_blank" rel="noopener"
                                       class="group block overflow-hidden rounded-xl border border-zinc-100 shadow-sm">
                                        <img src="{{ $imgUrl }}" alt="{{ $event->title }} photo"
                                             class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105" />
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Resources & downloads --}}
                    @if($event->resources->isNotEmpty())
                        <div class="mt-12 border-t border-zinc-100 pt-10">
                            <h2 class="mb-6 font-serif text-2xl font-bold text-zinc-900">Resources &amp; Downloads</h2>
                            <ul class="space-y-3">
                                @foreach($event->resources as $resource)
                                    <li class="flex flex-col gap-3 rounded-xl border border-zinc-200 p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                                        <div class="flex min-w-0 items-start gap-3">
                                            <span class="mt-0.5 flex h-10 w-10 flex-none items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                            </span>
                                            <div class="min-w-0">
                                                <p class="truncate font-medium text-zinc-900">{{ $resource->title }}</p>
                                                <p class="mt-0.5 truncate text-xs text-zinc-500">
                                                    {{ $resource->file_name }}@if($resource->humanSize()) &middot; {{ $resource->humanSize() }}@endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex flex-none items-center gap-3">
                                            @if($resource->is_paid)
                                                <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">{{ $resource->priceLabel() }}</span>
                                                <a href="{{ route('contact') }}"
                                                   class="inline-flex items-center gap-1.5 rounded-lg border border-brand-200 bg-brand-50 px-4 py-2 text-sm font-medium text-brand-700 transition hover:bg-brand-100">
                                                    Contact to purchase
                                                </a>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-brand-100 px-3 py-1 text-xs font-semibold text-brand-800">Free</span>
                                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($resource->file_path) }}"
                                                   download="{{ $resource->file_name }}"
                                                   class="inline-flex items-center gap-1.5 rounded-lg bg-brand-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-800">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Download
                                                </a>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-12 border-t border-zinc-100 pt-6">
                        <a href="{{ route('events.index') }}"
                           class="inline-flex items-center gap-2 text-sm font-medium text-brand-700 transition hover:text-brand-900">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to all events
                        </a>
                    </div>
                </div>

                {{-- ---- Sidebar ---- --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24 overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm">
                        <div class="p-6">
                            <h3 class="mb-5 font-serif text-lg font-bold text-zinc-900">Event Details</h3>

                            <ul class="space-y-4">
                                {{-- Date --}}
                                <li class="flex items-start gap-3">
                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-50">
                                        <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Date</p>
                                        <p class="mt-0.5 text-sm font-medium text-zinc-900">{{ $event->starts_at->format('d M Y') }}</p>
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
                                            <p class="mt-0.5 text-xs text-zinc-500">{{ $event->starts_at->format('g:i A') }}</p>
                                        @endif
                                    </div>
                                </li>

                                {{-- Venue --}}
                                @if($event->venue)
                                    <li class="flex items-start gap-3">
                                        <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-50">
                                            <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
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
                                            <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
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
                                            <svg class="h-4 w-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-400">Capacity</p>
                                            <p class="mt-0.5 text-sm font-medium text-zinc-900">{{ number_format($event->max_capacity) }} attendees</p>
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
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Download Brochure
                                    </a>
                                </div>
                            @endif

                            {{-- Inquiry channels --}}
                            @php
                                $subject = rawurlencode('Event Inquiry: '.$event->title);
                                $waText = rawurlencode("Hello, I'd like to inquire about the event: ".$event->title);
                                $channels = collect($event->inquiry_channels ?? [])->map(function ($c) use ($subject, $waText) {
                                    $value = trim($c['value'] ?? '');
                                    if ($value === '') {
                                        return null;
                                    }
                                    return match ($c['type'] ?? '') {
                                        'email' => ['icon' => 'email', 'label' => $value, 'href' => 'mailto:'.$value.'?subject='.$subject, 'external' => false],
                                        'phone' => ['icon' => 'phone', 'label' => $value, 'href' => 'tel:'.preg_replace('/[^0-9+]/', '', $value), 'external' => false],
                                        'whatsapp' => ['icon' => 'whatsapp', 'label' => 'WhatsApp: '.$value, 'href' => 'https://wa.me/'.preg_replace('/[^0-9]/', '', $value).'?text='.$waText, 'external' => true],
                                        'url' => ['icon' => 'link', 'label' => 'Register / RSVP', 'href' => \Illuminate\Support\Str::startsWith($value, ['http://', 'https://']) ? $value : 'https://'.$value, 'external' => true],
                                        default => null,
                                    };
                                })->filter()->values();
                            @endphp

                            <div class="mt-6 border-t border-zinc-100 pt-5">
                                <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-zinc-400">Inquire About This Event</p>

                                @forelse($channels as $channel)
                                    <a href="{{ $channel['href'] }}"
                                       @if($channel['external']) target="_blank" rel="noopener" @endif
                                       class="mb-2 flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold shadow-sm transition
                                           {{ $loop->first ? 'bg-crimson-700 text-white hover:bg-crimson-800 hover:shadow-md' : 'border border-zinc-200 bg-white text-zinc-700 hover:border-crimson-300 hover:text-crimson-700' }}">
                                        @switch($channel['icon'])
                                            @case('email')
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                @break
                                            @case('phone')
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                @break
                                            @case('whatsapp')
                                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.149-.197.297-.767.967-.94 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.247-.694.247-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413"/></svg>
                                                @break
                                            @default
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 11-5.656-5.656l1.5-1.5m6.328-1.328a4 4 0 000-5.656l-3-3a4 4 0 00-5.656 5.656l1.5 1.5"/></svg>
                                        @endswitch
                                        {{ $channel['label'] }}
                                    </a>
                                @empty
                                    <a href="{{ route('contact') }}?subject={{ $subject }}"
                                       class="flex w-full items-center justify-center gap-2 rounded-xl bg-crimson-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-crimson-800 hover:shadow-md">
                                        Inquire About This Event
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== REGISTRATION MODAL ===================== --}}
    @if($event->registration_enabled)
        <div x-data="{ open: false }"
             x-on:open-registration.window="open = true"
             x-on:keydown.escape.window="open = false"
             x-effect="document.body.style.overflow = open ? 'hidden' : ''"
             x-cloak>
            {{-- Overlay --}}
            <div x-show="open"
                 x-transition.opacity
                 class="fixed inset-0 z-[60] bg-zinc-950/70 backdrop-blur-sm"
                 x-on:click="open = false"
                 aria-hidden="true"></div>

            {{-- Dialog --}}
            <div x-show="open"
                 x-transition
                 class="fixed inset-0 z-[70] flex items-start justify-center overflow-y-auto p-4 sm:items-center sm:p-6"
                 role="dialog" aria-modal="true" aria-label="Event registration">
                <div x-on:click.stop
                     class="relative my-8 w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-zinc-200 sm:p-8">
                    {{-- Close --}}
                    <button type="button" x-on:click="open = false"
                            class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full text-zinc-400 transition hover:bg-zinc-100 hover:text-zinc-700"
                            aria-label="Close">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <livewire:public.event-register :event="$event" :key="'event-register-'.$event->id" />
                </div>
            </div>
        </div>
    @endif

</x-layouts::website>
