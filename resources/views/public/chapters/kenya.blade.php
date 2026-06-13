{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Kenya Chapter"
    banner_image  : Banner background image (upload via Media Library — PLACEHOLDER UNTIL UPLOADED)
    heading       : Chapter name heading (default: "Kenya Chapter")
    description   : Chapter description paragraph
    address       : Office address (Nairobi)
    email         : Contact email
    phone         : Phone number
    office_hours  : Office hours (NEW KEY — e.g. "Mon–Fri, 8am–5pm EAT")
    initiatives   : Comma-separated list of chapter initiatives

    CONTACT DETAILS (address/email/phone) are GLOBAL — Admin → Settings → Contact Details
    (kenya_address/phone/email) — same source the contact page & footer read.
--}}
@php
    $chapterAddress = \App\Models\SystemSetting::get('kenya_address', 'Nairobi, Republic of Kenya');
    $chapterEmail   = \App\Models\SystemSetting::get('kenya_email', 'kenya@nikccima.org');
    $chapterPhone   = \App\Models\SystemSetting::get('kenya_phone', '');
@endphp
<x-layouts::website
    :title="$page?->meta_title ?: 'Kenya Chapter — NiKCCIMA'"
    :meta-description="$page?->meta_description ?: $page?->section('description', 'The Kenya Chapter of NiKCCIMA — leadership, initiatives and events driving AfCFTA corridor trade from Nairobi.')">

    {{-- Hero — Red accents for Kenya --}}
    <section class="relative overflow-hidden py-24 text-white">
        @if($page?->section('banner_image'))
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('banner_image')) }}')">
                <div class="absolute inset-0 bg-crimson-800/85"></div>
            </div>
        @else
            {{-- PLACEHOLDER — upload banner_image via Admin → CMS → Pages → Kenya Chapter --}}
            <div class="absolute inset-0 bg-gradient-to-br from-crimson-800 to-crimson-800">
                <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg">
                    <defs><pattern id="dp-ke" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.5" fill="white"/></pattern></defs>
                    <rect width="100%" height="100%" fill="url(#dp-ke)"/>
                </svg>
            </div>
        @endif
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span class="mb-4 inline-flex items-center gap-2 rounded-full bg-crimson-700 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">🇰🇪 Kenya</span>
            <h1 class="font-serif text-4xl font-bold lg:text-6xl">{{ $page?->section('heading', 'Kenya Chapter') }}</h1>
            @if($page?->section('description'))
                <p class="mt-4 max-w-2xl text-lg text-crimson-100">{{ $page->section('description') }}</p>
            @endif
        </div>
    </section>

    {{-- ===================== CHAPTER PHOTO ===================== --}}
    {{-- CMS: chapter-kenya → chapter_photo (upload 800×600 office/city photo) --}}
    @if($page?->section('chapter_photo'))
        <section class="py-16 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-widest text-crimson-700">🇰🇪 Kenya Chapter</span>
                        <h2 class="mt-2 font-serif text-3xl font-bold text-zinc-900 lg:text-4xl">
                            {{ $page?->section('heading', 'Kenya Chapter') }}
                        </h2>
                        @if($page?->section('description'))
                            <p class="mt-4 text-base leading-relaxed text-zinc-600">
                                {{ $page->section('description') }}
                            </p>
                        @endif
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-xl bg-crimson-800 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-crimson-800">
                                Contact Kenya Chapter
                            </a>
                        </div>
                    </div>
                    <div class="relative">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('chapter_photo')) }}"
                            alt="NiKCCIMA Kenya Chapter"
                            class="w-full rounded-2xl shadow-2xl object-cover"
                        />
                        <div class="absolute -bottom-4 -right-4 -z-10 h-full w-full rounded-2xl border-2 border-crimson-100"></div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Office Info --}}
    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-crimson-50">
                        <svg class="h-5 w-5 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-zinc-400">Address</p>
                    <p class="text-sm text-zinc-700">{{ $chapterAddress }}</p>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-crimson-50">
                        <svg class="h-5 w-5 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-zinc-400">Email</p>
                    <a href="mailto:{{ $chapterEmail }}" class="text-sm text-crimson-700 hover:underline">{{ $chapterEmail }}</a>
                </div>
                @if($chapterPhone)
                    <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6">
                        <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-crimson-50">
                            <svg class="h-5 w-5 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-zinc-400">Phone</p>
                        <p class="text-sm text-zinc-700">{{ $chapterPhone }}</p>
                    </div>
                @endif
            </div>
            @if($page?->section('office_hours'))
                {{-- CMS: chapter-kenya → office_hours --}}
                <p class="mt-6 text-sm text-zinc-500"><span class="font-medium">Office Hours:</span> {{ $page->section('office_hours') }}</p>
            @endif
        </div>
    </section>

    {{-- Initiatives --}}
    @if($page?->section('initiatives'))
        {{-- CMS: chapter-kenya → initiatives (comma-separated) --}}
        <section class="bg-zinc-50 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="mb-6 font-serif text-2xl font-bold text-zinc-900">Chapter Initiatives</h2>
                <div class="flex flex-wrap gap-3">
                    @foreach(array_filter(explode(',', $page->section('initiatives'))) as $initiative)
                        <span class="rounded-full border border-crimson-100 bg-crimson-50 px-4 py-1.5 text-sm font-medium text-crimson-800">{{ trim($initiative) }}</span>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Leadership --}}
    @if($profiles->isNotEmpty())
        <section class="bg-white py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-10">
                    <span class="mb-2 inline-block text-xs font-semibold uppercase tracking-widest text-crimson-700">People</span>
                    <h2 class="font-serif text-3xl font-bold text-zinc-900">Chapter Leadership</h2>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($profiles as $profile)
                        <div class="rounded-2xl border border-zinc-200 bg-white p-6 text-center transition hover:shadow-md">
                            @if($profile->photoUrl())
                                <img src="{{ $profile->photoUrl() }}" alt="{{ $profile->name }}" class="mx-auto mb-4 h-24 w-24 rounded-full object-cover ring-4 ring-crimson-100">
                            @else
                                <div class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-crimson-700 to-crimson-800 ring-4 ring-crimson-100">
                                    <span class="text-2xl font-bold text-white">{{ strtoupper(substr($profile->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <h3 class="font-semibold text-zinc-900">{{ $profile->name }}</h3>
                            <p class="mt-1 text-sm text-crimson-700">{{ $profile->position }}</p>
                            @if($profile->email)
                                <a href="mailto:{{ $profile->email }}" class="mt-3 inline-block text-xs text-crimson-700 hover:underline">{{ $profile->email }}</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Upcoming Events --}}
    @if($events->isNotEmpty())
        <section class="bg-zinc-50 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="mb-8 font-serif text-2xl font-bold text-zinc-900">Upcoming Chapter Events</h2>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($events as $event)
                        <a href="{{ route('events.show', $event->id) }}" class="group rounded-2xl border border-zinc-200 bg-white p-6 transition hover:border-crimson-100 hover:shadow-md">
                            <p class="mb-2 text-xs font-medium text-crimson-700">{{ $event->starts_at->format('d M Y') }}</p>
                            <h3 class="font-semibold text-zinc-900 transition group-hover:text-crimson-800 line-clamp-2">{{ $event->title }}</h3>
                            @if($event->venue)
                                <p class="mt-2 flex items-center gap-1.5 text-sm text-zinc-500">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $event->venue }}
                                </p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="bg-crimson-800 py-14 text-white">
        <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="font-serif text-2xl font-bold">Connect with the Kenya Chapter</h2>
            <p class="mt-3 text-crimson-100">Get in touch with our Nairobi office for trade inquiries, membership, or chapter events.</p>
            <a href="{{ route('contact') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3 text-sm font-semibold text-crimson-800 transition hover:bg-crimson-50">
                Contact Us
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </section>

</x-layouts::website>
