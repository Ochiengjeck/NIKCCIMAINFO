{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Pillars Overview"
    banner_image    : Banner background image (PLACEHOLDER UNTIL UPLOADED)
    page_heading    : Page heading (default: "Our Four Pillars")
    page_subtitle   : Subtitle
    pillar1_title   : Title for Executive pillar
    pillar1_icon    : Icon (unused in template - uses hardcoded SVG)
    pillar1_summary : Summary text
    pillar2_title, pillar2_summary : Trade pillar
    pillar3_title, pillar3_summary : Policy pillar
    pillar4_title, pillar4_summary : Admin pillar
--}}

<x-layouts::website :title="($page?->section('page_heading', 'Our Four Pillars')).' — NiKCCIMA'">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden py-24 text-white">
        {{-- Background --}}
        @if($page?->section('banner_image'))
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('banner_image')) }}')">
                <div class="absolute inset-0 bg-green-900/85"></div>
            </div>
        @else
            {{-- PLACEHOLDER — upload banner_image via Admin → CMS → Pages → Pillars Overview --}}
            <div class="absolute inset-0 bg-gradient-to-br from-green-950 via-green-900 to-green-800">
                {{-- Diagonal grid mesh --}}
                <svg class="absolute inset-0 h-full w-full opacity-[0.06]" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="dp-pillars-mesh" x="0" y="0" width="48" height="48" patternUnits="userSpaceOnUse">
                            <path d="M0 24h48M24 0v48" stroke="white" stroke-width="1" fill="none"/>
                            <circle cx="24" cy="24" r="2" fill="white"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dp-pillars-mesh)"/>
                </svg>
            </div>
            {{-- Decorative blobs --}}
            <div class="pointer-events-none absolute -right-32 -top-16 h-96 w-96 rounded-full bg-white/5 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-16 h-80 w-80 rounded-full bg-green-600/10 blur-3xl"></div>
        @endif

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span class="mb-4 inline-flex rounded-full bg-red-600 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">Structure</span>
            <h1 class="font-serif text-4xl font-bold lg:text-6xl">
                {{ $page?->section('page_heading', 'Our Four Pillars') }}
            </h1>
            @php $pageSubtitle = $page?->section('page_subtitle', 'NiKCCIMA operates through four structured pillars governing bilateral trade execution and institutional capacity across the Nigeria-Kenya AfCFTA corridor.'); @endphp
            @if($pageSubtitle)
                <p class="mt-4 max-w-2xl text-green-200 text-lg">{{ $pageSubtitle }}</p>
            @endif
        </div>
    </section>

    {{-- ===================== FOUR PILLARS GRID ===================== --}}
    <section class="py-20 bg-zinc-50 dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mb-14 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-green-600 dark:text-green-400">Governance</span>
                <h2 class="mt-2 font-serif text-3xl font-bold text-zinc-900 dark:text-white lg:text-4xl">How We Operate</h2>
                <p class="mt-3 mx-auto max-w-2xl text-zinc-500 dark:text-zinc-400">
                    Each pillar represents a distinct functional domain — together they constitute the full institutional apparatus of NiKCCIMA.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">

                {{-- Pillar 1: Executive --}}
                <a href="{{ route('pillars.show', 'executive') }}"
                   class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-900 to-green-800 p-8 text-white hover:-translate-y-1 hover:shadow-2xl transition-all duration-300">
                    <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-white/15 group-hover:bg-white/25 transition-colors duration-200">
                        {{-- Building / office icon --}}
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                        </svg>
                    </div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-green-300">Pillar 1</p>
                    <h3 class="font-serif text-xl font-bold text-white mb-3 leading-snug">
                        {{ $page?->section('pillar1_title', 'Executive & Institutional Leadership') }}
                    </h3>
                    <p class="text-sm text-green-200 leading-relaxed mb-6 line-clamp-4">
                        {{ $page?->section('pillar1_summary', 'Governance, policy direction, and bilateral treaty coordination at the highest institutional level.') }}
                    </p>
                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-300 group-hover:text-white transition-colors duration-200">
                        Explore Pillar
                        <svg class="h-4 w-4 group-hover:translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                    {{-- Decorative arc --}}
                    <div class="pointer-events-none absolute -bottom-6 -right-6 h-24 w-24 rounded-full bg-white/5"></div>
                </a>

                {{-- Pillar 2: Trade --}}
                <a href="{{ route('pillars.show', 'trade') }}"
                   class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-800 to-green-700 p-8 text-white hover:-translate-y-1 hover:shadow-2xl transition-all duration-300">
                    <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-white/15 group-hover:bg-white/25 transition-colors duration-200">
                        {{-- Trending up icon --}}
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-emerald-300">Pillar 2</p>
                    <h3 class="font-serif text-xl font-bold text-white mb-3 leading-snug">
                        {{ $page?->section('pillar2_title', 'Trade & Investment Facilitation') }}
                    </h3>
                    <p class="text-sm text-emerald-100 leading-relaxed mb-6 line-clamp-4">
                        {{ $page?->section('pillar2_summary', 'Active trade lead matching, anchor investor coordination, and corridor investment activation under AfCFTA protocols.') }}
                    </p>
                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-300 group-hover:text-white transition-colors duration-200">
                        Explore Pillar
                        <svg class="h-4 w-4 group-hover:translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                    <div class="pointer-events-none absolute -bottom-6 -right-6 h-24 w-24 rounded-full bg-white/5"></div>
                </a>

                {{-- Pillar 3: Policy --}}
                <a href="{{ route('pillars.show', 'policy') }}"
                   class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-800 to-teal-700 p-8 text-white hover:-translate-y-1 hover:shadow-2xl transition-all duration-300">
                    <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-white/15 group-hover:bg-white/25 transition-colors duration-200">
                        {{-- Document text icon --}}
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-teal-300">Pillar 3</p>
                    <h3 class="font-serif text-xl font-bold text-white mb-3 leading-snug">
                        {{ $page?->section('pillar3_title', 'Policy & Research') }}
                    </h3>
                    <p class="text-sm text-teal-100 leading-relaxed mb-6 line-clamp-4">
                        {{ $page?->section('pillar3_summary', 'Evidence-based policy advocacy, trade research, regulatory briefs, and technical advisory services for the bilateral corridor.') }}
                    </p>
                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-teal-300 group-hover:text-white transition-colors duration-200">
                        Explore Pillar
                        <svg class="h-4 w-4 group-hover:translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                    <div class="pointer-events-none absolute -bottom-6 -right-6 h-24 w-24 rounded-full bg-white/5"></div>
                </a>

                {{-- Pillar 4: Admin --}}
                <a href="{{ route('pillars.show', 'admin') }}"
                   class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-teal-800 to-green-900 p-8 text-white hover:-translate-y-1 hover:shadow-2xl transition-all duration-300">
                    <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-white/15 group-hover:bg-white/25 transition-colors duration-200">
                        {{-- Cog icon --}}
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-teal-300">Pillar 4</p>
                    <h3 class="font-serif text-xl font-bold text-white mb-3 leading-snug">
                        {{ $page?->section('pillar4_title', 'Administration & Member Services') }}
                    </h3>
                    <p class="text-sm text-teal-100 leading-relaxed mb-6 line-clamp-4">
                        {{ $page?->section('pillar4_summary', 'Membership management, secretariat operations, compliance monitoring, and cross-chamber administrative coordination.') }}
                    </p>
                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-teal-300 group-hover:text-white transition-colors duration-200">
                        Explore Pillar
                        <svg class="h-4 w-4 group-hover:translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                    <div class="pointer-events-none absolute -bottom-6 -right-6 h-24 w-24 rounded-full bg-white/5"></div>
                </a>

            </div>
        </div>
    </section>

    {{-- ===================== CTA STRIP ===================== --}}
    <section class="bg-zinc-900 dark:bg-zinc-950 py-16 border-t border-zinc-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-serif text-2xl font-bold text-white lg:text-3xl mb-3">
                Explore Trade &amp; Investment Opportunities
            </h2>
            <p class="text-zinc-400 max-w-xl mx-auto mb-8 text-sm leading-relaxed">
                Access live trade leads, anchor investor profiles, and export readiness resources on the NiKCCIMA corridor portal.
            </p>
            <a href="{{ route('trade') }}"
               class="inline-flex items-center gap-2 rounded-full bg-green-600 px-8 py-3.5 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-colors duration-200">
                Go to Trade Portal
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </section>

</x-layouts::website>
