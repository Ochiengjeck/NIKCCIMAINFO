{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Pillar: [name]"
    banner_image      : Banner background (PLACEHOLDER UNTIL UPLOADED)
    mandate           : Mandate description (text)
    responsibilities  : Newline-separated list
    programs          : Newline-separated list
    kpis              : Newline-separated list
--}}

@php
    $pillars = [
        'executive' => 'Executive & Institutional Leadership',
        'trade'     => 'Trade & Investment Facilitation',
        'policy'    => 'Policy & Research',
        'admin'     => 'Administration & Member Services',
    ];
@endphp

<x-layouts::website :title="($page?->title ?? ($pillars[$pillarSlug] ?? 'Pillar')).' — NiKCCIMA'">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden py-20 text-white">
        {{-- Background --}}
        @if($page?->section('banner_image'))
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('banner_image')) }}')">
                <div class="absolute inset-0 bg-brand-900/85"></div>
            </div>
        @else
            {{-- PLACEHOLDER — upload banner_image via Admin → CMS → Pages → the pillar page --}}
            <div class="absolute inset-0 bg-gradient-to-br from-brand-950 via-brand-900 to-brand-800">
                <svg class="absolute inset-0 h-full w-full opacity-[0.06]" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="dp-pillar" x="0" y="0" width="48" height="48" patternUnits="userSpaceOnUse">
                            <path d="M0 24h48M24 0v48" stroke="white" stroke-width="1" fill="none"/>
                            <circle cx="24" cy="24" r="2" fill="white"/>
                            <circle cx="0" cy="0" r="1.5" fill="white"/>
                            <circle cx="48" cy="48" r="1.5" fill="white"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dp-pillar)"/>
                </svg>
            </div>
            <div class="pointer-events-none absolute -right-24 top-0 h-80 w-80 rounded-full bg-white/5 blur-3xl"></div>
        @endif

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="mb-5 flex items-center gap-2 text-sm text-brand-200" aria-label="Breadcrumb">
                <a href="{{ route('pillars') }}" class="hover:text-white transition-colors duration-150">Pillars</a>
                <svg class="h-3.5 w-3.5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-brand-100">{{ $page?->title ?? ($pillars[$pillarSlug] ?? 'Pillar Details') }}</span>
            </nav>

            <span class="mb-4 inline-flex rounded-full bg-crimson-700 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">Pillar</span>
            <h1 class="font-serif text-4xl font-bold lg:text-5xl max-w-3xl leading-tight">
                {{ $page?->title ?? ($pillars[$pillarSlug] ?? 'Pillar Details') }}
            </h1>
        </div>
    </section>

    {{-- ===================== CONTENT BODY ===================== --}}
    <section class="py-16 bg-zinc-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-8 lg:flex-row">

                {{-- -------- LEFT SIDEBAR: Pillar Navigation -------- --}}
                <aside class="shrink-0 lg:w-56 lg:sticky lg:top-24 lg:self-start">
                    <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-widest text-zinc-400">Our Pillars</p>
                    <nav class="space-y-0.5" aria-label="Pillar navigation">
                        @foreach($pillars as $slug => $name)
                            <a href="{{ route('pillars.show', $slug) }}"
                               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-150
                                      {{ $pillarSlug === $slug
                                          ? 'bg-brand-50 text-brand-700 font-semibold'
                                          : 'text-zinc-600 hover:bg-white hover:text-zinc-900' }}">
                                @if($pillarSlug === $slug)
                                    <span class="h-1.5 w-1.5 rounded-full bg-brand-600 shrink-0"></span>
                                @else
                                    <span class="h-1.5 w-1.5 rounded-full bg-zinc-300 shrink-0"></span>
                                @endif
                                {{ $name }}
                            </a>
                        @endforeach
                    </nav>

                    {{-- Back link (mobile helper shown below sidebar on small screens) --}}
                    <div class="mt-6 hidden lg:block">
                        <a href="{{ route('pillars') }}"
                           class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-400 hover:text-brand-600 transition-colors duration-150">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            All Pillars
                        </a>
                    </div>
                </aside>

                {{-- -------- MAIN CONTENT -------- --}}
                <div class="min-w-0 flex-1">

                    @if($page)

                        {{-- Mandate Block --}}
                        @if($page->section('mandate'))
                            <div class="mb-8 rounded-2xl border border-brand-200 bg-brand-50 p-8">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-700 text-white mt-0.5">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="mb-3 flex items-center gap-3">
                                            <span class="rounded-full bg-crimson-700 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white">Mandate</span>
                                        </div>
                                        <p class="text-brand-900 leading-relaxed text-base">
                                            {{ $page->section('mandate') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid gap-8 sm:grid-cols-2">

                            {{-- Responsibilities --}}
                            @if($page->section('responsibilities'))
                                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                                    <div class="mb-5 flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-100">
                                            <svg class="h-4.5 w-4.5 h-5 w-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <h2 class="font-serif text-lg font-bold text-zinc-900">Responsibilities</h2>
                                    </div>
                                    <ul class="space-y-3">
                                        @foreach(array_filter(explode("\n", $page->section('responsibilities'))) as $item)
                                            <li class="flex items-start gap-3 text-sm text-zinc-600 leading-relaxed">
                                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                                {{ trim($item) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Key Programs --}}
                            @if($page->section('programs'))
                                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                                    <div class="mb-5 flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-100">
                                            <svg class="h-5 w-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
                                            </svg>
                                        </div>
                                        <h2 class="font-serif text-lg font-bold text-zinc-900">Key Programs</h2>
                                    </div>
                                    <ul class="space-y-3">
                                        @foreach(array_filter(explode("\n", $page->section('programs'))) as $item)
                                            <li class="flex items-start gap-3 text-sm text-zinc-600 leading-relaxed">
                                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                                {{ trim($item) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        </div>

                        {{-- KPIs --}}
                        @if($page->section('kpis'))
                            <div class="mt-8">
                                <div class="mb-5 flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-100">
                                        <svg class="h-5 w-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                                        </svg>
                                    </div>
                                    <h2 class="font-serif text-lg font-bold text-zinc-900">Key Performance Indicators</h2>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach(array_filter(explode("\n", $page->section('kpis'))) as $kpi)
                                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3.5 text-sm font-medium text-zinc-700 leading-snug">
                                            {{ trim($kpi) }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    @else
                        {{-- Content coming soon --}}
                        <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-white py-20 px-8 text-center">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-zinc-100">
                                <svg class="h-7 w-7 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-serif text-lg font-semibold text-zinc-800 mb-2">Content Coming Soon</h3>
                            <p class="text-sm text-zinc-500 max-w-xs">
                                This pillar page is being prepared. Please check back shortly or contact us for more information.
                            </p>
                            <a href="{{ route('contact') }}"
                               class="mt-6 inline-flex items-center gap-2 rounded-full bg-crimson-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-crimson-800 transition-colors duration-200">
                                Contact Us
                            </a>
                        </div>
                    @endif

                    {{-- -------- Bottom Navigation -------- --}}
                    <div class="mt-10 flex flex-wrap items-center justify-between gap-4 border-t border-zinc-200 pt-6">
                        <a href="{{ route('pillars') }}"
                           class="inline-flex items-center gap-2 rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm font-medium text-zinc-700 hover:border-brand-300 hover:text-brand-700 transition-colors duration-150">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            All Pillars
                        </a>

                        {{-- Sibling pillar navigation --}}
                        <div class="flex flex-wrap gap-2">
                            @foreach($pillars as $slug => $name)
                                @if($slug !== $pillarSlug)
                                    <a href="{{ route('pillars.show', $slug) }}"
                                       class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-200 bg-white px-3 py-2 text-xs font-medium text-zinc-500 hover:border-brand-300 hover:text-brand-700 transition-colors duration-150">
                                        {{ $name }}
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

</x-layouts::website>
