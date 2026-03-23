{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Nigeria Chapter"
    banner_image  : Banner background (PLACEHOLDER UNTIL UPLOADED)
    heading       : Chapter name heading
    description   : Chapter description
    address       : Office address
    email         : Contact email
    phone         : Phone number
    office_hours  : Office hours (NEW KEY)
    initiatives   : Comma-separated national initiatives
--}}
<x-layouts::website :title="'Nigeria Chapter — NiKCCIMA'">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden py-24 text-white lg:py-32">
        {{-- Background --}}
        @if($page?->section('banner_image'))
            <div class="absolute inset-0">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('banner_image')) }}"
                     alt=""
                     class="h-full w-full object-cover"
                     aria-hidden="true">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-950/90 via-brand-900/80 to-[#7a1e22]/70"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-brand-950 via-brand-900 to-[#7a1e22]"></div>
        @endif

        {{-- Dot pattern --}}
        <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <defs>
                <pattern id="dp-nigeria" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dp-nigeria)"/>
        </svg>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <span class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-[#922529]">
                    &#127475;&#127468; Nigeria
                </span>
                <h1 class="mb-5 font-serif text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                    {{ $page?->section('heading', 'Nigeria Chapter') }}
                </h1>
                @if($page?->section('description'))
                    <p class="max-w-2xl text-lg text-[#922529] sm:text-xl">
                        {{ $page->section('description') }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    {{-- ===================== CHAPTER PHOTO ===================== --}}
    {{-- CMS: chapter-nigeria → chapter_photo (upload 800×600 office/city photo) --}}
    @if($page?->section('chapter_photo'))
        <section class="py-16 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <div class="order-2 lg:order-1">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('chapter_photo')) }}"
                            alt="NiKCCIMA Nigeria Chapter"
                            class="w-full rounded-2xl shadow-2xl object-cover"
                        />
                    </div>
                    <div class="order-1 lg:order-2">
                        <span class="text-xs font-semibold uppercase tracking-widest text-[#922529]">🇳🇬 Nigeria Chapter</span>
                        <h2 class="mt-2 font-serif text-3xl font-bold text-zinc-900 lg:text-4xl">
                            {{ $page?->section('heading', 'Nigeria Chapter') }}
                        </h2>
                        @if($page?->section('description'))
                            <p class="mt-4 text-base leading-relaxed text-zinc-600">
                                {{ $page->section('description') }}
                            </p>
                        @endif
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#922529] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#922529]">
                                Contact Nigeria Chapter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== OFFICE INFO ===================== --}}
    <section class="py-16 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="grid gap-6 sm:grid-cols-3">

                {{-- Address --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-[#A8DCAB]/20">
                        <svg class="h-5 w-5 text-[#922529]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-zinc-400">Address</p>
                    <p class="text-sm text-zinc-700">
                        {{ $page?->section('address', 'Abuja, Federal Capital Territory, Nigeria') }}
                    </p>
                </div>

                {{-- Email --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-[#A8DCAB]/20">
                        <svg class="h-5 w-5 text-[#922529]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-zinc-400">Email</p>
                    <a href="mailto:{{ $page?->section('email', 'nigeria@nikccima.org') }}"
                       class="text-sm font-medium text-[#922529] transition hover:text-[#922529] hover:underline">
                        {{ $page?->section('email', 'nigeria@nikccima.org') }}
                    </a>
                </div>

                {{-- Phone (only if set) --}}
                @if($page?->section('phone'))
                    <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-[#A8DCAB]/20">
                            <svg class="h-5 w-5 text-[#922529]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-zinc-400">Phone</p>
                        <p class="text-sm font-medium text-zinc-700">{{ $page->section('phone') }}</p>
                    </div>
                @endif

            </div>

            {{-- Office hours --}}
            @if($page?->section('office_hours'))
                <p class="mt-5 text-sm text-zinc-500">
                    <span class="font-medium text-zinc-700">Office Hours:</span>
                    {{ $page->section('office_hours') }}
                </p>
            @endif

        </div>
    </section>

    {{-- ===================== INITIATIVES ===================== --}}
    @if($page?->section('initiatives'))
        <section class="py-12 bg-zinc-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-6">
                    <span class="mb-2 inline-block text-xs font-semibold uppercase tracking-widest text-[#922529]">
                        Active Programs
                    </span>
                    <h2 class="font-serif text-2xl font-bold text-zinc-900">
                        National Initiatives
                    </h2>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach(array_filter(explode(',', $page->section('initiatives'))) as $initiative)
                        <span class="rounded-full border border-[#A8DCAB]/30 bg-[#A8DCAB]/20 px-4 py-1.5 text-sm font-medium text-[#922529]">
                            {{ trim($initiative) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== LEADERSHIP ===================== --}}
    @if($profiles->isNotEmpty())
        <section class="py-20 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div class="mb-10">
                    <span class="mb-2 inline-block text-xs font-semibold uppercase tracking-widest text-[#922529]">
                        People
                    </span>
                    <h2 class="font-serif text-3xl font-bold text-zinc-900">
                        Chapter Leadership
                    </h2>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($profiles as $profile)
                        <div class="rounded-2xl border border-zinc-200 bg-white p-6 text-center transition hover:-translate-y-0.5 hover:shadow-md">
                            @if($profile->photoUrl())
                                <img src="{{ $profile->photoUrl() }}"
                                     alt="{{ $profile->name }}"
                                     class="mx-auto mb-4 h-20 w-20 rounded-full object-cover ring-4 ring-[#922529]">
                            @else
                                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-brand-700 to-[#7a1e22] ring-4 ring-[#922529]">
                                    <span class="font-serif text-2xl font-bold text-white">
                                        {{ strtoupper(substr($profile->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <h3 class="font-semibold text-zinc-900">{{ $profile->name }}</h3>
                            <p class="mt-1 text-sm text-[#922529]">{{ $profile->position }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== UPCOMING EVENTS ===================== --}}
    @if($events->isNotEmpty())
        <section class="py-16 bg-zinc-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                <div class="mb-8 flex items-end justify-between">
                    <div>
                        <span class="mb-2 inline-block text-xs font-semibold uppercase tracking-widest text-[#922529]">
                            Upcoming
                        </span>
                        <h2 class="font-serif text-2xl font-bold text-zinc-900">
                            Chapter Events
                        </h2>
                    </div>
                    <a href="{{ route('events.index') }}"
                       class="text-sm font-medium text-[#922529] transition hover:text-[#922529]">
                        All Events &rarr;
                    </a>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($events as $event)
                        <a href="{{ route('events.show', $event->id) }}"
                           class="group rounded-2xl border border-zinc-200 bg-white p-5 transition hover:border-[#A8DCAB]/30 hover:shadow-md">
                            <p class="mb-1.5 text-xs font-medium text-zinc-400">
                                {{ $event->starts_at->format('d M Y') }}
                            </p>
                            <h3 class="font-semibold text-zinc-900 line-clamp-2 transition group-hover:text-[#922529]">
                                {{ $event->title }}
                            </h3>
                            @if($event->venue)
                                <div class="mt-2 flex items-center gap-1.5 text-sm text-zinc-500">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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

    {{-- ===================== FOOTER CTA ===================== --}}
    <section class="relative overflow-hidden py-16 bg-[#922529] text-white">
        <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <defs>
                <pattern id="dp-nigeria-cta" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dp-nigeria-cta)"/>
        </svg>
        <div class="relative mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="mb-4 font-serif text-2xl font-bold sm:text-3xl">
                Connect with the Nigeria Chapter
            </h2>
            <p class="mb-8 text-[#922529]">
                Reach out to our Abuja office to learn more about membership, trade programmes, and upcoming events.
            </p>
            <a href="{{ route('contact') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3.5 text-sm font-bold text-[#922529] transition hover:bg-[#A8DCAB]/20 hover:shadow-md">
                Get in Touch
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </section>

</x-layouts::website>
