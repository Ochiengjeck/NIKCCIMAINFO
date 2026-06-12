@props([
    'title' => null,
    'metaDescription' => null,
    'ogImage' => null,
    'ogType' => 'website',
    'noindex' => false,
    'jsonLd' => null,
    'transparentNav' => false,
])
@php
    $seoTitle = $title ?? 'NiKCCIMA — Nigeria-Kenya Chamber of Commerce';
    $siteName = \App\Models\SystemSetting::get('site_name', 'NiKCCIMA');
    $seoDescription = $metaDescription
        ?: \App\Models\SystemSetting::get('seo_default_description', 'The Nigeria-Kenya Chamber of Commerce, Industry, Mines & Agriculture — driving AfCFTA corridor trade, investment and policy between Nigeria and Kenya.');

    // Resolve the social share image (absolute URL): page-specific → settings → logo.
    $shareImage = $ogImage ?: \App\Models\SystemSetting::get('seo_share_image') ?: \App\Models\SystemSetting::get('site_logo');
    if ($shareImage && ! \Illuminate\Support\Str::startsWith($shareImage, ['http://', 'https://'])) {
        $shareImage = \Illuminate\Support\Facades\Storage::disk('public')->url($shareImage);
    }

    $gaId = \App\Models\SystemSetting::get('ga_measurement_id');
    $searchConsole = \App\Models\SystemSetting::get('search_console_verification');
    $orgLogo = \App\Models\SystemSetting::get('site_logo');
    $orgLogo = $orgLogo ? \Illuminate\Support\Facades\Storage::disk('public')->url($orgLogo) : null;

    $organizationJsonLd = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Nigeria-Kenya Chamber of Commerce, Industry, Mines & Agriculture',
        'alternateName' => 'NiKCCIMA',
        'url' => route('home'),
        'logo' => $orgLogo,
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
            'email' => \App\Models\SystemSetting::get('nigeria_email', 'nigeria@nikccima.org'),
            'telephone' => \App\Models\SystemSetting::get('nigeria_phone', ''),
        ],
    ]);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ $seoTitle }}</title>
        <meta name="description" content="{{ $seoDescription }}">
        <link rel="canonical" href="{{ url()->current() }}">
        <meta name="robots" content="{{ $noindex ? 'noindex,nofollow' : 'index,follow' }}">
        <meta name="theme-color" content="#9f1239">

        {{-- Open Graph --}}
        <meta property="og:type" content="{{ $ogType }}">
        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:title" content="{{ $seoTitle }}">
        <meta property="og:description" content="{{ $seoDescription }}">
        <meta property="og:url" content="{{ url()->current() }}">
        @if($shareImage)<meta property="og:image" content="{{ $shareImage }}">@endif

        {{-- Twitter --}}
        <meta name="twitter:card" content="{{ $shareImage ? 'summary_large_image' : 'summary' }}">
        <meta name="twitter:title" content="{{ $seoTitle }}">
        <meta name="twitter:description" content="{{ $seoDescription }}">
        @if($shareImage)<meta name="twitter:image" content="{{ $shareImage }}">@endif

        @if($searchConsole)<meta name="google-site-verification" content="{{ $searchConsole }}">@endif

        {{-- Organization structured data --}}
        <script type="application/ld+json">@json($organizationJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>

        {{-- Page-specific structured data --}}
        @if($jsonLd)
            <script type="application/ld+json">@json($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
        @endif

        {{-- Google Analytics (GA4) --}}
        @if($gaId)
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ $gaId }}');
            </script>
        @endif

        {{-- Playfair Display from fonts.bunny.net --}}
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @include('partials.head')

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="min-h-screen bg-white text-zinc-900 antialiased">

        {{-- ===================== STICKY HEADER ===================== --}}
        <header
            x-data="{
                hidden: false,
                atTop: {{ $transparentNav ? 'true' : 'false' }},
                lastY: 0,
                mobileOpen: false,
                onScroll() {
                    const y = window.scrollY;
                    this.atTop = {{ $transparentNav ? 'y < 50' : 'false' }};
                    this.hidden = y > 100 && y > this.lastY;
                    this.lastY = y;
                }
            }"
            @scroll.window="onScroll()"
            :class="[
                hidden ? '-translate-y-full' : 'translate-y-0',
                atTop ? 'bg-transparent is-hero-top' : 'bg-white/95 backdrop-blur-md border-b border-crimson-700/20 shadow-sm'
            ]"
            class="sticky top-0 z-50 transition-all duration-300 ease-in-out">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">

                {{-- Logo --}}
                @php $siteLogo = \App\Models\SystemSetting::get('site_logo'); @endphp
                <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3 group">
                    @if($siteLogo)
                        <img src="{{ Storage::disk('public')->url($siteLogo) }}" alt="NiKCCIMA" class="h-20 w-auto object-contain">
                    @else
                        <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-brand-700 text-white shadow-sm group-hover:bg-brand-800 transition-colors">
                            <span class="text-lg font-bold font-serif tracking-tight">NK</span>
                        </div>
                        <div class="hidden sm:block">
                            <span class="logo-name block text-lg font-bold tracking-wide text-zinc-900 font-serif transition-colors duration-300">NiKCCIMA</span>
                            <span class="logo-subtitle block text-sm text-zinc-500 transition-colors duration-300">Nigeria-Kenya Chamber</span>
                        </div>
                    @endif
                </a>

                {{-- -------- Desktop Navigation -------- --}}
                <nav class="desktop-nav hidden items-center gap-0.5 xl:flex" aria-label="Main navigation">

                    {{-- Home --}}
                    <a href="{{ route('home') }}"
                       class="rounded-lg px-4 py-2.5 text-base font-medium transition-colors
                              {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-700' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}">
                        Home
                    </a>

                    {{-- About --}}
                    <a href="{{ route('about') }}"
                       class="rounded-lg px-4 py-2.5 text-base font-medium transition-colors
                              {{ request()->routeIs('about') ? 'bg-brand-50 text-brand-700' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}">
                        About
                    </a>

                    {{-- What We Do dropdown --}}
                    <div class="relative"
                         x-data="{ open: false }"
                         @mouseenter="open = true"
                         @mouseleave="open = false"
                         @click.outside="open = false">
                        <button @click="open = !open"
                                :aria-expanded="open"
                                class="nav-link inline-flex items-center gap-1 rounded-lg px-4 py-2.5 text-base font-medium transition-colors
                                       {{ request()->routeIs('pillars*', 'trade', 'policy') ? 'bg-brand-50 text-brand-700' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}">
                            What We Do
                            <svg class="h-3.5 w-3.5 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute left-0 top-full mt-1 w-56 rounded-xl border border-zinc-200 bg-white py-2 shadow-xl"
                             role="menu">
                            <a href="{{ route('pillars') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors {{ request()->routeIs('pillars*') ? 'text-brand-700 bg-brand-50' : '' }}"
                               role="menuitem">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-100 text-brand-700 shrink-0">
                                    {{-- Columns / pillars icon --}}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                </span>
                                <span>
                                    <span class="block font-medium">Our Pillars</span>
                                    <span class="block text-xs text-zinc-400">Five strategic corridors</span>
                                </span>
                            </a>
                            <a href="{{ route('trade') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors {{ request()->routeIs('trade') ? 'text-brand-700 bg-brand-50' : '' }}"
                               role="menuitem">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-100 text-brand-700 shrink-0">
                                    {{-- Chart / trade icon --}}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </span>
                                <span>
                                    <span class="block font-medium">Trade & Investment</span>
                                    <span class="block text-xs text-zinc-400">AfCFTA corridor data</span>
                                </span>
                            </a>
                            <a href="{{ route('policy') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors {{ request()->routeIs('policy') ? 'text-brand-700 bg-brand-50' : '' }}"
                               role="menuitem">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-700 shrink-0">
                                    {{-- Document / policy icon --}}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </span>
                                <span>
                                    <span class="block font-medium">Policy & Research</span>
                                    <span class="block text-xs text-zinc-400">Publications & briefs</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    {{-- Membership --}}
                    <a href="{{ route('membership') }}"
                       class="rounded-lg px-4 py-2.5 text-base font-medium transition-colors
                              {{ request()->routeIs('membership*') ? 'bg-brand-50 text-brand-700' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}">
                        Membership
                    </a>

                    {{-- Events --}}
                    <a href="{{ route('events.index') }}"
                       class="rounded-lg px-4 py-2.5 text-base font-medium transition-colors
                              {{ request()->routeIs('events*') ? 'bg-brand-50 text-brand-700' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}">
                        Events
                    </a>

                    {{-- Chapters dropdown --}}
                    <div class="relative"
                         x-data="{ open: false }"
                         @mouseenter="open = true"
                         @mouseleave="open = false"
                         @click.outside="open = false">
                        <button @click="open = !open"
                                :aria-expanded="open"
                                class="nav-link inline-flex items-center gap-1 rounded-lg px-4 py-2.5 text-base font-medium transition-colors
                                       {{ request()->routeIs('chapters*') ? 'bg-brand-50 text-brand-700' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}">
                            Chapters
                            <svg class="h-3.5 w-3.5 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute left-0 top-full mt-1 w-48 rounded-xl border border-zinc-200 bg-white py-1.5 shadow-xl"
                             role="menu">
                            <a href="{{ route('chapters.nigeria') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors {{ request()->routeIs('chapters.nigeria') ? 'text-brand-700 bg-brand-50' : '' }}"
                               role="menuitem">
                                <span class="text-base leading-none">&#127475;&#127468;</span>
                                <span class="font-medium">Nigeria Chapter</span>
                            </a>
                            <a href="{{ route('chapters.kenya') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors {{ request()->routeIs('chapters.kenya') ? 'text-crimson-700 bg-crimson-50' : '' }}"
                               role="menuitem">
                                <span class="text-base leading-none">&#127472;&#127466;</span>
                                <span class="font-medium">Kenya Chapter</span>
                            </a>
                        </div>
                    </div>

                    {{-- Blog --}}
                    <a href="{{ route('blog.index') }}"
                       class="rounded-lg px-4 py-2.5 text-base font-medium transition-colors
                              {{ request()->routeIs('blog.*') ? 'bg-brand-50 text-brand-700' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}">
                        Blog
                    </a>

                    {{-- Contact --}}
                    <a href="{{ route('contact') }}"
                       class="rounded-lg px-4 py-2.5 text-base font-medium transition-colors
                              {{ request()->routeIs('contact') ? 'bg-brand-50 text-brand-700' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900' }}">
                        Contact
                    </a>
                </nav>

                {{-- -------- Right-side controls -------- --}}
                <div class="flex items-center gap-2">

                    {{-- Apply Now CTA --}}
                    <a href="{{ route('membership.apply') }}"
                       class="hidden rounded-full bg-crimson-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-crimson-800 transition-colors lg:inline-block">
                        Apply Now
                    </a>

                    {{-- Member Login / Dashboard button --}}
                    @auth
                        <a href="{{ route('admin.dashboard') }}"
                           class="hidden rounded-full bg-brand-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-800 transition-colors lg:inline-block">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="hidden rounded-full bg-crimson-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-crimson-800 transition-colors lg:inline-block">
                            Member Login
                        </a>
                    @endauth

                    {{-- Mobile menu toggle --}}
                    <button @click="mobileOpen = !mobileOpen"
                            :aria-expanded="mobileOpen"
                            aria-label="Toggle menu"
                            class="header-icon-btn rounded-lg p-2 text-zinc-600 hover:bg-zinc-50 transition-colors xl:hidden">
                        <svg x-show="!mobileOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- -------- Mobile Navigation -------- --}}
            <div x-show="mobileOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="border-t border-zinc-100 bg-white px-4 pb-4 pt-2 xl:hidden"
                 @click.outside="mobileOpen = false">
                <nav class="flex flex-col gap-0.5" aria-label="Mobile navigation">

                    {{-- Main links --}}
                    <a href="{{ route('home') }}"
                       class="rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-700' : 'text-zinc-700 hover:bg-zinc-50' }}">
                        Home
                    </a>
                    <a href="{{ route('about') }}"
                       class="rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'bg-brand-50 text-brand-700' : 'text-zinc-700 hover:bg-zinc-50' }}">
                        About
                    </a>

                    {{-- What We Do section --}}
                    <div class="border-t border-zinc-100 my-1 pt-1">
                        <p class="px-3 pb-1 pt-1 text-xs font-semibold uppercase tracking-wider text-zinc-400">What We Do</p>
                        <a href="{{ route('pillars') }}"
                           class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('pillars*') ? 'bg-brand-50 text-brand-700' : 'text-zinc-700 hover:bg-zinc-50' }}">
                            <svg class="h-4 w-4 shrink-0 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            Our Pillars
                        </a>
                        <a href="{{ route('trade') }}"
                           class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('trade') ? 'bg-brand-50 text-brand-700' : 'text-zinc-700 hover:bg-zinc-50' }}">
                            <svg class="h-4 w-4 shrink-0 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Trade & Investment
                        </a>
                        <a href="{{ route('policy') }}"
                           class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('policy') ? 'bg-brand-50 text-brand-700' : 'text-zinc-700 hover:bg-zinc-50' }}">
                            <svg class="h-4 w-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Policy & Research
                        </a>
                    </div>

                    {{-- Membership & Events --}}
                    <div class="border-t border-zinc-100 my-1 pt-1">
                        <a href="{{ route('membership') }}"
                           class="rounded-lg px-3 py-2.5 text-sm font-medium transition-colors block {{ request()->routeIs('membership*') ? 'bg-brand-50 text-brand-700' : 'text-zinc-700 hover:bg-zinc-50' }}">
                            Membership
                        </a>
                        <a href="{{ route('events.index') }}"
                           class="rounded-lg px-3 py-2.5 text-sm font-medium transition-colors block {{ request()->routeIs('events*') ? 'bg-brand-50 text-brand-700' : 'text-zinc-700 hover:bg-zinc-50' }}">
                            Events & Missions
                        </a>
                    </div>

                    {{-- Chapters section --}}
                    <div class="border-t border-zinc-100 my-1 pt-1">
                        <p class="px-3 pb-1 pt-1 text-xs font-semibold uppercase tracking-wider text-zinc-400">Chapters</p>
                        <a href="{{ route('chapters.nigeria') }}"
                           class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('chapters.nigeria') ? 'bg-brand-50 text-brand-700' : 'text-zinc-700 hover:bg-zinc-50' }}">
                            <span class="text-base leading-none">&#127475;&#127468;</span> Nigeria Chapter
                        </a>
                        <a href="{{ route('chapters.kenya') }}"
                           class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('chapters.kenya') ? 'bg-crimson-50 text-crimson-700' : 'text-zinc-700 hover:bg-zinc-50' }}">
                            <span class="text-base leading-none">&#127472;&#127466;</span> Kenya Chapter
                        </a>
                    </div>

                    {{-- Blog & Contact --}}
                    <div class="border-t border-zinc-100 my-1 pt-1">
                        <a href="{{ route('blog.index') }}"
                           class="rounded-lg px-3 py-2.5 text-sm font-medium transition-colors block {{ request()->routeIs('blog.*') ? 'bg-brand-50 text-brand-700' : 'text-zinc-700 hover:bg-zinc-50' }}">
                            Blog
                        </a>
                        <a href="{{ route('contact') }}"
                           class="rounded-lg px-3 py-2.5 text-sm font-medium transition-colors block {{ request()->routeIs('contact') ? 'bg-brand-50 text-brand-700' : 'text-zinc-700 hover:bg-zinc-50' }}">
                            Contact
                        </a>
                    </div>

                    {{-- CTA --}}
                    <div class="border-t border-zinc-100 my-1 pt-2">
                        @auth
                            <a href="{{ route('admin.dashboard') }}"
                               class="block rounded-full bg-brand-700 px-4 py-2.5 text-sm font-semibold text-white text-center hover:bg-brand-800 transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="block rounded-full bg-crimson-700 px-4 py-2.5 text-sm font-semibold text-white text-center hover:bg-crimson-800 transition-colors">
                                Member Login
                            </a>
                        @endauth
                    </div>
                </nav>
            </div>
        </header>

        {{-- ===================== MAIN CONTENT ===================== --}}
        <main>
            {{ $slot }}
        </main>

        {{-- ===================== FOOTER ===================== --}}
        @php
            $footerNgAddress = \App\Models\SystemSetting::get('nigeria_address', 'Abuja, Federal Capital Territory, Federal Republic of Nigeria');
            $footerNgPhone   = \App\Models\SystemSetting::get('nigeria_phone', '');
            $footerNgEmail   = \App\Models\SystemSetting::get('nigeria_email', 'nigeria@nikccima.org');
            $footerKeAddress = \App\Models\SystemSetting::get('kenya_address', 'Nairobi, Republic of Kenya');
            $footerKePhone   = \App\Models\SystemSetting::get('kenya_phone', '');
            $footerKeEmail   = \App\Models\SystemSetting::get('kenya_email', 'kenya@nikccima.org');
        @endphp
        <footer class="mt-16 bg-crimson-700 text-white">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

                {{-- 3-column grid --}}
                <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">

                    {{-- Column 1: Brand + Social --}}
                    <div>
                        <a href="{{ route('home') }}" class="mb-5 flex items-center gap-3 group">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-crimson-800 text-white text-xs font-bold font-serif shadow group-hover:bg-crimson-900 transition-colors">
                                NK
                            </div>
                            <div>
                                <span class="block text-base font-bold text-white font-serif tracking-wide">NiKCCIMA</span>
                                <span class="block text-xs text-brand-200">Nigeria-Kenya Chamber</span>
                            </div>
                        </a>
                        <p class="mb-5 text-sm text-white/80 leading-relaxed">
                            The Nigeria-Kenya Chamber of Commerce, Industry, Mines &amp; Agriculture — driving AfCFTA corridor execution and unlocking trade between Africa's two largest economies.
                        </p>

                        {{-- Quick links --}}
                        <div class="mb-6 flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-brand-100/80">
                            <a href="{{ route('about') }}" class="hover:text-white transition-colors">About</a>
                            <a href="{{ route('pillars') }}" class="hover:text-white transition-colors">Pillars</a>
                            <a href="{{ route('membership') }}" class="hover:text-white transition-colors">Membership</a>
                            <a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">Blog</a>
                            <a href="{{ route('downloads') }}" class="hover:text-white transition-colors">Downloads</a>
                            <a href="{{ route('leadership') }}" class="hover:text-white transition-colors">Leadership</a>
                            <a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact</a>
                        </div>

                        {{-- Social icons --}}
                        <div class="flex items-center gap-3">
                            {{-- Twitter / X --}}
                            <a href="#" aria-label="Follow us on X" class="flex h-8 w-8 items-center justify-center rounded-full bg-crimson-800 text-brand-200 hover:bg-crimson-900 hover:text-white transition-colors">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                            {{-- LinkedIn --}}
                            <a href="#" aria-label="Follow us on LinkedIn" class="flex h-8 w-8 items-center justify-center rounded-full bg-crimson-800 text-brand-200 hover:bg-crimson-900 hover:text-white transition-colors">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            {{-- Facebook --}}
                            <a href="#" aria-label="Follow us on Facebook" class="flex h-8 w-8 items-center justify-center rounded-full bg-crimson-800 text-brand-200 hover:bg-crimson-900 hover:text-white transition-colors">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Column 2: Nigeria Office --}}
                    <div>
                        <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-brand-200">
                            <span class="text-base leading-none">&#127475;&#127468;</span>
                            Nigeria Chapter
                        </h3>
                        <ul class="space-y-2 text-sm text-white/80">
                            @if($footerNgAddress)
                                <li class="flex items-start gap-2">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $footerNgAddress }}</span>
                                </li>
                            @endif
                            @if($footerNgPhone)
                                <li class="flex items-center gap-2">
                                    <svg class="h-4 w-4 shrink-0 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $footerNgPhone) }}" class="hover:text-white transition-colors">{{ $footerNgPhone }}</a>
                                </li>
                            @endif
                            @if($footerNgEmail)
                                <li class="flex items-center gap-2">
                                    <svg class="h-4 w-4 shrink-0 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <a href="mailto:{{ $footerNgEmail }}" class="hover:text-white transition-colors">{{ $footerNgEmail }}</a>
                                </li>
                            @endif
                        </ul>
                        <div class="mt-5">
                            <a href="{{ route('chapters.nigeria') }}"
                               class="inline-flex items-center gap-1.5 rounded-lg border border-brand-200/40 px-3 py-1.5 text-xs font-medium text-brand-200 hover:border-brand-200 hover:text-white transition-colors">
                                Visit Nigeria Chapter
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Column 3: Kenya Office + Links --}}
                    <div>
                        <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-crimson-200">
                            <span class="text-base leading-none">&#127472;&#127466;</span>
                            Kenya Chapter
                        </h3>
                        <ul class="space-y-2 text-sm text-white/80">
                            @if($footerKeAddress)
                                <li class="flex items-start gap-2">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-crimson-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $footerKeAddress }}</span>
                                </li>
                            @endif
                            @if($footerKePhone)
                                <li class="flex items-center gap-2">
                                    <svg class="h-4 w-4 shrink-0 text-crimson-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $footerKePhone) }}" class="hover:text-white transition-colors">{{ $footerKePhone }}</a>
                                </li>
                            @endif
                            @if($footerKeEmail)
                                <li class="flex items-center gap-2">
                                    <svg class="h-4 w-4 shrink-0 text-crimson-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <a href="mailto:{{ $footerKeEmail }}" class="hover:text-white transition-colors">{{ $footerKeEmail }}</a>
                                </li>
                            @endif
                        </ul>
                        <div class="mt-5">
                            <a href="{{ route('chapters.kenya') }}"
                               class="inline-flex items-center gap-1.5 rounded-lg border border-crimson-200/40 px-3 py-1.5 text-xs font-medium text-crimson-200 hover:border-crimson-100 hover:text-white transition-colors">
                                Visit Kenya Chapter
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        {{-- Additional links --}}
                        <div class="mt-6 border-t border-crimson-800 pt-5">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-brand-200">Resources</p>
                            <ul class="space-y-1.5 text-sm text-white/80">
                                <li><a href="{{ route('trade') }}" class="hover:text-white transition-colors">Trade &amp; Investment</a></li>
                                <li><a href="{{ route('policy') }}" class="hover:text-white transition-colors">Policy &amp; Research</a></li>
                                <li><a href="{{ route('events.index') }}" class="hover:text-white transition-colors">Events &amp; Missions</a></li>
                                <li><a href="{{ route('downloads') }}" class="hover:text-white transition-colors">Downloads</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Bottom bar --}}
                <div class="mt-10 border-t border-crimson-800 pt-6 flex flex-col items-center justify-between gap-3 sm:flex-row">
                    <p class="text-xs text-white/60">
                        &copy; {{ date('Y') }} NiKCCIMA. All rights reserved. Governed under the AfCFTA framework.
                    </p>
                    <p class="text-xs text-brand-200/60">
                        Nigeria-Kenya Chamber of Commerce, Industry, Mines &amp; Agriculture
                    </p>
                </div>
            </div>
        </footer>

        {{-- ===================== CHATBOT WIDGET ===================== --}}
        <livewire:public.chat-widget />

        @fluxScripts
    </body>
</html>
