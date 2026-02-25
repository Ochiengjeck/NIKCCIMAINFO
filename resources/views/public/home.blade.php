<x-layouts::website :title="'NiKCCIMA — Driving Structured Trade Between Nigeria and Kenya'">

    {{-- =========================================================
         SECTION A — HERO
         Full-bleed, min-height: calc(100vh - 64px), relative
         =========================================================
         CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Homepage"
         hero_title         : Main headline (text)
         hero_subtitle      : Sub-paragraph (text)
         hero_image         : Full-bleed background image path (upload 1920×800 via Admin → Media Library → PLACEHOLDER UNTIL UPLOADED)
         hero_cta_primary   : Primary CTA button label (default: "Become a Member")
         hero_cta_secondary : Secondary CTA button label (default: "Explore Trade Opportunities")
         ========================================================= --}}
    <section
        class="relative overflow-hidden text-white"
        style="min-height: calc(100vh - 64px);
               @if($page?->section('hero_image'))
                   background-image: url('{{ asset($page->section('hero_image')) }}');
                   background-size: cover;
                   background-position: center;
               @endif"
    >
        {{-- Background layer: image overlay OR gradient + dot pattern --}}
        @if($page?->section('hero_image'))
            {{-- Dark overlay for image background --}}
            <div class="absolute inset-0 bg-green-950/80"></div>
        @else
            {{-- Gradient background --}}
            <div class="absolute inset-0 bg-gradient-to-br from-green-950 via-green-900 to-green-800"></div>
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
        <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-green-700/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-red-800/10 blur-3xl pointer-events-none"></div>

        {{-- Hero content: vertically centered --}}
        <div class="relative flex items-center" style="min-height: calc(100vh - 64px);">
            <div class="mx-auto w-full max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
                <div class="max-w-3xl">

                    {{-- AfCFTA badge pill --}}
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-2 rounded-full bg-red-600 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white shadow-sm">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            AfCFTA Corridor Execution
                        </span>
                    </div>

                    {{-- Main headline --}}
                    <h1 class="font-['Playfair_Display',serif] font-serif text-5xl font-bold leading-[1.1] text-white lg:text-7xl">
                        {{ $page?->section('hero_title', 'Driving Structured Trade Between Nigeria and Kenya.') }}
                    </h1>

                    {{-- Sub-paragraph --}}
                    <p class="mt-6 max-w-2xl text-lg leading-relaxed text-green-200">
                        {{ $page?->section('hero_subtitle', 'NiKCCIMA is the premier bilateral trade chamber operationalising the AfCFTA corridor between Nigeria and Kenya — with governance, structure, and measurable outcomes.') }}
                    </p>

                    {{-- CTA buttons --}}
                    <div class="mt-10 flex flex-wrap gap-4">
                        <a href="{{ route('membership.apply') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-semibold text-green-900 shadow-lg transition hover:bg-green-50 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-green-900">
                            {{ $page?->section('hero_cta_primary', 'Become a Member') }}
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="{{ route('trade') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-white/40 bg-white/10 px-7 py-3.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20 hover:border-white/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                            {{ $page?->section('hero_cta_secondary', 'Explore Trade Opportunities') }}
                        </a>
                        <a href="{{ route('downloads') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-white/25 px-7 py-3.5 text-sm font-semibold text-green-200 transition hover:border-white/40 hover:text-white focus:outline-none focus:ring-2 focus:ring-white/30">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Institutional Profile
                        </a>
                    </div>

                    {{-- Trust indicators --}}
                    <div class="mt-14 flex flex-wrap items-center gap-x-8 gap-y-3">
                        <span class="text-xs font-medium uppercase tracking-widest text-green-400">Recognised by</span>
                        <span class="text-sm font-semibold text-white/60">African Union</span>
                        <span class="h-4 w-px bg-white/20"></span>
                        <span class="text-sm font-semibold text-white/60">AfCFTA Secretariat</span>
                        <span class="h-4 w-px bg-white/20"></span>
                        <span class="text-sm font-semibold text-white/60">ECOWAS Framework</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="h-6 w-6 text-green-400/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </section>

    {{-- =========================================================
         SECTION B — LIVE METRICS STRIP
         bg-green-900 py-12
         =========================================================
         No CMS keys — values pulled live from DB
         (Member, TradeLead, Corridor, Ntb models)
         ========================================================= --}}
    <section class="bg-green-900 py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-y-10 gap-x-4 lg:grid-cols-4">

                {{-- Active Members --}}
                <div class="flex flex-col items-center text-center lg:border-r lg:border-green-800 lg:pr-8">
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
                    <span class="mt-2 text-sm uppercase tracking-wide text-green-300">Active Members</span>
                </div>

                {{-- Trade Leads --}}
                <div class="flex flex-col items-center text-center lg:border-r lg:border-green-800 lg:pr-8">
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
                    <span class="mt-2 text-sm uppercase tracking-wide text-green-300">Trade Leads</span>
                </div>

                {{-- Active Corridors --}}
                <div class="flex flex-col items-center text-center lg:border-r lg:border-green-800 lg:pr-8">
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
                    <span class="mt-2 text-sm uppercase tracking-wide text-green-300">Active Corridors</span>
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
                    <span class="mt-2 text-sm uppercase tracking-wide text-green-300">NTBs Resolved</span>
                </div>

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
                    <span class="text-xs font-semibold uppercase tracking-widest text-red-600">Our Institution</span>
                    <h2 class="font-['Playfair_Display',serif] font-serif mt-3 text-4xl font-bold leading-tight text-zinc-900 dark:text-white lg:text-5xl">
                        {{ $page?->section('about_heading', 'About NiKCCIMA') }}
                    </h2>
                    <div class="prose prose-zinc dark:prose-invert mt-6 max-w-none text-lg leading-relaxed text-zinc-600 dark:text-zinc-400">
                        <p>{{ $page?->section('about_body', 'The Nigeria-Kenya Chamber of Commerce, Industry, Mines & Agriculture (NiKCCIMA) is the premier bilateral institution operationalising the African Continental Free Trade Area corridor between Nigeria and Kenya. We deliver measurable, governance-backed trade outcomes through structured KPI systems, NTB resolution mechanisms, and bilateral corridor activation.') }}</p>
                    </div>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('about') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-green-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">
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
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 dark:bg-green-900/40">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Governance-Backed</h3>
                        <p class="mt-1.5 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">Formal bilateral framework with institutional oversight from both Nigeria and Kenya.</p>
                    </div>

                    {{-- Structured KPI System --}}
                    <div class="rounded-2xl border border-zinc-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 dark:bg-green-900/40">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Structured KPI System</h3>
                        <p class="mt-1.5 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">Measurable outcomes tracked against defined trade and investment performance indicators.</p>
                    </div>

                    {{-- Bilateral Corridor Model --}}
                    <div class="rounded-2xl border border-zinc-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 dark:bg-green-900/40">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Bilateral Corridor Model</h3>
                        <p class="mt-1.5 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">Dedicated Nigeria–Kenya trade corridor with structured B2B pipeline and deal facilitation.</p>
                    </div>

                    {{-- AfCFTA Aligned --}}
                    <div class="rounded-2xl border border-zinc-100 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 dark:bg-green-900/40">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <span class="text-xs font-semibold uppercase tracking-widest text-red-600">How We Work</span>
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
                    class="group relative flex flex-col overflow-hidden rounded-2xl bg-gradient-to-br from-green-900 to-green-800 p-8 text-white shadow-lg transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-white/10">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="mb-1 text-xs font-semibold uppercase tracking-widest text-green-400">Pillar 1</span>
                    <h3 class="font-['Playfair_Display',serif] font-serif mb-3 text-xl font-bold leading-snug text-white">{{ $pillar1Title }}</h3>
                    <p class="flex-1 text-sm leading-relaxed text-green-200">{{ $pillar1Summary }}</p>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-semibold text-green-300 group-hover:text-white transition">
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
                    class="group relative flex flex-col overflow-hidden rounded-2xl bg-gradient-to-br from-green-800 to-emerald-700 p-8 text-white shadow-lg transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-white/10">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <span class="mb-1 text-xs font-semibold uppercase tracking-widest text-emerald-300">Pillar 2</span>
                    <h3 class="font-['Playfair_Display',serif] font-serif mb-3 text-xl font-bold leading-snug text-white">{{ $pillar2Title }}</h3>
                    <p class="flex-1 text-sm leading-relaxed text-green-200">{{ $pillar2Summary }}</p>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-semibold text-emerald-300 group-hover:text-white transition">
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
                    class="group relative flex flex-col overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-800 to-green-700 p-8 text-white shadow-lg transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-white/10">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="mb-1 text-xs font-semibold uppercase tracking-widest text-emerald-300">Pillar 3</span>
                    <h3 class="font-['Playfair_Display',serif] font-serif mb-3 text-xl font-bold leading-snug text-white">{{ $pillar3Title }}</h3>
                    <p class="flex-1 text-sm leading-relaxed text-green-200">{{ $pillar3Summary }}</p>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-semibold text-emerald-300 group-hover:text-white transition">
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
                    class="group relative flex flex-col overflow-hidden rounded-2xl bg-gradient-to-br from-green-700 to-teal-700 p-8 text-white shadow-lg transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-white/10">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="mb-1 text-xs font-semibold uppercase tracking-widest text-teal-300">Pillar 4</span>
                    <h3 class="font-['Playfair_Display',serif] font-serif mb-3 text-xl font-bold leading-snug text-white">{{ $pillar4Title }}</h3>
                    <p class="flex-1 text-sm leading-relaxed text-green-200">{{ $pillar4Summary }}</p>
                    <div class="mt-6 flex items-center gap-1.5 text-xs font-semibold text-teal-300 group-hover:text-white transition">
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
                    class="inline-flex items-center gap-2 text-sm font-semibold text-green-700 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition">
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
                    <span class="text-xs font-semibold uppercase tracking-widest text-red-600">Strategic Focus</span>
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
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/40">
                            <svg class="h-6 w-6 text-green-700 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/40">
                            <svg class="h-6 w-6 text-green-700 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/40">
                            <svg class="h-6 w-6 text-green-700 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/40">
                            <svg class="h-6 w-6 text-green-700 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        class="inline-flex items-center gap-2 text-sm font-semibold text-green-700 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition">
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
    <section class="bg-green-950 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <div class="mb-12 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-widest text-red-400">Upcoming</span>
                    <h2 class="font-['Playfair_Display',serif] font-serif mt-3 text-4xl font-bold text-white lg:text-5xl">
                        Events &amp; Trade Missions
                    </h2>
                    <p class="mt-3 max-w-lg text-base text-green-300">
                        Flagship summits, trade missions, and corridor activation events connecting Nigeria and Kenya.
                    </p>
                </div>
                <a href="{{ route('events.index') }}"
                    class="shrink-0 inline-flex items-center gap-2 text-sm font-semibold text-green-400 hover:text-white transition">
                    View All Events
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>

            @if($upcomingEvents->isNotEmpty())
                <div class="grid gap-6 sm:grid-cols-3">
                    @foreach($upcomingEvents as $event)
                        <a href="{{ route('events.show', $event->id) }}"
                            class="group flex flex-col overflow-hidden rounded-2xl border border-green-800 bg-green-900 transition hover:border-green-600 hover:shadow-2xl hover:shadow-green-900/50">

                            {{-- Date display bar --}}
                            <div class="flex items-center gap-4 border-b border-green-800 px-6 py-5">
                                <div class="text-center">
                                    <span class="font-['Playfair_Display',serif] font-serif block text-4xl font-bold leading-none text-white">
                                        {{ $event->starts_at->format('d') }}
                                    </span>
                                    <span class="mt-0.5 block text-sm font-semibold uppercase tracking-wide text-green-400">
                                        {{ $event->starts_at->format('M') }}
                                    </span>
                                    <span class="block text-xs text-green-500">
                                        {{ $event->starts_at->format('Y') }}
                                    </span>
                                </div>
                                <div class="h-10 w-px bg-green-800"></div>
                                <div>
                                    {{-- Event type badge --}}
                                    @php
                                        $type = strtolower($event->type ?? 'event');
                                        $isFlagship = in_array($type, ['flagship', 'summit', 'trade-mission']);
                                    @endphp
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                        {{ $isFlagship ? 'bg-red-600 text-white' : 'bg-green-700 text-green-200' }}">
                                        {{ ucwords(str_replace('-', ' ', $event->type ?? 'Event')) }}
                                    </span>
                                    <span class="mt-1 block text-xs text-green-400">
                                        {{ $event->starts_at->format('l') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Event body --}}
                            <div class="flex flex-1 flex-col p-6">
                                <h3 class="font-semibold leading-snug text-white transition group-hover:text-green-300">
                                    {{ $event->title }}
                                </h3>

                                @if($event->venue)
                                    <div class="mt-3 flex items-start gap-1.5">
                                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="text-sm text-green-400">{{ $event->venue }}</span>
                                    </div>
                                @endif

                                <div class="mt-auto pt-5 flex items-center gap-1.5 text-xs font-semibold text-green-400 group-hover:text-green-300 transition">
                                    View Event Details
                                    <svg class="h-3.5 w-3.5 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- Empty state --}}
                <div class="rounded-2xl border border-green-800 bg-green-900/50 px-8 py-16 text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-800">
                        <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-base font-medium text-green-200">No upcoming events — check back soon.</p>
                    <p class="mt-1 text-sm text-green-400">Stay tuned for our next trade mission and summit announcements.</p>
                </div>
            @endif

            {{-- Bottom CTA --}}
            <div class="mt-10 text-center">
                <a href="{{ route('events.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-green-400 hover:text-white transition">
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
                    <span class="text-xs font-semibold uppercase tracking-widest text-red-600">Media</span>
                    <h2 class="font-['Playfair_Display',serif] font-serif mt-3 text-4xl font-bold text-zinc-900 dark:text-white lg:text-5xl">
                        Latest News
                    </h2>
                </div>
                <a href="{{ route('news.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-700 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition">
                    All News
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>

            @if($latestNews->isNotEmpty())
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($latestNews as $article)
                        <article class="group flex flex-col overflow-hidden rounded-2xl border border-zinc-100 bg-white shadow-sm transition hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-800">

                            {{-- Featured image or placeholder --}}
                            @if($article->featuredImageUrl())
                                <div class="h-52 w-full overflow-hidden">
                                    <img
                                        src="{{ $article->featuredImageUrl() }}"
                                        alt="{{ $article->title }}"
                                        class="h-52 w-full object-cover transition duration-500 group-hover:scale-105"
                                    >
                                </div>
                            @else
                                <div class="flex h-52 w-full items-center justify-center bg-gradient-to-br from-green-100 to-zinc-100 dark:from-zinc-800 dark:to-zinc-900">
                                    <svg class="h-12 w-12 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Card body --}}
                            <div class="flex flex-1 flex-col p-6">
                                {{-- Category + date --}}
                                <div class="mb-3 flex items-center gap-2">
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700 dark:bg-green-900/50 dark:text-green-400">
                                        {{ ucwords(str_replace('-', ' ', $article->category ?? 'News')) }}
                                    </span>
                                    <span class="text-xs text-zinc-400">
                                        {{ $article->published_at?->format('d M Y') }}
                                    </span>
                                </div>

                                {{-- Title --}}
                                <h3 class="line-clamp-2 font-semibold leading-snug text-zinc-900 transition group-hover:text-green-700 dark:text-white dark:group-hover:text-green-400">
                                    {{ $article->title }}
                                </h3>

                                {{-- Excerpt --}}
                                @if($article->excerpt ?? null)
                                    <p class="mt-2.5 line-clamp-3 text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">
                                        {{ $article->excerpt }}
                                    </p>
                                @endif

                                {{-- Read more link --}}
                                <div class="mt-auto pt-5">
                                    <a href="{{ route('news.show', $article->slug) }}"
                                        class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-700 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition">
                                        Read More
                                        <svg class="h-3.5 w-3.5 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
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
                    <span class="text-xs font-semibold uppercase tracking-widest text-green-400">Join NiKCCIMA</span>
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
                                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-green-700">
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
                            class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-7 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-900">
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
                        ['tier' => 'Platinum', 'model' => $platinum, 'icon_color' => 'text-slate-300', 'border' => 'border-slate-600'],
                        ['tier' => 'Gold',     'model' => $gold,     'icon_color' => 'text-yellow-400', 'border' => 'border-yellow-800'],
                        ['tier' => 'Silver',   'model' => $silver,   'icon_color' => 'text-zinc-400',  'border' => 'border-zinc-600'],
                        ['tier' => 'Bronze',   'model' => $bronze,   'icon_color' => 'text-orange-400','border' => 'border-orange-900'],
                    ] as $item)
                        <div class="rounded-xl border {{ $item['border'] }} bg-zinc-800 p-4">
                            <div class="mb-2 flex items-center gap-2">
                                <svg class="h-4 w-4 {{ $item['icon_color'] }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="font-semibold text-white text-sm">{{ $item['tier'] }}</span>
                            </div>
                            @if($item['model'])
                                @if($item['model']->fee_ngn)
                                    <p class="text-sm font-bold text-white">
                                        ₦{{ number_format($item['model']->fee_ngn) }}
                                    </p>
                                @endif
                                @if($item['model']->fee_kes)
                                    <p class="mt-0.5 text-xs text-zinc-400">
                                        KES {{ number_format($item['model']->fee_kes) }}
                                    </p>
                                @endif
                            @else
                                <p class="text-xs text-zinc-400">Contact for pricing</p>
                            @endif
                            <p class="mt-1.5 text-xs text-zinc-500">Membership Tier</p>
                        </div>
                    @endforeach

                    {{-- Disclaimer note spanning full width --}}
                    <div class="col-span-2 rounded-xl border border-zinc-700 bg-zinc-800/50 px-4 py-3">
                        <p class="text-xs leading-relaxed text-zinc-500">
                            Annual membership fees. Both NGN and KES pricing available. Contact the Secretariat for corporate and consortium rates.
                            <a href="{{ route('contact') }}" class="ml-1 font-medium text-green-500 hover:text-green-400">Get in touch &rarr;</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

</x-layouts::website>
