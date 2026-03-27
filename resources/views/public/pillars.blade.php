{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Pillars Overview"
    banner_image    : Banner background image (PLACEHOLDER UNTIL UPLOADED)
    page_heading    : Page heading (default: "Our Strategic Pillars")
    page_subtitle   : Subtitle
    pillar1_title   : Title for Executive pillar
    pillar1_summary : Summary text
    pillar2_title, pillar2_summary : Trade pillar
    pillar3_title, pillar3_summary : Policy pillar
    pillar4_title, pillar4_summary : Admin pillar
--}}

<x-layouts::website :title="'Our Strategic Pillars — NiKCCIMA'">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden py-24 text-white lg:py-32">
        @if($page?->section('banner_image'))
            <div class="absolute inset-0">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('banner_image')) }}"
                     alt=""
                     class="h-full w-full object-cover"
                     aria-hidden="true">
                <div class="absolute inset-0 bg-zinc-950/70"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-800 to-crimson-950"></div>
        @endif

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <span class="block text-xs font-bold uppercase tracking-widest text-brand-200 mb-3">Structure</span>
            <h1 class="font-serif text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl mb-4">
                {{ $page?->section('page_heading', 'Our Strategic Pillars') }}
            </h1>
            @php $pageSubtitle = $page?->section('page_subtitle', 'NiKCCIMA operates through four structured pillars governing bilateral trade execution and institutional capacity across the Nigeria-Kenya AfCFTA corridor.'); @endphp
            @if($pageSubtitle)
                <p class="max-w-2xl mx-auto text-lg text-zinc-300 sm:text-xl">{{ $pageSubtitle }}</p>
            @endif
        </div>
    </section>

    {{-- ===================== SERVICES GRID ===================== --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">

            <span class="block text-xs font-bold uppercase tracking-widest text-crimson-700 text-center mb-3">Our Pillars</span>
            <h2 class="text-3xl lg:text-4xl font-bold font-serif text-zinc-900 text-center mb-4">Strategic Pillars</h2>
            <div class="w-20 h-1.5 bg-brand-500 rounded-full mx-auto mb-14"></div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-6xl mx-auto">

                {{-- Pillar 1: Executive Governance --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:shadow-lg transition-all duration-500 overflow-hidden">
                    <div class="float-left mr-8 mb-6">
                        {{-- Columns / layers icon --}}
                        <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">{{ $page?->section('pillar1_title', 'Executive Governance') }}</h4>
                    <p class="text-[15px] leading-[26px] text-zinc-600 mb-4">{{ $page?->section('pillar1_summary', 'Governance, policy direction, and bilateral treaty coordination at the highest institutional level of the chamber.') }}</p>
                    <a href="{{ route('pillars.show', 'executive') }}" class="text-sm font-medium text-brand-600 hover:text-crimson-700 transition-colors">Explore Pillar &rarr;</a>
                    <div class="clear-both"></div>
                </div>

                {{-- Pillar 2: Trade & Investment --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:shadow-lg transition-all duration-500 overflow-hidden">
                    <div class="float-left mr-8 mb-6">
                        {{-- Trending up icon --}}
                        <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">{{ $page?->section('pillar2_title', 'Trade & Investment') }}</h4>
                    <p class="text-[15px] leading-[26px] text-zinc-600 mb-4">{{ $page?->section('pillar2_summary', 'Active trade lead matching, anchor investor coordination, and corridor investment activation under AfCFTA protocols.') }}</p>
                    <a href="{{ route('pillars.show', 'trade') }}" class="text-sm font-medium text-brand-600 hover:text-crimson-700 transition-colors">Explore Pillar &rarr;</a>
                    <div class="clear-both"></div>
                </div>

                {{-- Pillar 3: Policy & Advocacy --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:shadow-lg transition-all duration-500 overflow-hidden">
                    <div class="float-left mr-8 mb-6">
                        {{-- Document text icon --}}
                        <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">{{ $page?->section('pillar3_title', 'Policy & Advocacy') }}</h4>
                    <p class="text-[15px] leading-[26px] text-zinc-600 mb-4">{{ $page?->section('pillar3_summary', 'Evidence-based policy advocacy, trade research, regulatory briefs, and technical advisory services for the bilateral corridor.') }}</p>
                    <a href="{{ route('pillars.show', 'policy') }}" class="text-sm font-medium text-brand-600 hover:text-crimson-700 transition-colors">Explore Pillar &rarr;</a>
                    <div class="clear-both"></div>
                </div>

                {{-- Pillar 4: Administration & HR --}}
                <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:shadow-lg transition-all duration-500 overflow-hidden">
                    <div class="float-left mr-8 mb-6">
                        {{-- Cog / settings icon --}}
                        <svg class="h-16 w-16 text-crimson-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold font-serif text-zinc-900 mb-4">{{ $page?->section('pillar4_title', 'Administration & HR') }}</h4>
                    <p class="text-[15px] leading-[26px] text-zinc-600 mb-4">{{ $page?->section('pillar4_summary', 'Membership management, secretariat operations, compliance monitoring, and cross-chamber administrative coordination.') }}</p>
                    <a href="{{ route('pillars.show', 'admin') }}" class="text-sm font-medium text-brand-600 hover:text-crimson-700 transition-colors">Explore Pillar &rarr;</a>
                    <div class="clear-both"></div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== CTA STRIP ===================== --}}
    <section class="bg-crimson-700 py-20">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="font-serif text-3xl font-bold text-white mb-4">Explore Our Trade &amp; Investment Data</h2>
            <div class="w-20 h-1.5 bg-white/50 rounded-full mx-auto mb-10"></div>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('trade') }}"
                   class="inline-block bg-white text-crimson-700 px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                    Trade &amp; Investment
                </a>
                <a href="{{ route('policy') }}"
                   class="inline-block border border-white/60 text-white px-8 py-3 rounded text-sm font-medium hover:bg-white/10 transition-all">
                    Policy Research
                </a>
            </div>
        </div>
    </section>

</x-layouts::website>
