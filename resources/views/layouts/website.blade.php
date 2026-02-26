<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        darkMode: (localStorage.getItem('nk-theme') === 'dark') || (localStorage.getItem('nk-theme') === null && window.matchMedia('(prefers-color-scheme: dark)').matches),
        mobileOpen: false,
        init() {
            this.$watch('darkMode', val => {
                localStorage.setItem('nk-theme', val ? 'dark' : 'light');
                document.documentElement.classList.toggle('dark', val);
            });
            document.documentElement.classList.toggle('dark', this.darkMode);
        }
    }"
    :class="{ 'dark': darkMode }"
    style="font-size: 75%"
>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ $title ?? 'NiKCCIMA — Nigeria-Kenya Chamber of Commerce' }}</title>
        @isset($metaDescription)
            <meta name="description" content="{{ $metaDescription }}">
        @endisset

        {{-- Playfair Display from fonts.bunny.net --}}
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @include('partials.head')

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="min-h-screen bg-white text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100 transition-colors duration-200">

        {{-- ===================== STICKY HEADER ===================== --}}
        <header class="sticky top-0 z-50 border-b border-zinc-200/80 bg-white/95 backdrop-blur-md dark:border-zinc-800/80 dark:bg-zinc-950/95 transition-colors duration-200">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">

                {{-- Logo --}}
                @php $siteLogo = \App\Models\SystemSetting::get('site_logo'); @endphp
                <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3 group">
                    @if($siteLogo)
                        <img src="{{ Storage::disk('public')->url($siteLogo) }}" alt="NiKCCIMA" class="h-10 w-auto object-contain">
                    @else
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-700 text-white shadow-sm group-hover:bg-green-800 transition-colors">
                            <span class="text-xs font-bold font-serif tracking-tight">NK</span>
                        </div>
                        <div class="hidden sm:block">
                            <span class="block text-sm font-bold tracking-wide text-zinc-900 dark:text-zinc-50 font-serif">NiKCCIMA</span>
                            <span class="block text-xs text-zinc-500 dark:text-zinc-400">Nigeria-Kenya Chamber</span>
                        </div>
                    @endif
                </a>

                {{-- -------- Desktop Navigation -------- --}}
                <nav class="hidden items-center gap-0.5 xl:flex" aria-label="Main navigation">

                    {{-- Home --}}
                    <a href="{{ route('home') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition-colors
                              {{ request()->routeIs('home') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100' }}">
                        Home
                    </a>

                    {{-- About --}}
                    <a href="{{ route('about') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition-colors
                              {{ request()->routeIs('about') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100' }}">
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
                                class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                                       {{ request()->routeIs('pillars*', 'trade', 'policy') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100' }}">
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
                             class="absolute left-0 top-full mt-1 w-56 rounded-xl border border-zinc-200 bg-white py-2 shadow-xl dark:border-zinc-700 dark:bg-zinc-900"
                             role="menu">
                            <a href="{{ route('pillars') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800 transition-colors {{ request()->routeIs('pillars*') ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-950/40' : '' }}"
                               role="menuitem">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-green-100 text-green-700 dark:bg-green-950 dark:text-green-400 shrink-0">
                                    {{-- Columns / pillars icon --}}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                </span>
                                <span>
                                    <span class="block font-medium">Our Pillars</span>
                                    <span class="block text-xs text-zinc-400 dark:text-zinc-500">Five strategic corridors</span>
                                </span>
                            </a>
                            <a href="{{ route('trade') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800 transition-colors {{ request()->routeIs('trade') ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-950/40' : '' }}"
                               role="menuitem">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400 shrink-0">
                                    {{-- Chart / trade icon --}}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </span>
                                <span>
                                    <span class="block font-medium">Trade & Investment</span>
                                    <span class="block text-xs text-zinc-400 dark:text-zinc-500">AfCFTA corridor data</span>
                                </span>
                            </a>
                            <a href="{{ route('policy') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800 transition-colors {{ request()->routeIs('policy') ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-950/40' : '' }}"
                               role="menuitem">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-400 shrink-0">
                                    {{-- Document / policy icon --}}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </span>
                                <span>
                                    <span class="block font-medium">Policy & Research</span>
                                    <span class="block text-xs text-zinc-400 dark:text-zinc-500">Publications & briefs</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    {{-- Membership --}}
                    <a href="{{ route('membership') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition-colors
                              {{ request()->routeIs('membership*') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100' }}">
                        Membership
                    </a>

                    {{-- Events --}}
                    <a href="{{ route('events.index') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition-colors
                              {{ request()->routeIs('events*') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100' }}">
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
                                class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                                       {{ request()->routeIs('chapters*') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100' }}">
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
                             class="absolute left-0 top-full mt-1 w-48 rounded-xl border border-zinc-200 bg-white py-1.5 shadow-xl dark:border-zinc-700 dark:bg-zinc-900"
                             role="menu">
                            <a href="{{ route('chapters.nigeria') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800 transition-colors {{ request()->routeIs('chapters.nigeria') ? 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-950/40' : '' }}"
                               role="menuitem">
                                <span class="text-base leading-none">&#127475;&#127468;</span>
                                <span class="font-medium">Nigeria Chapter</span>
                            </a>
                            <a href="{{ route('chapters.kenya') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800 transition-colors {{ request()->routeIs('chapters.kenya') ? 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40' : '' }}"
                               role="menuitem">
                                <span class="text-base leading-none">&#127472;&#127466;</span>
                                <span class="font-medium">Kenya Chapter</span>
                            </a>
                        </div>
                    </div>

                    {{-- News --}}
                    <a href="{{ route('news.index') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition-colors
                              {{ request()->routeIs('news*') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100' }}">
                        News
                    </a>

                    {{-- Contact --}}
                    <a href="{{ route('contact') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition-colors
                              {{ request()->routeIs('contact') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/60 dark:hover:text-zinc-100' }}">
                        Contact
                    </a>
                </nav>

                {{-- -------- Right-side controls -------- --}}
                <div class="flex items-center gap-2">

                    {{-- Dark mode toggle --}}
                    <button @click="darkMode = !darkMode"
                            :aria-label="darkMode ? 'Switch to light mode' : 'Switch to dark mode'"
                            class="rounded-lg p-2 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-200 transition-colors">
                        {{-- Sun icon (shown in dark mode) --}}
                        <svg x-show="darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
                        </svg>
                        {{-- Moon icon (shown in light mode) --}}
                        <svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    {{-- Member Login / Dashboard button --}}
                    @auth
                        <a href="{{ route('admin.dashboard') }}"
                           class="hidden rounded-full bg-green-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-800 transition-colors lg:inline-block">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="hidden rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 transition-colors lg:inline-block">
                            Member Login
                        </a>
                    @endauth

                    {{-- Mobile menu toggle --}}
                    <button @click="mobileOpen = !mobileOpen"
                            :aria-expanded="mobileOpen"
                            aria-label="Toggle menu"
                            class="rounded-lg p-2 text-zinc-600 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:bg-zinc-800 transition-colors xl:hidden">
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
                 class="border-t border-zinc-100 bg-white px-4 pb-4 pt-2 dark:border-zinc-800 dark:bg-zinc-950 xl:hidden"
                 @click.outside="mobileOpen = false">
                <nav class="flex flex-col gap-0.5" aria-label="Mobile navigation">

                    {{-- Main links --}}
                    <a href="{{ route('home') }}"
                       class="rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                        Home
                    </a>
                    <a href="{{ route('about') }}"
                       class="rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                        About
                    </a>

                    {{-- What We Do section --}}
                    <div class="border-t border-zinc-100 dark:border-zinc-800 my-1 pt-1">
                        <p class="px-3 pb-1 pt-1 text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">What We Do</p>
                        <a href="{{ route('pillars') }}"
                           class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('pillars*') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                            <svg class="h-4 w-4 shrink-0 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            Our Pillars
                        </a>
                        <a href="{{ route('trade') }}"
                           class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('trade') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                            <svg class="h-4 w-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Trade & Investment
                        </a>
                        <a href="{{ route('policy') }}"
                           class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('policy') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                            <svg class="h-4 w-4 shrink-0 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Policy & Research
                        </a>
                    </div>

                    {{-- Membership & Events --}}
                    <div class="border-t border-zinc-100 dark:border-zinc-800 my-1 pt-1">
                        <a href="{{ route('membership') }}"
                           class="rounded-lg px-3 py-2.5 text-sm font-medium transition-colors block {{ request()->routeIs('membership*') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                            Membership
                        </a>
                        <a href="{{ route('events.index') }}"
                           class="rounded-lg px-3 py-2.5 text-sm font-medium transition-colors block {{ request()->routeIs('events*') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                            Events & Missions
                        </a>
                    </div>

                    {{-- Chapters section --}}
                    <div class="border-t border-zinc-100 dark:border-zinc-800 my-1 pt-1">
                        <p class="px-3 pb-1 pt-1 text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Chapters</p>
                        <a href="{{ route('chapters.nigeria') }}"
                           class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('chapters.nigeria') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                            <span class="text-base leading-none">&#127475;&#127468;</span> Nigeria Chapter
                        </a>
                        <a href="{{ route('chapters.kenya') }}"
                           class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('chapters.kenya') ? 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400' : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                            <span class="text-base leading-none">&#127472;&#127466;</span> Kenya Chapter
                        </a>
                    </div>

                    {{-- News & Contact --}}
                    <div class="border-t border-zinc-100 dark:border-zinc-800 my-1 pt-1">
                        <a href="{{ route('news.index') }}"
                           class="rounded-lg px-3 py-2.5 text-sm font-medium transition-colors block {{ request()->routeIs('news*') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                            News
                        </a>
                        <a href="{{ route('contact') }}"
                           class="rounded-lg px-3 py-2.5 text-sm font-medium transition-colors block {{ request()->routeIs('contact') ? 'bg-green-50 text-green-700 dark:bg-green-950/60 dark:text-green-400' : 'text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800' }}">
                            Contact
                        </a>
                    </div>

                    {{-- CTA --}}
                    <div class="border-t border-zinc-100 dark:border-zinc-800 my-1 pt-2">
                        @auth
                            <a href="{{ route('admin.dashboard') }}"
                               class="block rounded-full bg-green-700 px-4 py-2.5 text-sm font-semibold text-white text-center hover:bg-green-800 transition-colors">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="block rounded-full bg-red-600 px-4 py-2.5 text-sm font-semibold text-white text-center hover:bg-red-700 transition-colors">
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
        <footer class="mt-16 bg-green-950 text-white">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

                {{-- 3-column grid --}}
                <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">

                    {{-- Column 1: Brand + Social --}}
                    <div>
                        <a href="{{ route('home') }}" class="mb-5 flex items-center gap-3 group">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-700 text-white text-xs font-bold font-serif shadow group-hover:bg-green-600 transition-colors">
                                NK
                            </div>
                            <div>
                                <span class="block text-base font-bold text-white font-serif tracking-wide">NiKCCIMA</span>
                                <span class="block text-xs text-green-300">Nigeria-Kenya Chamber</span>
                            </div>
                        </a>
                        <p class="mb-5 text-sm text-green-200/80 leading-relaxed">
                            The Nigeria-Kenya Chamber of Commerce, Industry, Mines &amp; Agriculture — driving AfCFTA corridor execution and unlocking trade between Africa's two largest economies.
                        </p>

                        {{-- Quick links --}}
                        <div class="mb-6 flex flex-wrap gap-x-4 gap-y-1.5 text-xs text-green-300/80">
                            <a href="{{ route('about') }}" class="hover:text-white transition-colors">About</a>
                            <a href="{{ route('pillars') }}" class="hover:text-white transition-colors">Pillars</a>
                            <a href="{{ route('membership') }}" class="hover:text-white transition-colors">Membership</a>
                            <a href="{{ route('news.index') }}" class="hover:text-white transition-colors">News</a>
                            <a href="{{ route('downloads') }}" class="hover:text-white transition-colors">Downloads</a>
                            <a href="{{ route('leadership') }}" class="hover:text-white transition-colors">Leadership</a>
                            <a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact</a>
                        </div>

                        {{-- Social icons --}}
                        <div class="flex items-center gap-3">
                            {{-- Twitter / X --}}
                            <a href="#" aria-label="Follow us on X" class="flex h-8 w-8 items-center justify-center rounded-full bg-green-900 text-green-300 hover:bg-green-700 hover:text-white transition-colors">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                            {{-- LinkedIn --}}
                            <a href="#" aria-label="Follow us on LinkedIn" class="flex h-8 w-8 items-center justify-center rounded-full bg-green-900 text-green-300 hover:bg-green-700 hover:text-white transition-colors">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            {{-- Facebook --}}
                            <a href="#" aria-label="Follow us on Facebook" class="flex h-8 w-8 items-center justify-center rounded-full bg-green-900 text-green-300 hover:bg-green-700 hover:text-white transition-colors">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Column 2: Nigeria Office --}}
                    <div>
                        <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-green-300">
                            <span class="text-base leading-none">&#127475;&#127468;</span>
                            Nigeria Chapter
                        </h3>
                        <ul class="space-y-2 text-sm text-green-200/80">
                            <li class="flex items-start gap-2">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Abuja, Federal Capital Territory<br>Federal Republic of Nigeria</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="mailto:nigeria@nikccima.org" class="hover:text-white transition-colors">nigeria@nikccima.org</a>
                            </li>
                        </ul>
                        <div class="mt-5">
                            <a href="{{ route('chapters.nigeria') }}"
                               class="inline-flex items-center gap-1.5 rounded-lg border border-green-700 px-3 py-1.5 text-xs font-medium text-green-300 hover:border-green-500 hover:text-white transition-colors">
                                Visit Nigeria Chapter
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Column 3: Kenya Office + Links --}}
                    <div>
                        <h3 class="mb-4 flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-red-400">
                            <span class="text-base leading-none">&#127472;&#127466;</span>
                            Kenya Chapter
                        </h3>
                        <ul class="space-y-2 text-sm text-green-200/80">
                            <li class="flex items-start gap-2">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Nairobi, Capital City<br>Republic of Kenya</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="h-4 w-4 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="mailto:kenya@nikccima.org" class="hover:text-white transition-colors">kenya@nikccima.org</a>
                            </li>
                        </ul>
                        <div class="mt-5">
                            <a href="{{ route('chapters.kenya') }}"
                               class="inline-flex items-center gap-1.5 rounded-lg border border-red-800 px-3 py-1.5 text-xs font-medium text-red-400 hover:border-red-500 hover:text-red-300 transition-colors">
                                Visit Kenya Chapter
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        {{-- Additional links --}}
                        <div class="mt-6 border-t border-green-900 pt-5">
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-green-500">Resources</p>
                            <ul class="space-y-1.5 text-sm text-green-200/80">
                                <li><a href="{{ route('trade') }}" class="hover:text-white transition-colors">Trade &amp; Investment</a></li>
                                <li><a href="{{ route('policy') }}" class="hover:text-white transition-colors">Policy &amp; Research</a></li>
                                <li><a href="{{ route('events.index') }}" class="hover:text-white transition-colors">Events &amp; Missions</a></li>
                                <li><a href="{{ route('downloads') }}" class="hover:text-white transition-colors">Downloads</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Bottom bar --}}
                <div class="mt-10 border-t border-green-900 pt-6 flex flex-col items-center justify-between gap-3 sm:flex-row">
                    <p class="text-xs text-green-400/70">
                        &copy; {{ date('Y') }} NiKCCIMA. All rights reserved. Governed under the AfCFTA framework.
                    </p>
                    <p class="text-xs text-green-500/60">
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
