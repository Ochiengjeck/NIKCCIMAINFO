<x-layouts::website :title="'NiKCCIMA — Driving Structured Trade Between Nigeria and Kenya'">

    {{-- =========================================================
         SECTION A — HERO CAROUSEL (3 slides)
         Full-bleed, min-height: calc(100vh - 91px)
         =========================================================
         CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Homepage"

         Slide 1 (Trade / AfCFTA):
           hero_badge         : Badge label          (default: "AfCFTA Corridor Execution")
           hero_title         : Main headline         (default: "Driving Structured Trade…")
           hero_subtitle      : Sub-paragraph         (default: "NiKCCIMA is the premier…")
           hero_cta_primary   : Primary button label  (default: "Become a Member")
           hero_cta_secondary : Secondary button label(default: "Explore Trade Opportunities")
           hero_image         : Background image path (upload 1920×800 via Media Library)

         Slide 2 (Membership):
           hero2_badge        : Badge label          (default: "Membership Network")
           hero2_title        : Main headline        (default: "Connecting Africa's Two…")
           hero2_subtitle     : Sub-paragraph        (default: "Join a structured…")
           hero2_cta_primary  : Primary button label (default: "View Membership Tiers")
           hero2_cta_secondary: Secondary button label(default: "Apply Now")

         Slide 3 (Events):
           hero3_badge        : Badge label          (default: "Events & Missions")
           hero3_title        : Main headline        (default: "Flagship Summits…")
           hero3_subtitle     : Sub-paragraph        (default: "Attend NiKCCIMA's…")
           hero3_cta_primary  : Primary button label (default: "View Upcoming Events")
           hero3_cta_secondary: Secondary button label(default: "Contact the Secretariat")
         ========================================================= --}}
    <section
        class="relative overflow-hidden text-white"
        style="min-height: calc(100vh - 91px);
               @if($page?->section('hero_image'))
                   background-image: url('{{ asset($page->section('hero_image')) }}');
                   background-size: cover;
                   background-position: center;
               @endif"
    >
        {{-- Background layer: image overlay OR gradient + dot pattern --}}
        @if($page?->section('hero_image'))
            {{-- Dark overlay for image background --}}
            <div class="absolute inset-0 bg-zinc-950/80"></div>
        @else
            {{-- Gradient background --}}
            <div class="absolute inset-0 bg-gradient-to-br from-green-950 via-green-900 to-[#922529]"></div>
            {{-- Dot pattern SVG overlay at 5% opacity --}}
            <div class="absolute inset-0" style="opacity:0.05;">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                    <defs>
                        <pattern id="dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1" fill="white"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dots)"/>
                </svg>
            </div>
        @endif

        {{-- Decorative accent blobs --}}
        <div class="absolute -right-16 -top-16 h-[36rem] w-[36rem] rounded-full bg-brand-200/30 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 h-[32rem] w-[32rem] rounded-full bg-crimson-700/40 blur-2xl pointer-events-none"></div>

        {{-- ===== HERO CAROUSEL (Alpine.js) ===== --}}
        {{-- flex-col: content (flex-1) fills space, controls (shrink-0) always stay in viewport --}}
        <div
            class="relative flex flex-col"
            style="min-height: calc(100vh - 91px);"
            x-data="{
                active: 0,
                total: 3,
                timer: null,
                start() {
                    this.timer = setInterval(() => {
                        this.active = (this.active + 1) % this.total;
                    }, 6000);
                },
                go(i) {
                    this.active = i;
                    clearInterval(this.timer);
                    this.start();
                },
                prev() { this.go((this.active - 1 + this.total) % this.total); },
                next() { this.go((this.active + 1) % this.total); }
            }"
            x-init="start()"
        >
            {{-- Content area: grows to fill available height, centers grid vertically --}}
            <div class="mx-auto w-full max-w-7xl flex-1 flex items-center px-4 py-10 sm:px-6 lg:px-8">
                <div class="grid w-full items-center gap-12 lg:grid-cols-5">

                    {{-- ===== LEFT: Slides stacked via CSS grid-area — opacity crossfade, no display:none snap ===== --}}
                    <div class="lg:col-span-3 grid">

                        {{-- SLIDE 1 —— CMS keys: hero_badge, hero_title, hero_subtitle, hero_cta_primary, hero_cta_secondary --}}
                        <div style="grid-area: 1/1;"
                             :class="active === 0 ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
                             class="transition-opacity duration-500 ease-in-out">
                            <div class="mb-6">
                                <span class="inline-flex items-center gap-2 rounded-full bg-brand-200 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-brand-900 shadow-sm">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    {{ $page?->section('hero_badge', 'AfCFTA Corridor Execution') }}
                                </span>
                            </div>
                            <h1 class="font-['Playfair_Display',serif] font-serif text-5xl font-bold leading-[1.1] text-white lg:text-6xl xl:text-7xl">
                                {{ $page?->section('hero_title', 'Driving Structured Trade Between Nigeria and Kenya.') }}
                            </h1>
                            <p class="mt-6 max-w-2xl text-lg leading-relaxed text-brand-100">
                                {{ $page?->section('hero_subtitle', 'NiKCCIMA is the premier bilateral trade chamber operationalising the AfCFTA corridor between Nigeria and Kenya — with governance, structure, and measurable outcomes.') }}
                            </p>
                            <div class="mt-10 flex flex-wrap gap-4">
                                <a href="{{ route('membership.apply') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-semibold text-brand-900 shadow-lg transition hover:bg-brand-50 hover:shadow-xl">
                                    {{ $page?->section('hero_cta_primary', 'Become a Member') }}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                                <a href="{{ route('trade') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/40 bg-white/10 px-7 py-3.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20 hover:border-white/60">
                                    {{ $page?->section('hero_cta_secondary', 'Explore Trade Opportunities') }}
                                </a>
                            </div>
                            <div class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-3">
                                <span class="text-xs font-medium uppercase tracking-widest text-brand-300">Recognised by</span>
                                <span class="text-sm font-semibold text-white/60">African Union</span>
                                <span class="h-4 w-px bg-white/20"></span>
                                <span class="text-sm font-semibold text-white/60">AfCFTA Secretariat</span>
                                <span class="h-4 w-px bg-white/20"></span>
                                <span class="text-sm font-semibold text-white/60">ECOWAS Framework</span>
                            </div>
                        </div>

                        {{-- SLIDE 2 —— CMS keys: hero2_badge, hero2_title, hero2_subtitle, hero2_cta_primary, hero2_cta_secondary --}}
                        <div style="grid-area: 1/1;"
                             :class="active === 1 ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
                             class="transition-opacity duration-500 ease-in-out">
                            <div class="mb-6">
                                <span class="inline-flex items-center gap-2 rounded-full bg-crimson-700 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white shadow-sm">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                                    {{ $page?->section('hero2_badge', 'Membership Network') }}
                                </span>
                            </div>
                            <h1 class="font-['Playfair_Display',serif] font-serif text-5xl font-bold leading-[1.1] text-white lg:text-6xl xl:text-7xl">
                                {{ $page?->section('hero2_title', "Connecting Africa's Two Largest Economies.") }}
                            </h1>
                            <p class="mt-6 max-w-2xl text-lg leading-relaxed text-brand-100">
                                {{ $page?->section('hero2_subtitle', 'Join a structured bilateral chamber with members spanning trade, finance, agriculture, technology, and maritime sectors across Nigeria and Kenya.') }}
                            </p>
                            <div class="mt-10 flex flex-wrap gap-4">
                                <a href="{{ route('membership') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-semibold text-brand-900 shadow-lg transition hover:bg-brand-50 hover:shadow-xl">
                                    {{ $page?->section('hero2_cta_primary', 'View Membership Tiers') }}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                                <a href="{{ route('membership.apply') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/40 bg-white/10 px-7 py-3.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20 hover:border-white/60">
                                    {{ $page?->section('hero2_cta_secondary', 'Apply Now') }}
                                </a>
                            </div>
                            <div class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-3">
                                <span class="text-xs font-medium uppercase tracking-widest text-brand-300">Serving sectors</span>
                                <span class="text-sm font-semibold text-white/60">Trade & Finance</span>
                                <span class="h-4 w-px bg-white/20"></span>
                                <span class="text-sm font-semibold text-white/60">Agriculture</span>
                                <span class="h-4 w-px bg-white/20"></span>
                                <span class="text-sm font-semibold text-white/60">Technology</span>
                            </div>
                        </div>

                        {{-- SLIDE 3 —— CMS keys: hero3_badge, hero3_title, hero3_subtitle, hero3_cta_primary, hero3_cta_secondary --}}
                        <div style="grid-area: 1/1;"
                             :class="active === 2 ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
                             class="transition-opacity duration-500 ease-in-out">
                            <div class="mb-6">
                                <span class="inline-flex items-center gap-2 rounded-full bg-brand-200 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-brand-900 shadow-sm">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                                    {{ $page?->section('hero3_badge', 'Events & Missions') }}
                                </span>
                            </div>
                            <h1 class="font-['Playfair_Display',serif] font-serif text-5xl font-bold leading-[1.1] text-white lg:text-6xl xl:text-7xl">
                                {{ $page?->section('hero3_title', 'Flagship Summits. Real Trade Outcomes.') }}
                            </h1>
                            <p class="mt-6 max-w-2xl text-lg leading-relaxed text-brand-100">
                                {{ $page?->section('hero3_subtitle', "Attend NiKCCIMA's corridor activation summits, bilateral trade missions, and B2B matching events — where Nigeria meets Kenya in structured, high-value commerce.") }}
                            </p>
                            <div class="mt-10 flex flex-wrap gap-4">
                                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-semibold text-brand-900 shadow-lg transition hover:bg-brand-50 hover:shadow-xl">
                                    {{ $page?->section('hero3_cta_primary', 'View Upcoming Events') }}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/40 bg-white/10 px-7 py-3.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20 hover:border-white/60">
                                    {{ $page?->section('hero3_cta_secondary', 'Contact the Secretariat') }}
                                </a>
                            </div>
                            <div class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-3">
                                <span class="text-xs font-medium uppercase tracking-widest text-brand-300">Upcoming in</span>
                                <span class="text-sm font-semibold text-white/60">Abuja</span>
                                <span class="h-4 w-px bg-white/20"></span>
                                <span class="text-sm font-semibold text-white/60">Nairobi</span>
                                <span class="h-4 w-px bg-white/20"></span>
                                <span class="text-sm font-semibold text-white/60">Virtual</span>
                            </div>
                        </div>

                    </div>

                    {{-- ===== RIGHT: Static decorative panel ===== --}}
                    <div class="hidden lg:col-span-2 lg:block">
                        @if($page?->section('feature_image'))
                            <div class="relative">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('feature_image')) }}"
                                     alt="NiKCCIMA"
                                     class="w-full rounded-2xl shadow-2xl ring-1 ring-white/20 -rotate-1 object-cover"
                                     style="max-height: 500px;"/>
                                <div class="absolute -bottom-4 -left-4 rounded-xl border border-crimson-800 bg-crimson-900/90 px-4 py-3 shadow-xl backdrop-blur">
                                    <span class="block text-xs font-semibold uppercase tracking-widest text-brand-300">Operating Since</span>
                                    <span class="block text-2xl font-bold text-white font-serif">2024</span>
                                </div>
                            </div>
                        @else
                            <div class="space-y-3">
                                <div class="rounded-2xl border border-crimson-800 bg-crimson-900/50 p-5 backdrop-blur-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-crimson-800">
                                            <svg class="h-6 w-6 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-brand-200">Bilateral Mandate</p>
                                            <p class="font-semibold text-white">Nigeria ↔ Kenya Corridor</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-2xl border border-crimson-800 bg-crimson-900/50 p-5 backdrop-blur-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-crimson-800">
                                            <svg class="h-6 w-6 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-brand-200">Framework</p>
                                            <p class="font-semibold text-white">AfCFTA Aligned & Governed</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-2xl border border-crimson-800 bg-crimson-900/50 p-5 backdrop-blur-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-crimson-800">
                                            <svg class="h-6 w-6 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-brand-200">Focus</p>
                                            <p class="font-semibold text-white">Measurable Trade Outcomes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- ===== Carousel controls — flex child, always visible within hero ===== --}}
            <div class="flex shrink-0 items-center justify-center gap-6 pb-8">

                {{-- Prev arrow --}}
                <button @click="prev()" aria-label="Previous slide"
                        class="flex h-9 w-9 items-center justify-center rounded-full border border-white/30 bg-white/10 text-white backdrop-blur-sm transition hover:bg-white/25">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                {{-- Dot indicators --}}
                <div class="flex items-center gap-2">
                    <template x-for="i in total" :key="i">
                        <button
                            @click="go(i - 1)"
                            :aria-label="'Go to slide ' + i"
                            :class="active === i - 1
                                ? 'w-8 bg-brand-200'
                                : 'w-2.5 bg-white/40 hover:bg-white/70'"
                            class="h-2.5 rounded-full transition-all duration-300">
                        </button>
                    </template>
                </div>

                {{-- Next arrow --}}
                <button @click="next()" aria-label="Next slide"
                        class="flex h-9 w-9 items-center justify-center rounded-full border border-white/30 bg-white/10 text-white backdrop-blur-sm transition hover:bg-white/25">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

            </div>
        </div>
    </section>

    {{-- =========================================================
         SECTION B — LIVE METRICS STRIP
         bg-green-900 py-12
         =========================================================
         No CMS keys — values pulled live from DB
         (Member, TradeLead, Corridor, Ntb models)
         ========================================================= --}}
    <section class="bg-crimson-700 py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-y-10 gap-x-4 lg:grid-cols-4">

                {{-- Active Members --}}
                <div class="flex flex-col items-center text-center lg:border-r lg:border-crimson-600 lg:pr-8">
                    <span
                        class="font-['Playfair_Display',serif] font-serif text-4xl font-bold text-white lg:text-5xl"
                        x-data="{ count: 0 }"
                        x-init="setTimeout(() => {
                            let t = {{ $stats['members'] }},
                                s = Math.max(1, Math.ceil(t / 60));
                            let i = setInterval(() => {
                                count = Math.min(count + s, t);
                                if (count >= t) clearInterval(i);
                            }, 25);
                        }, 500)"
                        x-text="count.toLocaleString()"
                    >0</span>
                    <span class="mt-2 text-sm uppercase tracking-wide text-brand-200">Active Members</span>
                </div>

                {{-- Trade Leads --}}
                <div class="flex flex-col items-center text-center lg:border-r lg:border-crimson-600 lg:pr-8">
                    <span
                        class="font-['Playfair_Display',serif] font-serif text-4xl font-bold text-white lg:text-5xl"
                        x-data="{ count: 0 }"
                        x-init="setTimeout(() => {
                            let t = {{ $stats['leads'] }},
                                s = Math.max(1, Math.ceil(t / 60));
                            let i = setInterval(() => {
                                count = Math.min(count + s, t);
                                if (count >= t) clearInterval(i);
                            }, 25);
                        }, 500)"
                        x-text="count.toLocaleString()"
                    >0</span>
                    <span class="mt-2 text-sm uppercase tracking-wide text-brand-200">Trade Leads</span>
                </div>

                {{-- Active Corridors --}}
                <div class="flex flex-col items-center text-center lg:border-r lg:border-crimson-600 lg:pr-8">
                    <span
                        class="font-['Playfair_Display',serif] font-serif text-4xl font-bold text-white lg:text-5xl"
                        x-data="{ count: 0 }"
                        x-init="setTimeout(() => {
                            let t = {{ $stats['corridors'] }},
                                s = Math.max(1, Math.ceil(t / 60));
                            let i = setInterval(() => {
                                count = Math.min(count + s, t);
                                if (count >= t) clearInterval(i);
                            }, 25);
                        }, 500)"
                        x-text="count.toLocaleString()"
                    >0</span>
                    <span class="mt-2 text-sm uppercase tracking-wide text-brand-200">Active Corridors</span>
                </div>

                {{-- NTBs Resolved --}}
                <div class="flex flex-col items-center text-center">
                    <span
                        class="font-['Playfair_Display',serif] font-serif text-4xl font-bold text-white lg:text-5xl"
                        x-data="{ count: 0 }"
                        x-init="setTimeout(() => {
                            let t = {{ $stats['ntbs'] }},
                                s = Math.max(1, Math.ceil(t / 60));
                            let i = setInterval(() => {
                                count = Math.min(count + s, t);
                                if (count >= t) clearInterval(i);
                            }, 25);
                        }, 500)"
                        x-text="count.toLocaleString()"
                    >0</span>
                    <span class="mt-2 text-sm uppercase tracking-wide text-brand-200">NTBs Resolved</span>
                </div>

            </div>
        </div>
    </section>

    {{-- =========================================================
         SECTION B2 — WHAT WE DO (Services Grid)
         Inspired by eaisraelchamber.org services grid
         ========================================================= --}}
    <section class="bg-white py-20 dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-crimson-700">Our Services</span>
                <h2 class="font-['Playfair_Display',serif] font-serif mt-3 text-4xl font-bold text-zinc-900 dark:text-white lg:text-5xl">
                    What NiKCCIMA Does For You
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-base text-zinc-500 dark:text-zinc-400">
                    From trade facilitation to policy advocacy — we deliver structured bilateral outcomes across the Nigeria-Kenya corridor.
                </p>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    ['icon' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3', 'title' => 'Trade Facilitation', 'desc' => 'End-to-end support for bilateral trade transactions, documentation, and customs processes between Nigeria and Kenya.'],
                    ['icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7', 'title' => 'Market Access & Penetration', 'desc' => 'Strategic entry support for Nigerian businesses into Kenya and vice versa — market intelligence, partner matching, and sector guides.'],
                    ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Policy Advocacy', 'desc' => 'Bilateral policy engagement, NTB resolution, and AfCFTA compliance monitoring to remove barriers and unlock corridor trade.'],
                    ['icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'title' => 'AfCFTA Corridor Management', 'desc' => 'Structured AfCFTA corridor activation with governance frameworks, KPI tracking, and measurable bilateral trade outcomes.'],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Business Matchmaking', 'desc' => 'Curated B2B matching connecting Nigerian and Kenyan enterprises through our structured pipeline and deal facilitation process.'],
                    ['icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064', 'title' => 'Bilateral Trade Missions', 'desc' => 'Flagship summits, trade missions, and corridor activation events connecting senior business leaders across both countries.'],
                ] as $service)
                <div class="group rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:border-brand-200 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-brand-700">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-950">
                        <svg class="h-6 w-6 text-brand-700 dark:text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $service['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="mb-2 font-semibold text-zinc-900 dark:text-white">{{ $service['title'] }}</h3>
                    <p class="text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">{{ $service['desc'] }}</p>
                </div>
                @endforeach
            </div>
            <div class="mt-10 text-center">
                <a href="{{ route('pillars') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-crimson-700 hover:text-crimson-800 dark:text-brand-300 dark:hover:text-brand-200 transition">
                    Explore Our Strategic Pillars in Detail
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- =========================================================
         SECTION C — INSTITUTIONAL SNAPSHOT
         py-20 bg-white dark:bg-zinc-950
         =========================================================
         CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Homepage"
         about_heading : Section heading (default: "About NiKCCIMA")
         about_body    : Institution intro paragraph (HTML/text)
         ========================================================= --}}
    <section class="bg-white py-20 dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-16 lg:grid-cols-2 lg:items-center">

                {{-- Left column: heading + body text --}}
                <div class="max-w-xl">
                    <span class="text-xs font-semibold uppercase tracking-widest text-crimson-700">Our Institution</span>
                    <h2 class="font-['Playfair_Display',serif] font-serif mt-3 text-4xl font-bold leading-tight text-zinc-900 dark:text-white lg:text-5xl">
                        {{ $page?->section('about_heading', 'About NiKCCIMA') }}
                    </h2>
                    <div class="prose prose-zinc dark:prose-invert mt-6 max-w-none text-lg leading-relaxed text-zinc-600 dark:text-zinc-400">
                        <p>{{ $page?->section('about_body', 'The Nigeria-Kenya Chamber of Commerce, Industry, Mines & Agriculture (NiKCCIMA) is the premier bilateral institution operationalising the African Continental Free Trade Area corridor between Nigeria and Kenya. We deliver measurable, governance-backed trade outcomes through structured KPI systems, NTB resolution mechanisms, and bilateral corridor activation.') }}</p>
                    </div>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('about') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-crimson-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-crimson-800 focus:outline-none focus:ring-2 focus:ring-crimson-600 focus:ring-offset-2">
                            Learn More About Us
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="{{ route('leadership') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 px-6 py-3 text-sm font-semibold text-zinc-700 transition hover:border-zinc-300 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            Meet the Leadership
                        </a>
                    </div>
                </div>

                {{-- Right column: 2×2 credibility badges --}}
                <div class="grid grid-cols-2 gap-4">

                    {{-- Governance-Backed --}}
                    <div class="rounded-2xl border border-zinc-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-900/40">
                            <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Governance-Backed</h3>
                        <p class="mt-1.5 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">Formal bilateral framework with institutional oversight from both Nigeria and Kenya.</p>
                    </div>

                    {{-- Structured KPI System --}}
                    <div class="rounded-2xl border border-zinc-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-900/40">
                            <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Structured KPI System</h3>
                        <p class="mt-1.5 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">Measurable outcomes tracked against defined trade and investment performance indicators.</p>
                    </div>

                    {{-- Bilateral Corridor Model --}}
                    <div class="rounded-2xl border border-zinc-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-900/40">
                            <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Bilateral Corridor Model</h3>
                        <p class="mt-1.5 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">Dedicated Nigeria–Kenya trade corridor with structured B2B pipeline and deal facilitation.</p>
                    </div>

                    {{-- AfCFTA Aligned --}}
                    <div class="rounded-2xl border border-zinc-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-900/40">
                            <svg class="h-5 w-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">AfCFTA Aligned</h3>
                        <p class="mt-1.5 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">Fully compliant with the African Continental Free Trade Area framework and protocols.</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- =========================================================
         SECTION C2 — ABOUT SNAPSHOT (numbered, eaisraelchamber.org style)
         =========================================================
         CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Homepage"
         snapshot_mission, snapshot_vision, snapshot_impact, snapshot_scope
         ========================================================= --}}
    <section class="bg-zinc-50 py-20 dark:bg-zinc-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-crimson-700">Who We Are</span>
                <h2 class="font-['Playfair_Display',serif] font-serif mt-3 text-4xl font-bold text-zinc-900 dark:text-white lg:text-5xl">
                    Our Foundation
                </h2>
            </div>
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    ['num' => '01', 'title' => 'Mission',
                     'body' => $page?->section('snapshot_mission', 'To operationalise the AfCFTA bilateral corridor between Nigeria and Kenya through structured governance, trade facilitation, and measurable outcomes.')],
                    ['num' => '02', 'title' => 'Vision',
                     'body' => $page?->section('snapshot_vision', 'To be the leading bilateral chamber driving Africa\'s most productive Nigeria-Kenya trade corridor, setting the standard for governed intra-African commerce.')],
                    ['num' => '03', 'title' => 'Impact',
                     'body' => $page?->section('snapshot_impact', 'Measurable bilateral trade growth, reduced NTBs, structured B2B deal pipelines, and active AfCFTA corridor execution between Africa\'s two largest economies.')],
                    ['num' => '04', 'title' => 'Scope',
                     'body' => $page?->section('snapshot_scope', 'Operating across both Nigeria and Kenya with chapter offices in Abuja and Nairobi, serving members across all sectors of the bilateral trade corridor.')],
                ] as $item)
                    <div class="relative rounded-2xl bg-white p-6 shadow-sm border border-zinc-100 dark:bg-zinc-800 dark:border-zinc-700">
                        <span class="absolute right-5 top-4 font-serif text-5xl font-bold leading-none text-crimson-700/10 dark:text-crimson-300/10 select-none">{{ $item['num'] }}</span>
                        <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-950">
                            <span class="font-serif text-sm font-bold text-brand-700 dark:text-brand-300">{{ $item['num'] }}</span>
                        </div>
                        <h3 class="mb-2 font-serif text-lg font-bold text-zinc-900 dark:text-white">{{ $item['title'] }}</h3>
                        <p class="text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">{{ $item['body'] }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-10 text-center">
                <a href="{{ route('about') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-crimson-700 hover:text-crimson-800 dark:text-brand-300 dark:hover:text-brand-200 transition">
                    Read our full story
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- =========================================================
         SECTION D — FOUR PILLARS
         py-20 bg-zinc-50 dark:bg-zinc-900
         =========================================================
         CMS-MANAGED SECTIONS — Admin → CMS → Pages → "pillars-overview"
         pillar1_title, pillar1_summary, pillar2_title, pillar2_summary,
         pillar3_title, pillar3_summary, pillar4_title, pillar4_summary
         ========================================================= --}}
    <section class="bg-zinc-50 py-20 dark:bg-zinc-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <div class="mb-12 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-crimson-700">How We Work</span>
                <h2 class="font-['Playfair_Display',serif] font-serif mt-3 text-4xl font-bold text-zinc-900 dark:text-white lg:text-5xl">
                    Our Four Pillars
                </h2>
                <p class="mx-auto mt-4 max-w-2xl text-base text-zinc-500 dark:text-zinc-400">
                    NiKCCIMA is structured around four strategic pillars that together deliver governed bilateral trade outcomes.
                </p>
            </div>

            {{-- Pillars grid: 2×2 on md, 4-col on xl --}}
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">

                {{-- Pillar 1: Executive & Institutional Leadership --}}
                @php
                    $pillar1Title   = $page?->section('pillar1_title',   'Executive & Institutional Leadership');
                    $pillar1Summary = $page?->section('pillar1_summary', 'Governance, council oversight, and the strategic direction that ensures NiKCCIMA delivers structured bilateral outcomes.');
                @endphp
                <a href="{{ route('pillars.show', 'executive') }}"
                    class="group relative flex flex-col overflow-hidden rounded-2xl bg-gradient-to-br from-green-900 to-crimson-800 p-8 text-white shadow-lg transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-white/10">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="mb-1 text-xs font-semibold uppercase tracking-widest text-brand-300">Pillar 1</span>
                    <h3 class="font-['Playfair_Display',serif] font-serif mb-3 text-xl font-bold leading-snug text-white">{{ $pillar1Title }}</h3>
                    <p class="flex-1 text-sm leading-relaxed text-brand-100">{{ $pillar1Summary }}</p>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-semibold text-brand-200 group-hover:text-white transition">
                        Explore Pillar
                        <svg class="h-4 w-4 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </div>
                </a>

                {{-- Pillar 2: Trade, Investment & Business Development --}}
                @php
                    $pillar2Title   = $page?->section('pillar2_title',   'Trade, Investment & Business Development');
                    $pillar2Summary = $page?->section('pillar2_summary', 'Corridor activation, B2B facilitation, and structured deal pipeline management across the Nigeria–Kenya corridor.');
                @endphp
                <a href="{{ route('pillars.show', 'trade') }}"
                    class="group relative flex flex-col overflow-hidden rounded-2xl bg-gradient-to-br from-green-900 to-brand-700 p-8 text-white shadow-lg transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-white/10">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <span class="mb-1 text-xs font-semibold uppercase tracking-widest text-brand-300">Pillar 2</span>
                    <h3 class="font-['Playfair_Display',serif] font-serif mb-3 text-xl font-bold leading-snug text-white">{{ $pillar2Title }}</h3>
                    <p class="flex-1 text-sm leading-relaxed text-brand-100">{{ $pillar2Summary }}</p>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-semibold text-brand-200 group-hover:text-white transition">
                        Explore Pillar
                        <svg class="h-4 w-4 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </div>
                </a>

                {{-- Pillar 3: Policy, Research & Strategic Affairs --}}
                @php
                    $pillar3Title   = $page?->section('pillar3_title',   'Policy, Research & Strategic Affairs');
                    $pillar3Summary = $page?->section('pillar3_summary', 'NTB resolution, policy briefs, AfCFTA compliance monitoring, and evidence-based strategic advisory services.');
                @endphp
                <a href="{{ route('pillars.show', 'policy') }}"
                    class="group relative flex flex-col overflow-hidden rounded-2xl bg-gradient-to-br from-green-800 to-brand-600 p-8 text-white shadow-lg transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-white/10">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="mb-1 text-xs font-semibold uppercase tracking-widest text-brand-300">Pillar 3</span>
                    <h3 class="font-['Playfair_Display',serif] font-serif mb-3 text-xl font-bold leading-snug text-white">{{ $pillar3Title }}</h3>
                    <p class="flex-1 text-sm leading-relaxed text-brand-100">{{ $pillar3Summary }}</p>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-semibold text-brand-200 group-hover:text-white transition">
                        Explore Pillar
                        <svg class="h-4 w-4 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </div>
                </a>

                {{-- Pillar 4: Administration, Finance & Membership --}}
                @php
                    $pillar4Title   = $page?->section('pillar4_title',   'Administration, Finance & Membership');
                    $pillar4Summary = $page?->section('pillar4_summary', 'Membership services, secretariat operations, financial governance, and the administrative backbone of the chamber.');
                @endphp
                <a href="{{ route('pillars.show', 'admin') }}"
                    class="group relative flex flex-col overflow-hidden rounded-2xl bg-gradient-to-br from-crimson-800 to-crimson-700 p-8 text-white shadow-lg transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-white/10">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="mb-1 text-xs font-semibold uppercase tracking-widest text-brand-300">Pillar 4</span>
                    <h3 class="font-['Playfair_Display',serif] font-serif mb-3 text-xl font-bold leading-snug text-white">{{ $pillar4Title }}</h3>
                    <p class="flex-1 text-sm leading-relaxed text-brand-100">{{ $pillar4Summary }}</p>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-semibold text-brand-200 group-hover:text-white transition">
                        Explore Pillar
                        <svg class="h-4 w-4 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </div>
                </a>

            </div>

            {{-- Pillars CTA --}}
            <div class="mt-10 text-center">
                <a href="{{ route('pillars') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-crimson-700 hover:text-crimson-800 dark:text-brand-300 dark:hover:text-brand-200 transition">
                    View All Pillars in Detail
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- =========================================================
         SECTION E — FEATURED SECTORS
         py-20 bg-white dark:bg-zinc-950
         =========================================================
         CMS-MANAGED SECTIONS — Admin → CMS → Pages → "sectors" (slug)
         Page sections used:
           page_heading      : Section heading
           page_subtitle     : Section sub-heading
           maritime_body     : Maritime / Blue Economy description
           agriculture_body  : Agriculture description
           aviation_body     : Aviation description
           waterways_body    : Inland Waterways description
         ========================================================= --}}
    @if($sectorsPage)
        <section class="bg-white py-20 dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                {{-- Section header --}}
                <div class="mb-12">
                    <span class="text-xs font-semibold uppercase tracking-widest text-crimson-700">Strategic Focus</span>
                    <h2 class="font-['Playfair_Display',serif] font-serif mt-3 text-4xl font-bold text-zinc-900 dark:text-white lg:text-5xl">
                        {{ $sectorsPage->section('page_heading', 'Featured Sectors') }}
                    </h2>
                    @if($sectorsPage->section('page_subtitle'))
                        <p class="mt-4 max-w-2xl text-base text-zinc-500 dark:text-zinc-400">
                            {{ $sectorsPage->section('page_subtitle') }}
                        </p>
                    @endif
                </div>

                {{-- Sector cards: 2×2 sm, 4-col on lg --}}
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

                    {{-- Maritime / Blue Economy --}}
                    <div class="group rounded-2xl border border-zinc-100 bg-white p-6 shadow-sm transition hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 dark:bg-brand-900/40">
                            <svg class="h-6 w-6 text-brand-700 dark:text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-zinc-900 dark:text-white">Maritime / Blue Economy</h3>
                        <p class="text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">
                            {{ $sectorsPage->section('maritime_body', 'Bilateral maritime trade, port linkages, and blue economy investment corridors between Nigeria and Kenya.') }}
                        </p>
                    </div>

                    {{-- Agriculture --}}
                    <div class="group rounded-2xl border border-zinc-100 bg-white p-6 shadow-sm transition hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 dark:bg-brand-900/40">
                            <svg class="h-6 w-6 text-brand-700 dark:text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-zinc-900 dark:text-white">Agriculture</h3>
                        <p class="text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">
                            {{ $sectorsPage->section('agriculture_body', 'Agri-trade facilitation, food security partnerships, and agricultural commodity exchange across the corridor.') }}
                        </p>
                    </div>

                    {{-- Aviation --}}
                    <div class="group rounded-2xl border border-zinc-100 bg-white p-6 shadow-sm transition hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 dark:bg-brand-900/40">
                            <svg class="h-6 w-6 text-brand-700 dark:text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-zinc-900 dark:text-white">Aviation</h3>
                        <p class="text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">
                            {{ $sectorsPage->section('aviation_body', 'Air connectivity, cargo logistics, and aviation investment opportunities linking Lagos and Nairobi.') }}
                        </p>
                    </div>

                    {{-- Inland Waterways --}}
                    <div class="group rounded-2xl border border-zinc-100 bg-white p-6 shadow-sm transition hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-brand-100 dark:bg-brand-900/40">
                            <svg class="h-6 w-6 text-brand-700 dark:text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 font-semibold text-zinc-900 dark:text-white">Inland Waterways</h3>
                        <p class="text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">
                            {{ $sectorsPage->section('waterways_body', 'River and lake transport networks supporting intra-African trade connectivity and logistics infrastructure.') }}
                        </p>
                    </div>

                </div>

                {{-- Sectors CTA --}}
                <div class="mt-10">
                    <a href="{{ route('trade') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-crimson-700 hover:text-crimson-800 dark:text-brand-300 dark:hover:text-brand-200 transition">
                        Explore All Sectors & Trade Opportunities
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- =========================================================
         SECTION F — EVENTS PREVIEW
         py-20 bg-green-950
         =========================================================
         No CMS keys — events fetched live from DB (upcoming 3)
         ========================================================= --}}
    <section class="bg-brand-50 py-20 dark:bg-zinc-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <div class="mb-10 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-crimson-700">Upcoming</span>
                    <h2 class="font-['Playfair_Display',serif] font-serif mt-3 text-4xl font-bold text-zinc-900 dark:text-white lg:text-5xl">
                        Events &amp; Trade Missions
                    </h2>
                    <p class="mt-3 max-w-lg text-base text-zinc-500 dark:text-zinc-400">
                        Flagship summits, trade missions, and corridor activation events connecting Nigeria and Kenya.
                    </p>
                </div>
                <a href="{{ route('events.index') }}"
                    class="shrink-0 inline-flex items-center gap-2 rounded-full border border-crimson-700 px-5 py-2 text-sm font-semibold text-crimson-700 hover:bg-crimson-50 dark:border-crimson-400 dark:text-crimson-300 dark:hover:bg-crimson-950/40 transition">
                    View All Events
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>

            @if($upcomingEvents->isNotEmpty())
                {{-- Table-style events listing --}}
                <div class="overflow-hidden rounded-2xl border border-brand-200 dark:border-brand-900 shadow-sm">
                    {{-- Table header --}}
                    <div class="grid grid-cols-12 gap-4 bg-brand-200 dark:bg-brand-900 px-6 py-3 text-xs font-semibold uppercase tracking-wider text-brand-900 dark:text-brand-100">
                        <div class="col-span-2">Date</div>
                        <div class="col-span-5">Event</div>
                        <div class="col-span-3">Venue</div>
                        <div class="col-span-2 text-right">Type</div>
                    </div>
                    {{-- Table rows --}}
                    @foreach($upcomingEvents as $i => $event)
                        @php
                            $type = strtolower($event->type ?? 'event');
                            $isFlagship = in_array($type, ['flagship', 'summit', 'trade-mission']);
                        @endphp
                        <a href="{{ route('events.show', $event->id) }}"
                            class="group grid grid-cols-12 gap-4 items-center px-6 py-4 transition hover:bg-brand-100 dark:hover:bg-brand-950/30 {{ $i % 2 === 0 ? 'bg-white dark:bg-zinc-950' : 'bg-brand-50 dark:bg-zinc-900' }} border-t border-brand-100 dark:border-brand-900/50">
                            {{-- Date --}}
                            <div class="col-span-2 flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 flex-col items-center justify-center rounded-lg bg-crimson-700 text-white shadow-sm">
                                    <span class="text-xs font-bold leading-none">{{ $event->starts_at->format('d') }}</span>
                                    <span class="text-[10px] font-semibold uppercase leading-tight">{{ $event->starts_at->format('M') }}</span>
                                </div>
                                <span class="hidden text-xs text-zinc-400 sm:block">{{ $event->starts_at->format('Y') }}</span>
                            </div>
                            {{-- Title --}}
                            <div class="col-span-5">
                                <h3 class="font-semibold text-zinc-900 dark:text-white transition group-hover:text-crimson-700 dark:group-hover:text-brand-300 line-clamp-2">
                                    {{ $event->title }}
                                </h3>
                                <span class="mt-0.5 block text-xs text-zinc-400">{{ $event->starts_at->format('l, d F Y') }}</span>
                            </div>
                            {{-- Venue --}}
                            <div class="col-span-3">
                                @if($event->venue)
                                    <span class="flex items-center gap-1.5 text-sm text-zinc-500 dark:text-zinc-400">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $event->venue }}
                                    </span>
                                @else
                                    <span class="text-xs text-zinc-400">TBA</span>
                                @endif
                            </div>
                            {{-- Type badge --}}
                            <div class="col-span-2 flex justify-end">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    {{ $isFlagship ? 'bg-crimson-700 text-white' : 'bg-brand-100 text-brand-700 dark:bg-brand-900 dark:text-brand-300' }}">
                                    {{ ucwords(str_replace('-', ' ', $event->type ?? 'Event')) }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- Empty state --}}
                <div class="rounded-2xl border border-brand-200 bg-white px-8 py-16 text-center dark:border-brand-900 dark:bg-zinc-900">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-brand-100 dark:bg-brand-950">
                        <svg class="h-8 w-8 text-brand-600 dark:text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-base font-medium text-zinc-700 dark:text-zinc-300">No upcoming events — check back soon.</p>
                    <p class="mt-1 text-sm text-zinc-400">Stay tuned for our next trade mission and summit announcements.</p>
                </div>
            @endif

            {{-- Bottom CTA --}}
            <div class="mt-8 text-center">
                <a href="{{ route('events.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-crimson-700 hover:text-crimson-800 dark:text-brand-300 dark:hover:text-brand-200 transition">
                    View All Upcoming Events &rarr;
                </a>
            </div>
        </div>
    </section>

    {{-- =========================================================
         SECTION G — LATEST NEWS
         py-20 bg-zinc-50 dark:bg-zinc-900
         =========================================================
         No CMS keys — 3 latest published NewsArticle records
         ========================================================= --}}
    <section class="bg-zinc-50 py-20 dark:bg-zinc-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <div class="mb-12 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-crimson-700">Media</span>
                    <h2 class="font-['Playfair_Display',serif] font-serif mt-3 text-4xl font-bold text-zinc-900 dark:text-white lg:text-5xl">
                        Latest News
                    </h2>
                </div>
                <a href="{{ route('news.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-crimson-700 hover:text-crimson-800 dark:text-brand-300 dark:hover:text-brand-200 transition">
                    All News
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>

            @if($latestNews->isNotEmpty())
                @if($latestNews->count() >= 3)
                    {{-- Magazine layout: 1 large featured + 2 stacked on right --}}
                    <div class="grid gap-6 lg:grid-cols-3">

                        {{-- Featured article: 2/3 width --}}
                        @php $featured = $latestNews->first(); @endphp
                        <article class="group lg:col-span-2">
                            <a href="{{ route('news.show', $featured->slug) }}" class="block relative overflow-hidden rounded-2xl shadow-lg" style="min-height: 420px;">
                                {{-- Background image or gradient --}}
                                @if($featured->featuredImageUrl())
                                    <img
                                        src="{{ $featured->featuredImageUrl() }}"
                                        alt="{{ $featured->title }}"
                                        class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                    />
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-br from-green-900 to-crimson-800">
                                        <svg class="absolute inset-0 h-full w-full opacity-5" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="gn" x="0" y="0" width="32" height="32" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.5" fill="white"/></pattern></defs><rect width="100%" height="100%" fill="url(#gn)"/></svg>
                                    </div>
                                @endif
                                {{-- Gradient overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/90 via-zinc-950/30 to-transparent"></div>
                                {{-- Content --}}
                                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
                                    <div class="mb-3 flex items-center gap-3">
                                        <span class="inline-flex rounded-full bg-crimson-700 px-2.5 py-0.5 text-xs font-semibold text-white">
                                            {{ ucwords(str_replace('-', ' ', $featured->category ?? 'News')) }}
                                        </span>
                                        <span class="text-xs text-white/60">{{ $featured->published_at?->format('d M Y') }}</span>
                                    </div>
                                    <h3 class="font-serif text-xl font-bold leading-snug text-white sm:text-2xl group-hover:text-brand-200 transition">
                                        {{ $featured->title }}
                                    </h3>
                                    @if($featured->excerpt)
                                        <p class="mt-2 line-clamp-2 text-sm text-white/70">{{ $featured->excerpt }}</p>
                                    @endif
                                    <span class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-brand-200 group-hover:text-brand-100 transition">
                                        Read Full Story
                                        <svg class="h-3.5 w-3.5 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                    </span>
                                </div>
                            </a>
                        </article>

                        {{-- 2 smaller articles stacked --}}
                        <div class="flex flex-col gap-6">
                            @foreach($latestNews->skip(1) as $article)
                                <article class="group flex overflow-hidden rounded-2xl border border-zinc-100 bg-white shadow-sm transition hover:shadow-md dark:border-zinc-800 dark:bg-zinc-800">
                                    {{-- Thumbnail --}}
                                    <div class="w-28 shrink-0 overflow-hidden">
                                        @if($article->featuredImageUrl())
                                            <img
                                                src="{{ $article->featuredImageUrl() }}"
                                                alt="{{ $article->title }}"
                                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                            />
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-green-100 to-zinc-100 dark:from-zinc-700 dark:to-zinc-800">
                                                <svg class="h-8 w-8 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- Body --}}
                                    <a href="{{ route('news.show', $article->slug) }}" class="flex flex-1 flex-col justify-center p-4">
                                        <span class="text-xs text-zinc-400">{{ $article->published_at?->format('d M Y') }}</span>
                                        <h3 class="mt-1 line-clamp-2 text-sm font-semibold leading-snug text-zinc-900 transition group-hover:text-crimson-700 dark:text-white dark:group-hover:text-brand-300">
                                            {{ $article->title }}
                                        </h3>
                                        <span class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-crimson-700 dark:text-brand-300 group-hover:underline">
                                            Read More <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </span>
                                    </a>
                                </article>
                            @endforeach
                        </div>

                    </div>
                @else
                    {{-- Fallback: equal grid for < 3 articles --}}
                    <div class="grid gap-6 sm:grid-cols-2">
                        @foreach($latestNews as $article)
                            <article class="group flex flex-col overflow-hidden rounded-2xl border border-zinc-100 bg-white shadow-sm transition hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-800">
                                @if($article->featuredImageUrl())
                                    <div class="h-52 overflow-hidden">
                                        <img src="{{ $article->featuredImageUrl() }}" alt="{{ $article->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                    </div>
                                @else
                                    <div class="flex h-52 items-center justify-center bg-gradient-to-br from-green-100 to-zinc-100 dark:from-zinc-800 dark:to-zinc-900">
                                        <svg class="h-12 w-12 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                    </div>
                                @endif
                                <div class="flex flex-1 flex-col p-6">
                                    <div class="mb-3 flex items-center gap-2">
                                        <span class="inline-flex rounded-full bg-brand-100 px-2.5 py-0.5 text-xs font-semibold text-brand-700 dark:bg-brand-900/50 dark:text-brand-300">{{ ucwords(str_replace('-', ' ', $article->category ?? 'News')) }}</span>
                                        <span class="text-xs text-zinc-400">{{ $article->published_at?->format('d M Y') }}</span>
                                    </div>
                                    <h3 class="line-clamp-2 font-semibold leading-snug text-zinc-900 transition group-hover:text-crimson-700 dark:text-white dark:group-hover:text-brand-300">{{ $article->title }}</h3>
                                    @if($article->excerpt)
                                        <p class="mt-2.5 line-clamp-3 text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">{{ $article->excerpt }}</p>
                                    @endif
                                    <div class="mt-auto pt-5">
                                        <a href="{{ route('news.show', $article->slug) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-crimson-700 hover:text-crimson-800 dark:text-brand-300 transition">
                                            Read More <svg class="h-3.5 w-3.5 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="rounded-2xl border border-zinc-100 bg-white px-8 py-16 text-center dark:border-zinc-800 dark:bg-zinc-800">
                    <p class="text-base font-medium text-zinc-500 dark:text-zinc-400">No news articles published yet — check back soon.</p>
                </div>
            @endif

        </div>
    </section>

    {{-- =========================================================
         SECTION H — MEMBERSHIP CTA
         py-20 bg-zinc-900
         =========================================================
         CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Homepage"
         cta_heading : Section heading (default: "Ready to Join NiKCCIMA?")
         cta_body    : Supporting text
         ========================================================= --}}
    <section class="bg-zinc-900 py-20 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-16 lg:grid-cols-2 lg:items-start">

                {{-- Left: headline + benefits + CTAs --}}
                <div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-brand-300">Join NiKCCIMA</span>
                    <h2 class="font-['Playfair_Display',serif] font-serif mt-3 text-4xl font-bold leading-tight text-white lg:text-5xl">
                        {{ $page?->section('cta_heading', 'Ready to Join NiKCCIMA?') }}
                    </h2>
                    <p class="mt-5 text-base leading-relaxed text-zinc-400">
                        {{ $page?->section('cta_body', 'Become part of the governed bilateral trade chamber driving measurable AfCFTA outcomes between Nigeria and Kenya. Access structured trade corridors, policy advocacy, and a cross-border B2B network.') }}
                    </p>

                    {{-- Benefit bullets --}}
                    <ul class="mt-8 space-y-4">
                        @foreach([
                            'Direct market access across Nigeria and Kenya through structured corridor activation',
                            'Policy advocacy, NTB resolution support, and AfCFTA compliance guidance',
                            'Structured B2B pipeline, trade lead matching, and investment facilitation',
                            'Visibility across both countries, flagship events, and corridor trade missions',
                        ] as $benefit)
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-crimson-700">
                                    <svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span class="text-sm leading-relaxed text-zinc-300">{{ $benefit }}</span>
                            </li>
                        @endforeach
                    </ul>

                    {{-- CTA buttons --}}
                    <div class="mt-10 flex flex-wrap gap-4">
                        <a href="{{ route('membership.apply') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-crimson-700 px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-crimson-800 focus:outline-none focus:ring-2 focus:ring-crimson-500 focus:ring-offset-2 focus:ring-offset-zinc-900">
                            Apply for Membership
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="{{ route('membership') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-zinc-600 px-7 py-3.5 text-sm font-semibold text-zinc-300 transition hover:border-zinc-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 focus:ring-offset-zinc-900">
                            View Categories
                        </a>
                    </div>
                </div>

                {{-- Right: membership tier mini-cards 2×2 --}}
                <div class="grid grid-cols-2 gap-4">

                    {{-- Platinum --}}
                    @php
                        $platinum = $categories->firstWhere('name', 'Platinum') ?? $categories->first();
                        $gold     = $categories->firstWhere('name', 'Gold')     ?? $categories->skip(1)->first();
                        $silver   = $categories->firstWhere('name', 'Silver')   ?? $categories->skip(2)->first();
                        $bronze   = $categories->firstWhere('name', 'Bronze')   ?? $categories->skip(3)->first();
                    @endphp

                    @foreach([
                        ['tier' => 'Platinum', 'model' => $platinum, 'strip' => 'bg-gradient-to-r from-slate-400 to-slate-300', 'icon_color' => 'text-slate-300', 'label_color' => 'text-slate-300'],
                        ['tier' => 'Gold',     'model' => $gold,     'strip' => 'bg-gradient-to-r from-yellow-500 to-amber-400',  'icon_color' => 'text-yellow-400', 'label_color' => 'text-yellow-400'],
                        ['tier' => 'Silver',   'model' => $silver,   'strip' => 'bg-gradient-to-r from-zinc-400 to-zinc-300',     'icon_color' => 'text-zinc-400',  'label_color' => 'text-zinc-400'],
                        ['tier' => 'Bronze',   'model' => $bronze,   'strip' => 'bg-gradient-to-r from-orange-600 to-orange-400', 'icon_color' => 'text-orange-400','label_color' => 'text-orange-400'],
                    ] as $item)
                        <div class="overflow-hidden rounded-xl bg-zinc-800 transition hover:-translate-y-0.5 hover:shadow-xl">
                            {{-- Color strip --}}
                            <div class="h-1 {{ $item['strip'] }}"></div>
                            <div class="p-4">
                                <div class="mb-2 flex items-center gap-2">
                                    <svg class="h-4 w-4 {{ $item['icon_color'] }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="font-semibold text-white text-sm">{{ $item['tier'] }}</span>
                                </div>
                                @if($item['model'])
                                    @if($item['model']->fee_ngn)
                                        <p class="text-sm font-bold text-white">₦{{ number_format($item['model']->fee_ngn) }}</p>
                                    @endif
                                    @if($item['model']->fee_kes)
                                        <p class="mt-0.5 text-xs text-zinc-400">KES {{ number_format($item['model']->fee_kes) }}</p>
                                    @endif
                                @else
                                    <p class="text-xs text-zinc-400">Contact for pricing</p>
                                @endif
                                <p class="mt-1.5 text-[10px] font-semibold uppercase tracking-wide {{ $item['label_color'] }}">Membership Tier</p>
                            </div>
                        </div>
                    @endforeach

                    {{-- Disclaimer note spanning full width --}}
                    <div class="col-span-2 rounded-xl border border-zinc-700 bg-zinc-800/50 px-4 py-3">
                        <p class="text-xs leading-relaxed text-zinc-500">
                            Annual membership fees. Both NGN and KES pricing available. Contact the Secretariat for corporate and consortium rates.
                            <a href="{{ route('contact') }}" class="ml-1 font-medium text-brand-300 hover:text-brand-200">Get in touch &rarr;</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- =========================================================
         SECTION I — BOTTOM CTA STRIP
         Full-bleed, uses cta_background_image when available
         =========================================================
         CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Homepage"
         cta_background_image : Full-bleed background (upload 1920×500)
         ========================================================= --}}
    <section class="relative overflow-hidden bg-brand-200 py-24">
        {{-- Subtle dot pattern on celadon --}}
        <div class="absolute inset-0 opacity-[0.08]">
            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                <defs>
                    <pattern id="ctadots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1" fill="#1c6123"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#ctadots)"/>
            </svg>
        </div>

        {{-- Decorative blobs --}}
        <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-brand-300/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-crimson-700/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <span class="mb-4 inline-block rounded-full bg-crimson-700 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">Join the Chamber</span>
            <h2 class="font-['Playfair_Display',serif] font-serif text-4xl font-bold text-brand-950 lg:text-5xl">
                Ready to Grow Your Business Across the Corridor?
            </h2>
            <p class="mx-auto mt-5 max-w-2xl text-lg leading-relaxed text-brand-900">
                Join NiKCCIMA and access Africa's most structured bilateral trade network — B2B pipelines, policy advocacy, and flagship events connecting Nigeria and Kenya.
            </p>
            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('membership.apply') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-crimson-700 px-8 py-4 text-sm font-semibold text-white shadow-lg transition hover:bg-crimson-800 hover:shadow-xl">
                    Apply for Membership
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="{{ route('contact') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-brand-700 px-8 py-4 text-sm font-semibold text-brand-900 transition hover:bg-brand-300/50">
                    Contact the Secretariat
                </a>
            </div>
        </div>
    </section>

</x-layouts::website>
