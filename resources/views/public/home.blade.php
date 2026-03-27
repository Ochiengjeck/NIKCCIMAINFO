<x-layouts::website :title="'NiKCCIMA — Driving Structured Trade Between Nigeria and Kenya'">

    {{-- =========================================================
         SECTION A — HERO CAROUSEL (3 slides, Alpine.js, 6s auto-rotate)
         Full-viewport, opacity crossfade
         CMS keys: hero_image, hero_title, hero_subtitle,
                   hero2_title, hero2_subtitle,
                   hero3_title, hero3_subtitle
         =========================================================
    --}}
    <section
        class="relative overflow-hidden"
        style="min-height: calc(100vh - 80px);"
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
        {{-- Background --}}
        @if($page?->section('hero_image'))
            <div class="absolute inset-0"
                 style="background-image: url('{{ asset($page->section('hero_image')) }}'); background-size: cover; background-position: center;">
            </div>
            <div class="absolute inset-0 bg-zinc-950/60"></div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-800 to-[#3b0d0f]"></div>
            {{-- Dot SVG pattern at 5% opacity --}}
            <div class="absolute inset-0" style="opacity:0.05;">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                    <defs>
                        <pattern id="dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1" fill="#A8DCAB"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dots)"/>
                </svg>
            </div>
        @endif

        {{-- Slides container — centered, single column --}}
        <div
            class="relative flex items-center justify-center"
            style="min-height: calc(100vh - 80px);"
        >
            <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8 py-20 text-center grid">

                {{-- SLIDE 1 —— Trade / AfCFTA --}}
                <div style="grid-area: 1/1;"
                     :class="active === 0 ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
                     class="transition-opacity duration-500 ease-in-out">
                    <h2 class="text-5xl lg:text-6xl font-bold font-serif text-white leading-tight mb-6">
                        {!! $page?->section('hero_title', 'Driving <em class="not-italic text-brand-200">Structured Trade</em> Between Nigeria and Kenya') !!}
                    </h2>
                    <div class="w-20 h-1.5 bg-brand-200 rounded-full mx-auto my-8"></div>
                    <p class="text-white/80 text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
                        {{ $page?->section('hero_subtitle', 'NiKCCIMA is the premier bilateral trade chamber operationalising the AfCFTA corridor between Nigeria and Kenya — with governance, structure, and measurable outcomes.') }}
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ route('membership.apply') }}"
                           class="inline-block bg-brand-500 text-white px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                            Become a Member
                        </a>
                        <a href="{{ route('trade') }}"
                           class="inline-block border border-white/60 text-white px-8 py-3 rounded text-sm font-medium hover:bg-white/10 transition-all">
                            Explore Trade
                        </a>
                    </div>
                </div>

                {{-- SLIDE 2 —— Membership --}}
                <div style="grid-area: 1/1;"
                     :class="active === 1 ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
                     class="transition-opacity duration-500 ease-in-out">
                    <h2 class="text-5xl lg:text-6xl font-bold font-serif text-white leading-tight mb-6">
                        {!! $page?->section('hero2_title', 'Connecting <em class="not-italic text-brand-200">Africa\'s</em> Two Largest Economies') !!}
                    </h2>
                    <div class="w-20 h-1.5 bg-brand-200 rounded-full mx-auto my-8"></div>
                    <p class="text-white/80 text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
                        {{ $page?->section('hero2_subtitle', 'Join a structured bilateral chamber with members spanning trade, finance, agriculture, technology, and maritime sectors across Nigeria and Kenya.') }}
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ route('membership') }}"
                           class="inline-block bg-brand-500 text-white px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                            View Membership
                        </a>
                        <a href="{{ route('membership.apply') }}"
                           class="inline-block border border-white/60 text-white px-8 py-3 rounded text-sm font-medium hover:bg-white/10 transition-all">
                            Apply Now
                        </a>
                    </div>
                </div>

                {{-- SLIDE 3 —— Events --}}
                <div style="grid-area: 1/1;"
                     :class="active === 2 ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
                     class="transition-opacity duration-500 ease-in-out">
                    <h2 class="text-5xl lg:text-6xl font-bold font-serif text-white leading-tight mb-6">
                        {!! $page?->section('hero3_title', 'Flagship <em class="not-italic text-brand-200">Summits.</em> Real Trade Outcomes.') !!}
                    </h2>
                    <div class="w-20 h-1.5 bg-brand-200 rounded-full mx-auto my-8"></div>
                    <p class="text-white/80 text-lg max-w-2xl mx-auto mb-10 leading-relaxed">
                        {{ $page?->section('hero3_subtitle', "Attend NiKCCIMA's corridor activation summits, bilateral trade missions, and B2B matching events — where Nigeria meets Kenya in structured, high-value commerce.") }}
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ route('events.index') }}"
                           class="inline-block bg-brand-500 text-white px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                            View Events
                        </a>
                        <a href="{{ route('contact') }}"
                           class="inline-block border border-white/60 text-white px-8 py-3 rounded text-sm font-medium hover:bg-white/10 transition-all">
                            Contact Us
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Left arrow --}}
        <button
            @click="prev()"
            class="absolute left-4 top-1/2 -translate-y-1/2 h-10 w-10 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center text-white transition-all"
            aria-label="Previous slide"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Right arrow --}}
        <button
            @click="next()"
            class="absolute right-4 top-1/2 -translate-y-1/2 h-10 w-10 rounded-full bg-white/20 hover:bg-white/40 flex items-center justify-center text-white transition-all"
            aria-label="Next slide"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        {{-- Dot navigation --}}
        <div class="absolute bottom-8 left-0 right-0 flex justify-center gap-3">
            <template x-for="i in total" :key="i">
                <button
                    @click="go(i - 1)"
                    :class="active === (i - 1) ? 'bg-white w-6' : 'bg-white/40 w-3'"
                    class="h-3 rounded-full transition-all duration-300"
                    :aria-label="`Go to slide ${i}`"
                ></button>
            </template>
        </div>
    </section>

    {{-- =========================================================
         SECTION B — SERVICES "What We Do"
         =========================================================
    --}}
    <section class="py-[90px] bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <span class="block text-xs font-bold uppercase tracking-widest text-crimson-700 text-center mb-3">What We Do</span>
            <h2 class="text-3xl lg:text-4xl font-bold font-serif text-zinc-900 text-center mb-4">Our Core Services</h2>
            <div class="w-20 h-1.5 bg-brand-500 rounded-full mx-auto mb-14"></div>

            {{-- 2-column grid of service cards --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- Card 1: Market Penetration --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:shadow-lg transition-all duration-500">
                    <div class="float-left mr-8 mb-6">
                        <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">Market Penetration</h4>
                    <p class="text-[15px] leading-[26px] text-zinc-600">We help businesses penetrate both the Nigerian and Kenyan markets, leveraging AfCFTA preferential trade terms.</p>
                    <div class="clear-both"></div>
                </div>

                {{-- Card 2: Investment Facilitation --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:shadow-lg transition-all duration-500">
                    <div class="float-left mr-8 mb-6">
                        <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">Investment Facilitation</h4>
                    <p class="text-[15px] leading-[26px] text-zinc-600">Connecting investors with high-value opportunities across the Nigeria-Kenya economic corridor.</p>
                    <div class="clear-both"></div>
                </div>

                {{-- Card 3: Policy Advocacy --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:shadow-lg transition-all duration-500">
                    <div class="float-left mr-8 mb-6">
                        <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">Policy Advocacy</h4>
                    <p class="text-[15px] leading-[26px] text-zinc-600">Engaging governments and regulators to eliminate Non-Tariff Barriers and improve the trade environment.</p>
                    <div class="clear-both"></div>
                </div>

                {{-- Card 4: AfCFTA Compliance --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:shadow-lg transition-all duration-500">
                    <div class="float-left mr-8 mb-6">
                        <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">AfCFTA Compliance</h4>
                    <p class="text-[15px] leading-[26px] text-zinc-600">Guiding businesses through AfCFTA rules of origin, customs harmonisation and certification requirements.</p>
                    <div class="clear-both"></div>
                </div>

                {{-- Card 5: Trade Missions --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:shadow-lg transition-all duration-500">
                    <div class="float-left mr-8 mb-6">
                        <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">Trade Missions</h4>
                    <p class="text-[15px] leading-[26px] text-zinc-600">Organising bilateral trade delegations, B2B matchmaking summits and joint investment forums.</p>
                    <div class="clear-both"></div>
                </div>

                {{-- Card 6: Member Services --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:shadow-lg transition-all duration-500">
                    <div class="float-left mr-8 mb-6">
                        <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">Member Services</h4>
                    <p class="text-[15px] leading-[26px] text-zinc-600">A full suite of secretariat services: attestation, ATA Carnets, mediation, and dispute resolution.</p>
                    <div class="clear-both"></div>
                </div>

            </div>
        </div>
    </section>

    {{-- =========================================================
         SECTION C — MEMBERSHIP TIERS
         =========================================================
    --}}
    <section class="py-20 bg-zinc-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <span class="block text-xs font-bold uppercase tracking-widest text-crimson-700 text-center mb-3">Membership</span>
            <h2 class="text-3xl lg:text-4xl font-bold font-serif text-zinc-900 text-center mb-4">Our Membership Tiers</h2>
            <div class="w-20 h-1.5 bg-brand-500 rounded-full mx-auto mb-14"></div>

            {{-- Table --}}
            <div class="max-w-5xl mx-auto">

                {{-- Header row --}}
                <div class="flex items-center border-b-2 border-crimson-700 pb-4 mb-2">
                    <span class="w-1/4 text-sm font-bold uppercase tracking-wide text-crimson-700">Membership Tier</span>
                    <span class="w-1/4 text-sm font-bold uppercase tracking-wide text-crimson-700">Annual Fee</span>
                    <span class="w-1/2 text-sm font-bold uppercase tracking-wide text-crimson-700">Key Benefits</span>
                </div>

                {{-- Data rows --}}
                @forelse($categories as $cat)
                <div class="flex items-start border-b border-zinc-200 py-5 group hover:bg-white transition-colors rounded">
                    <span class="w-1/4 font-semibold text-zinc-900 pr-4">{{ $cat->name }}</span>
                    <span class="w-1/4 text-zinc-700 font-medium pr-4">
                        @if($cat->fee_ngn) ₦{{ number_format($cat->fee_ngn) }} @endif
                        @if($cat->fee_kes) / KES {{ number_format($cat->fee_kes) }} @endif
                    </span>
                    <span class="w-1/2 text-zinc-600 text-sm leading-relaxed">{{ $cat->description ?? "Full access to NiKCCIMA's trade facilitation services, bilateral events, and member network." }}</span>
                </div>
                @empty
                <div class="flex items-start border-b border-zinc-200 py-5 group hover:bg-white transition-colors rounded">
                    <span class="w-1/4 font-semibold text-zinc-900 pr-4">Patron's Circle</span>
                    <span class="w-1/4 text-zinc-700 font-medium pr-4">₦5,000,000 / yr</span>
                    <span class="w-1/2 text-zinc-600 text-sm leading-relaxed">Full secretariat services, VIP event access, executive matchmaking, policy roundtables, dedicated trade desk, and exclusive corridor advisory.</span>
                </div>
                <div class="flex items-start border-b border-zinc-200 py-5 group hover:bg-white transition-colors rounded">
                    <span class="w-1/4 font-semibold text-zinc-900 pr-4">Premier Member</span>
                    <span class="w-1/4 text-zinc-700 font-medium pr-4">₦2,500,000 / yr</span>
                    <span class="w-1/2 text-zinc-600 text-sm leading-relaxed">Priority trade leads, B2B missions, compliance advisory, chamber directory listing, and bilateral event priority registration.</span>
                </div>
                <div class="flex items-start border-b border-zinc-200 py-5 group hover:bg-white transition-colors rounded">
                    <span class="w-1/4 font-semibold text-zinc-900 pr-4">Standard Member</span>
                    <span class="w-1/4 text-zinc-700 font-medium pr-4">₦750,000 / yr</span>
                    <span class="w-1/2 text-zinc-600 text-sm leading-relaxed">Chamber membership, newsletter, events access, AfCFTA guidance, and directory listing.</span>
                </div>
                @endforelse

            </div>

            {{-- CTA link --}}
            <div class="text-center mt-10">
                <a href="{{ route('membership') }}" class="text-crimson-700 font-semibold hover:underline text-sm">
                    View All Membership Options →
                </a>
            </div>

        </div>
    </section>

    {{-- =========================================================
         SECTION D — SECTORS "Industries We Serve"
         =========================================================
    --}}
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <span class="block text-xs font-bold uppercase tracking-widest text-crimson-700 text-center mb-3">Sectors</span>
            <h2 class="text-3xl lg:text-4xl font-bold font-serif text-zinc-900 text-center mb-4">Industries We Serve</h2>
            <div class="w-20 h-1.5 bg-brand-500 rounded-full mx-auto mb-14"></div>

            {{-- 3-column grid (2 rows × 3) --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-8">

                {{-- Agriculture --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:shadow-lg transition-all duration-300">
                    <div class="flex justify-center mb-4">
                        <div class="h-32 w-32 rounded bg-brand-100 flex items-center justify-center">
                            <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900">Agriculture &amp; Agro-Processing</h4>
                </div>

                {{-- Manufacturing --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:shadow-lg transition-all duration-300">
                    <div class="flex justify-center mb-4">
                        <div class="h-32 w-32 rounded bg-brand-100 flex items-center justify-center">
                            <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900">Manufacturing &amp; Industry</h4>
                </div>

                {{-- Technology --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:shadow-lg transition-all duration-300">
                    <div class="flex justify-center mb-4">
                        <div class="h-32 w-32 rounded bg-brand-100 flex items-center justify-center">
                            <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900">Technology &amp; Innovation</h4>
                </div>

                {{-- Financial Services --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:shadow-lg transition-all duration-300">
                    <div class="flex justify-center mb-4">
                        <div class="h-32 w-32 rounded bg-brand-100 flex items-center justify-center">
                            <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900">Financial Services</h4>
                </div>

                {{-- Energy --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:shadow-lg transition-all duration-300">
                    <div class="flex justify-center mb-4">
                        <div class="h-32 w-32 rounded bg-brand-100 flex items-center justify-center">
                            <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900">Energy &amp; Resources</h4>
                </div>

                {{-- Health --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:shadow-lg transition-all duration-300">
                    <div class="flex justify-center mb-4">
                        <div class="h-32 w-32 rounded bg-brand-100 flex items-center justify-center">
                            <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900">Health &amp; Pharmaceuticals</h4>
                </div>

            </div>
        </div>
    </section>

    {{-- =========================================================
         SECTION E — CTA STRIP
         =========================================================
    --}}
    <section class="bg-crimson-700 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-10">
                <h4 class="text-2xl lg:text-3xl font-bold font-serif text-white text-center lg:text-left lg:w-1/2">
                    High priority trade and investment <em class="not-italic text-brand-200">opportunities</em> between Nigeria and Kenya
                </h4>
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('trade') }}"
                       class="inline-block bg-brand-500 text-white px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                        Discover More
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-block border border-white/60 text-white px-8 py-3 rounded text-sm font-medium hover:bg-white/10 transition-all">
                        Contact Us
                    </a>
                </div>
            </div>
            <p class="text-center text-white/80 mt-10 text-lg">
                We are open for membership. Kindly apply
                <a href="{{ route('membership.apply') }}" class="text-brand-200 underline hover:text-white transition-colors">here</a>.
            </p>
        </div>
    </section>

    {{-- =========================================================
         SECTION F — ABOUT NiKCCIMA
         =========================================================
    --}}
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <span class="block text-xs font-bold uppercase tracking-widest text-crimson-700 text-center mb-3">About Us</span>
            <div class="w-20 h-1.5 bg-brand-500 rounded-full mx-auto mb-14"></div>

            {{-- 2-column layout: 2/5 left + 3/5 right --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 items-start">

                {{-- Left: Who We Are --}}
                <div class="lg:col-span-2">
                    <h4 class="text-2xl font-bold font-serif text-zinc-900 mb-6 leading-snug">Who We Are</h4>
                    <p class="text-[15px] leading-[26px] text-zinc-600 mb-8">
                        {{ $page?->section('hero_subtitle', 'NiKCCIMA is the premier bilateral chamber operationalising the AfCFTA corridor between Nigeria and Kenya — with governance, structure, and measurable trade outcomes.') }}
                    </p>
                    <a href="{{ route('about') }}"
                       class="inline-block bg-brand-500 text-white px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                        Discover More
                    </a>
                </div>

                {{-- Right: 2×2 numbered cards --}}
                <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- Card 01: Mission --}}
                    <div class="rounded bg-white p-8 shadow-[0_0_15px_rgba(0,0,0,0.1)] relative hover:shadow-lg transition-all duration-300">
                        <em class="not-italic absolute top-3 right-4 text-5xl font-bold text-crimson-100 font-serif leading-none select-none">01</em>
                        <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">Our Mission</h4>
                        <p class="text-[15px] leading-[26px] text-zinc-600">To foster structured, sustainable and inclusive bilateral trade between Nigeria and Kenya under the AfCFTA framework.</p>
                    </div>

                    {{-- Card 02: Vision --}}
                    <div class="rounded bg-white p-8 shadow-[0_0_15px_rgba(0,0,0,0.1)] relative hover:shadow-lg transition-all duration-300">
                        <em class="not-italic absolute top-3 right-4 text-5xl font-bold text-crimson-100 font-serif leading-none select-none">02</em>
                        <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">Our Vision</h4>
                        <p class="text-[15px] leading-[26px] text-zinc-600">To become Africa's leading bilateral trade chamber, driving measurable economic growth across the Nigeria-Kenya corridor.</p>
                    </div>

                    {{-- Card 03: Governance --}}
                    <div class="rounded bg-white p-8 shadow-[0_0_15px_rgba(0,0,0,0.1)] relative hover:shadow-lg transition-all duration-300">
                        <em class="not-italic absolute top-3 right-4 text-5xl font-bold text-crimson-100 font-serif leading-none select-none">03</em>
                        <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">Our Governance</h4>
                        <p class="text-[15px] leading-[26px] text-zinc-600">Governed by a Joint Governing Council with representation from both Nigeria and Kenya chapters, overseen by the Global Secretariat.</p>
                    </div>

                    {{-- Card 04: Reach --}}
                    <div class="rounded bg-white p-8 shadow-[0_0_15px_rgba(0,0,0,0.1)] relative hover:shadow-lg transition-all duration-300">
                        <em class="not-italic absolute top-3 right-4 text-5xl font-bold text-crimson-100 font-serif leading-none select-none">04</em>
                        <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">Our Reach</h4>
                        <p class="text-[15px] leading-[26px] text-zinc-600">Operating across 54 AfCFTA signatory nations with active corridors in Agriculture, Manufacturing, Technology, Energy, and Finance.</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- =========================================================
         SECTION G — LEADERSHIP "Our Leadership Team"
         =========================================================
    --}}
    <section class="py-20 bg-zinc-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <span class="block text-xs font-bold uppercase tracking-widest text-crimson-700 text-center mb-3">Our Team</span>
            <h2 class="text-3xl lg:text-4xl font-bold font-serif text-zinc-900 text-center mb-4">Leadership</h2>
            <div class="w-20 h-1.5 bg-brand-500 rounded-full mx-auto mb-14"></div>

            {{-- 4-column grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($leadership as $profile)
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:shadow-lg transition-all duration-300">
                    @if($profile->photoUrl())
                        <img src="{{ $profile->photoUrl() }}"
                             alt="{{ $profile->name }}"
                             class="h-32 w-32 rounded-full object-cover mx-auto mb-4">
                    @else
                        <div class="h-32 w-32 rounded-full bg-gradient-to-br from-brand-600 to-brand-800 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-white font-serif">{{ strtoupper(substr($profile->name, 0, 1)) }}{{ strtoupper(substr(strrchr($profile->name, ' ') ?: ' ', 1, 1)) }}</span>
                        </div>
                    @endif
                    <h4 class="text-lg font-bold font-serif text-zinc-900 mb-1">{{ $profile->name }}</h4>
                    <span class="block text-sm font-medium text-zinc-600 mt-1">{{ $profile->position }}</span>
                    @if($profile->chapter)
                    <span class="mt-2 inline-block text-xs px-2 py-0.5 rounded-full bg-brand-100 text-brand-700">{{ $profile->chapter->name }}</span>
                    @endif
                </div>
                @empty
                {{-- Fallback placeholder cards --}}
                @foreach([['C','Chioma Okafor','President, Nigeria Chapter'],['A','Amina Wanjiru','President, Kenya Chapter'],['E','Emeka Adeyemi','Director-General'],['F','Faith Muthoni','Head of Trade']] as [$initial, $name, $position])
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:shadow-lg transition-all duration-300">
                    <div class="h-32 w-32 rounded-full bg-gradient-to-br from-brand-600 to-brand-800 flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-white font-serif">{{ $initial }}</span>
                    </div>
                    <h4 class="text-lg font-bold font-serif text-zinc-900 mb-1">{{ $name }}</h4>
                    <span class="block text-sm font-medium text-zinc-600 mt-1">{{ $position }}</span>
                </div>
                @endforeach
                @endforelse
            </div>

            {{-- CTA --}}
            <div class="text-center mt-10">
                <a href="{{ route('leadership') }}" class="text-crimson-700 font-semibold hover:underline text-sm">
                    Meet Our Full Team →
                </a>
            </div>

        </div>
    </section>

    {{-- =========================================================
         SECTION H — EVENTS "Events & Missions"
         =========================================================
    --}}
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <span class="block text-xs font-bold uppercase tracking-widest text-crimson-700 text-center mb-3">Events &amp; Missions</span>
            <h2 class="text-3xl lg:text-4xl font-bold font-serif text-zinc-900 text-center mb-4">Upcoming Events</h2>
            <div class="w-20 h-1.5 bg-brand-500 rounded-full mx-auto mb-14"></div>

            {{-- 2-column split: 8/12 table + 4/12 sidebar --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                {{-- Left: Events table (8/12) --}}
                <div class="lg:col-span-8">
                    {{-- Header row --}}
                    <div class="flex items-center border-b-2 border-crimson-700 pb-4 mb-2 text-sm font-bold uppercase tracking-wide text-crimson-700">
                        <span class="flex-1">Event</span>
                        <span class="w-40">Venue</span>
                        <span class="w-32">Date</span>
                        <span class="w-24">Type</span>
                    </div>
                    @forelse($upcomingEvents as $event)
                    <div class="flex items-start border-b border-zinc-200 py-4 gap-4 group hover:bg-zinc-50 transition-colors">
                        <span class="flex-1 font-semibold text-zinc-900 group-hover:text-crimson-700 transition-colors">
                            <a href="{{ route('events.show', $event->id) }}">{{ $event->title }}</a>
                        </span>
                        <span class="w-40 text-zinc-600 text-sm">{{ $event->venue ?? 'TBC' }}</span>
                        <span class="w-32 text-zinc-600 text-sm">{{ $event->starts_at->format('d/m/Y') }}</span>
                        <span class="w-24">
                            <span class="inline-block text-xs px-2 py-0.5 rounded-full
                                {{ in_array(strtolower($event->type ?? ''), ['flagship','summit']) ? 'bg-crimson-100 text-crimson-700' : 'bg-brand-100 text-brand-700' }}">
                                {{ ucfirst($event->type ?? 'Event') }}
                            </span>
                        </span>
                    </div>
                    @empty
                    <div class="py-10 text-center text-zinc-500 text-sm">No upcoming events scheduled. Check back soon.</div>
                    @endforelse
                </div>

                {{-- Right: Sidebar (4/12) --}}
                <div class="lg:col-span-4">
                    <div class="rounded bg-zinc-50 p-8 shadow-[0_0_15px_rgba(0,0,0,0.1)]">
                        <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4 leading-snug">Visit the events page for more information</h4>
                        <p class="text-[15px] leading-[26px] text-zinc-600 mb-8">Get in touch with us for any queries about upcoming trade missions, bilateral summits and NiKCCIMA events.</p>
                        <a href="{{ route('events.index') }}"
                           class="inline-block bg-crimson-700 text-white px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                            Open Events
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- =========================================================
         SECTION I — GEOGRAPHIC COVERAGE
         =========================================================
    --}}
    <section class="bg-crimson-700 py-20">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold font-serif text-white mb-12">Geographic Coverage</h2>
            <div class="grid grid-cols-5 gap-6 max-w-2xl mx-auto mb-8">
                @foreach([
                    ['🇳🇬', 'Nigeria'],
                    ['🇰🇪', 'Kenya'],
                    ['🇿🇦', 'South Africa'],
                    ['🇬🇭', 'Ghana'],
                    ['🇹🇿', 'Tanzania'],
                    ['🇺🇬', 'Uganda'],
                    ['🇷🇼', 'Rwanda'],
                    ['🇪🇹', 'Ethiopia'],
                    ['🇸🇳', 'Senegal'],
                    ['🇨🇮', "Côte d'Ivoire"],
                ] as [$flag, $name])
                <div class="flex flex-col items-center gap-2 cursor-pointer hover:opacity-75 transition-opacity">
                    <span class="text-4xl">{{ $flag }}</span>
                    <span class="text-xs text-white/80 font-medium">{{ $name }}</span>
                </div>
                @endforeach
            </div>
            <h4 class="text-lg font-semibold text-white/90">
                Nigeria, Kenya, and the broader AfCFTA economic community — 54 nations, one market.
            </h4>
        </div>
    </section>

    {{-- =========================================================
         SECTION J — CONTACT INFO STRIP
         Cards float up (-mt-10) from the crimson section above
         =========================================================
    --}}
    <section class="bg-white pb-20">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 -mt-10 relative z-10">

                {{-- Email --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:-translate-y-2 transition-all duration-500">
                    <div class="flex justify-center mb-6">
                        <svg class="h-10 w-10 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold font-serif text-zinc-900 mb-3">Email Address</h4>
                    <a href="mailto:secretariat@nikccima.org"
                       class="text-brand-600 font-medium hover:text-crimson-700 transition-colors text-[15px]">
                        secretariat@nikccima.org
                    </a>
                </div>

                {{-- Phone --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:-translate-y-2 transition-all duration-500">
                    <div class="flex justify-center mb-6">
                        <svg class="h-10 w-10 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold font-serif text-zinc-900 mb-3">Phone Number</h4>
                    <a href="tel:+2349000000000"
                       class="text-brand-600 font-medium hover:text-crimson-700 transition-colors text-[15px]">
                        +234 900 000 0000
                    </a>
                </div>

                {{-- HQ --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:-translate-y-2 transition-all duration-500">
                    <div class="flex justify-center mb-6">
                        <svg class="h-10 w-10 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold font-serif text-zinc-900 mb-3">Headquartered at</h4>
                    <a href="{{ route('contact') }}"
                       class="text-brand-600 font-medium hover:text-crimson-700 transition-colors text-[15px]">
                        Abuja, Nigeria &amp; Nairobi, Kenya
                    </a>
                </div>

            </div>
        </div>
    </section>

</x-layouts::website>
