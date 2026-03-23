{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "About"
    banner_image         : Page banner background (upload via Media Library — PLACEHOLDER UNTIL UPLOADED)
    hero_title           : Page heading (default: "About NiKCCIMA")
    hero_subtitle        : Subheading
    mission_heading      : Mission section title
    mission_body         : Mission paragraph
    vision_heading       : Vision section title
    vision_body          : Vision paragraph
    governance_structure : Governance overview text/HTML
    about_body           : Full institutional story (HTML)
--}}

<x-layouts::website :title="($page?->section('hero_title', 'About NiKCCIMA')).' — NiKCCIMA'">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden py-24 text-white">
        {{-- Background --}}
        @if($page?->section('banner_image'))
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('banner_image')) }}')">
                <div class="absolute inset-0 bg-[#922529]/85"></div>
            </div>
        @else
            {{-- PLACEHOLDER — upload banner_image via Admin → CMS → Pages → About --}}
            <div class="absolute inset-0 bg-gradient-to-br from-brand-900 to-[#7a1e22]">
                <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="dp" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1.5" fill="white"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dp)"/>
                </svg>
            </div>
        @endif

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span class="mb-4 inline-flex rounded-full bg-[#922529] px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">Institution</span>
            <h1 class="font-serif text-4xl font-bold lg:text-6xl">
                {{ $page?->section('hero_title', 'About NiKCCIMA') }}
            </h1>
            @php $heroSubtitle = $page?->section('hero_subtitle', 'The premier bilateral trade chamber for Nigeria-Kenya corridor execution under the AfCFTA.'); @endphp
            @if($heroSubtitle)
                <p class="mt-4 max-w-2xl text-[#922529] text-lg">{{ $heroSubtitle }}</p>
            @endif
        </div>
    </section>

    {{-- ===================== MISSION & VISION ===================== --}}
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mb-14 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-[#922529]">Purpose</span>
                <h2 class="mt-2 font-serif text-3xl font-bold text-zinc-900 lg:text-4xl">What Drives Us</h2>
            </div>

            <div class="grid gap-8 lg:grid-cols-2">

                {{-- Mission Card --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#A8DCAB]/20">
                        {{-- Target / crosshairs icon --}}
                        <svg class="h-7 w-7 text-[#922529]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke-width="1.5"/>
                            <circle cx="12" cy="12" r="4" stroke-width="1.5"/>
                            <path stroke-linecap="round" stroke-width="1.5" d="M12 3v2M12 19v2M3 12h2M19 12h2"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-2xl font-bold text-zinc-900 mb-4">
                        {{ $page?->section('mission_heading', 'Our Mission') }}
                    </h3>
                    <p class="text-zinc-600 leading-relaxed">
                        {{ $page?->section('mission_body', 'To facilitate structured, measurable bilateral trade between Nigeria and Kenya through AfCFTA-aligned corridor execution, institutional capacity building, and active private-sector engagement.') }}
                    </p>
                </div>

                {{-- Vision Card --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#A8DCAB]/20">
                        {{-- Globe icon --}}
                        <svg class="h-7 w-7 text-[#922529]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke-width="1.5"/>
                            <path stroke-linecap="round" stroke-width="1.5" d="M3.6 9h16.8M3.6 15h16.8M12 3a14.5 14.5 0 010 18M12 3a14.5 14.5 0 000 18"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-2xl font-bold text-zinc-900 mb-4">
                        {{ $page?->section('vision_heading', 'Our Vision') }}
                    </h3>
                    <p class="text-zinc-600 leading-relaxed">
                        {{ $page?->section('vision_body', 'To become the premier bilateral trade chamber driving measurable AfCFTA corridor outcomes and sustainable economic integration across Africa\'s two largest economies.') }}
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== INSTITUTIONAL STORY ===================== --}}
    {{-- CMS: about → about_body (HTML content), story_image (800×600 optional side image) --}}
    @if($page?->section('about_body'))
        <section class="py-20 bg-zinc-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @if($page?->section('story_image'))
                    {{-- Split layout when story_image is uploaded --}}
                    <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-widest text-[#922529]">Our Story</span>
                            <h2 class="mt-2 mb-8 font-serif text-3xl font-bold text-zinc-900 lg:text-4xl">
                                The NiKCCIMA Story
                            </h2>
                            <div class="prose prose-zinc prose-p:leading-relaxed prose-p:text-zinc-600 max-w-none">
                                {!! $page->section('about_body') !!}
                            </div>
                        </div>
                        <div class="relative">
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('story_image')) }}"
                                alt="NiKCCIMA Story"
                                class="w-full rounded-2xl shadow-2xl object-cover"
                            />
                            {{-- Decorative accent --}}
                            <div class="absolute -bottom-4 -left-4 -z-10 h-full w-full rounded-2xl border-2 border-[#A8DCAB]/30"></div>
                        </div>
                    </div>
                @else
                    {{-- Centered layout when no image --}}
                    <div class="mx-auto max-w-3xl">
                        <span class="text-xs font-semibold uppercase tracking-widest text-[#922529]">Our Story</span>
                        <h2 class="mt-2 mb-8 font-serif text-3xl font-bold text-zinc-900 lg:text-4xl">
                            The NiKCCIMA Story
                        </h2>
                        <div class="prose prose-zinc prose-p:leading-relaxed prose-p:text-zinc-600 max-w-none">
                            {!! $page->section('about_body') !!}
                        </div>
                        <p class="mt-6 text-xs text-zinc-400">
                            Add a story image via Admin → CMS → Pages → About → Story Image (recommended: 800×600)
                        </p>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- ===================== GOVERNANCE MODEL ===================== --}}
    {{-- CMS: about → governance_structure (text description of governance) --}}
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="mb-14 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-[#922529]">Structure</span>
                <h2 class="mt-2 font-serif text-3xl font-bold text-zinc-900 lg:text-4xl">Governance Model</h2>
                @if($page?->section('governance_structure'))
                    <p class="mt-4 mx-auto max-w-2xl text-zinc-500 text-base leading-relaxed">
                        {{ $page->section('governance_structure') }}
                    </p>
                @endif
            </div>

            {{-- Governance hierarchy diagram --}}
            <div class="space-y-4 max-w-4xl mx-auto">

                {{-- Tier 1: Global Governing Council --}}
                <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-5 text-center relative">
                    <div class="flex flex-col items-center gap-3 sm:flex-row sm:justify-center sm:gap-5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#922529] text-white">
                            {{-- Crown / governing icon --}}
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#922529] mb-0.5">Tier 1</p>
                            <h3 class="font-semibold text-zinc-900 text-base">Global Governing Council</h3>
                            <p class="text-xs text-zinc-500 mt-0.5">Supreme governing body — policy direction and oversight</p>
                        </div>
                    </div>
                </div>

                {{-- Connector --}}
                <div class="flex justify-center">
                    <div class="h-5 w-px bg-zinc-300"></div>
                </div>

                {{-- Tier 2: Secretariat --}}
                <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-5 text-center max-w-sm mx-auto w-full">
                    <div class="flex flex-col items-center gap-3 sm:flex-row sm:justify-center sm:gap-5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#922529] text-white">
                            {{-- Office building icon --}}
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#922529] mb-0.5">Tier 2</p>
                            <h3 class="font-semibold text-zinc-900 text-base">Global Secretariat</h3>
                            <p class="text-xs text-zinc-500 mt-0.5">Operational coordination and programme delivery</p>
                        </div>
                    </div>
                </div>

                {{-- Connector split --}}
                <div class="flex justify-center">
                    <div class="h-5 w-px bg-zinc-300"></div>
                </div>

                {{-- Tier 3: Nigeria + Kenya Chapters --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-5">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xl leading-none" aria-hidden="true">&#127475;&#127468;</span>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-[#922529]">Tier 3</p>
                                <h3 class="font-semibold text-zinc-900 text-sm">Nigeria Chapter</h3>
                            </div>
                        </div>
                        <p class="text-xs text-zinc-500">Abuja-based operations, member engagement &amp; corridor delivery</p>
                    </div>
                    <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-5">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xl leading-none" aria-hidden="true">&#127472;&#127466;</span>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-[#922529]">Tier 3</p>
                                <h3 class="font-semibold text-zinc-900 text-sm">Kenya Chapter</h3>
                            </div>
                        </div>
                        <p class="text-xs text-zinc-500">Nairobi-based operations, member engagement &amp; corridor delivery</p>
                    </div>
                </div>

                {{-- Connector --}}
                <div class="flex justify-center">
                    <div class="h-5 w-px bg-zinc-300"></div>
                </div>

                {{-- Tier 4: Joint Technical Platforms --}}
                <div class="rounded-xl border border-dashed border-zinc-300 bg-zinc-50/50 p-5 text-center max-w-sm mx-auto w-full">
                    <div class="flex flex-col items-center gap-3 sm:flex-row sm:justify-center sm:gap-5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-zinc-200 text-zinc-600">
                            {{-- Link / chain icon --}}
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500 mb-0.5">Tier 4</p>
                            <h3 class="font-semibold text-zinc-900 text-base">Joint Technical Platforms</h3>
                            <p class="text-xs text-zinc-500 mt-0.5">Cross-chapter working groups and AfCFTA implementation bodies</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== CTA STRIP ===================== --}}
    <section class="bg-[#922529] py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-6 text-center sm:flex-row sm:justify-between sm:text-left">
                <div>
                    <h2 class="font-serif text-2xl font-bold text-white lg:text-3xl">Meet Our Leadership Team</h2>
                    <p class="mt-2 text-[#922529] text-sm max-w-lg">
                        Discover the Council members and Secretariat executives driving the NiKCCIMA mission.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3 justify-center sm:justify-end shrink-0">
                    <a href="{{ route('leadership') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-[#922529] shadow-sm hover:bg-[#A8DCAB]/20 transition-colors duration-200">
                        View Leadership
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-white/40 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10 transition-colors duration-200">
                        Get in Touch
                    </a>
                </div>
            </div>
        </div>
    </section>

</x-layouts::website>
