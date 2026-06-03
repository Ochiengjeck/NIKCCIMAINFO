{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "About"
    banner_image         : Page banner background (upload via Media Library — PLACEHOLDER UNTIL UPLOADED)
    hero_title           : Page heading (default: "About NiKCCIMA")
    hero_subtitle        : Subheading
    mission_heading      : Mission card title
    mission_body         : Mission paragraph
    vision_heading       : Vision card title
    vision_body          : Vision paragraph
    about_body           : Full institutional story (HTML)
--}}

<x-layouts::website
    :title="$page?->meta_title ?: 'About NiKCCIMA'"
    :meta-description="$page?->meta_description ?: $page?->section('hero_subtitle', 'About the Nigeria-Kenya Chamber of Commerce, Industry, Mines & Agriculture — our mission, vision, and role in AfCFTA corridor trade.')">

    {{-- ===================== HERO BANNER ===================== --}}
    <section class="relative overflow-hidden py-28 text-white">
        @if($page?->section('banner_image'))
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('banner_image')) }}')">
                <div class="absolute inset-0 bg-zinc-900/80"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-800 to-crimson-950"></div>
        @endif

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span class="mb-4 inline-flex rounded-full bg-crimson-700 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">Institution</span>
            <h1 class="font-serif text-4xl font-bold lg:text-6xl">
                {{ $page?->section('hero_title', 'About NiKCCIMA') }}
            </h1>
            @php $heroSubtitle = $page?->section('hero_subtitle', 'The premier bilateral trade chamber for Nigeria-Kenya corridor execution under the AfCFTA.'); @endphp
            @if($heroSubtitle)
                <p class="mt-4 max-w-2xl text-zinc-300 text-lg">{{ $heroSubtitle }}</p>
            @endif
        </div>
    </section>

    {{-- ===================== MAIN ABOUT SECTION ===================== --}}
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section label --}}
            <div class="mb-14 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-crimson-700">About Us</span>
                <div class="mt-3 w-12 h-0.5 bg-crimson-700 rounded-full mx-auto"></div>
            </div>

            {{-- 2-column grid: 2/5 left + 3/5 right --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-16 items-start">

                {{-- Left: Who We Are --}}
                <div class="lg:col-span-2">
                    <h4 class="text-2xl font-bold font-serif text-zinc-900 mb-6 leading-snug">Who We Are</h4>
                    <p class="text-[15px] leading-[26px] text-zinc-600 mb-6">
                        {!! $page?->section('about_body', 'The Nigeria-Kenya Chamber of Commerce, Industry, Mines &amp; Agriculture (NiKCCIMA) is the premier bilateral trade institution facilitating structured, measurable corridor execution between Nigeria and Kenya under the African Continental Free Trade Area (AfCFTA) framework.') !!}
                    </p>
                    <a href="{{ route('membership.apply') }}"
                       class="inline-block bg-brand-500 text-white px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                        Become a Member
                    </a>
                </div>

                {{-- Right: 2×2 numbered card grid --}}
                <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- Card 01: Mission --}}
                    <div class="rounded bg-white p-8 shadow-[0_0_15px_rgba(0,0,0,0.1)] relative hover:shadow-lg transition-all duration-300">
                        <em class="not-italic absolute top-3 right-4 text-5xl font-bold text-crimson-100 font-serif leading-none select-none">01</em>
                        <h5 class="text-base font-bold text-zinc-900 mb-3 relative">
                            {{ $page?->section('mission_heading', 'Our Mission') }}
                        </h5>
                        <p class="text-[14px] leading-[24px] text-zinc-600 relative">
                            {{ $page?->section('mission_body', 'To foster structured, measurable bilateral trade between Nigeria and Kenya through AfCFTA-aligned corridor execution, institutional capacity building, and active private-sector engagement.') }}
                        </p>
                    </div>

                    {{-- Card 02: Vision --}}
                    <div class="rounded bg-white p-8 shadow-[0_0_15px_rgba(0,0,0,0.1)] relative hover:shadow-lg transition-all duration-300">
                        <em class="not-italic absolute top-3 right-4 text-5xl font-bold text-crimson-100 font-serif leading-none select-none">02</em>
                        <h5 class="text-base font-bold text-zinc-900 mb-3 relative">
                            {{ $page?->section('vision_heading', 'Our Vision') }}
                        </h5>
                        <p class="text-[14px] leading-[24px] text-zinc-600 relative">
                            {{ $page?->section('vision_body', 'To become Africa\'s leading bilateral trade chamber, driving measurable AfCFTA corridor outcomes and sustainable economic integration across Africa\'s two largest economies.') }}
                        </p>
                    </div>

                    {{-- Card 03: Governance --}}
                    <div class="rounded bg-white p-8 shadow-[0_0_15px_rgba(0,0,0,0.1)] relative hover:shadow-lg transition-all duration-300">
                        <em class="not-italic absolute top-3 right-4 text-5xl font-bold text-crimson-100 font-serif leading-none select-none">03</em>
                        <h5 class="text-base font-bold text-zinc-900 mb-3 relative">Our Governance</h5>
                        <p class="text-[14px] leading-[24px] text-zinc-600 relative">
                            Governed by a Joint Governing Council with dual Nigeria and Kenya representation, overseen by the Global Secretariat.
                        </p>
                    </div>

                    {{-- Card 04: Reach --}}
                    <div class="rounded bg-white p-8 shadow-[0_0_15px_rgba(0,0,0,0.1)] relative hover:shadow-lg transition-all duration-300">
                        <em class="not-italic absolute top-3 right-4 text-5xl font-bold text-crimson-100 font-serif leading-none select-none">04</em>
                        <h5 class="text-base font-bold text-zinc-900 mb-3 relative">Our Reach</h5>
                        <p class="text-[14px] leading-[24px] text-zinc-600 relative">
                            Operating across the AfCFTA 54-nation bloc with active trade corridors in agriculture, manufacturing, technology, energy and finance.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </section>

    {{-- ===================== CTA STRIP ===================== --}}
    <section class="bg-crimson-700 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-serif text-3xl font-bold text-white lg:text-4xl">Meet Our Leadership Team</h2>
            <div class="w-20 h-1.5 bg-white/50 rounded-full mx-auto my-6"></div>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('leadership') }}"
                   class="inline-block bg-white text-crimson-700 px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                    View Leadership
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-block border border-white/60 text-white px-8 py-3 rounded text-sm font-medium hover:bg-white/10 transition-all">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

</x-layouts::website>
