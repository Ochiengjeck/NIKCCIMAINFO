{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Contact Us"
    banner_image    : Banner background image (upload via Media Library — PLACEHOLDER UNTIL UPLOADED)
    hero_title      : Page heading (default: "Contact Us")
    hero_subtitle   : Hero subheading
    nigeria_email   : Nigeria office email address
    nigeria_phone   : Nigeria phone number
    kenya_email     : Kenya office email address
    kenya_phone     : Kenya phone number
--}}

<x-layouts::website :title="'Contact NiKCCIMA'">

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
            <span class="mb-4 inline-flex rounded-full bg-crimson-700 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">Reach Out</span>
            <h1 class="font-serif text-4xl font-bold lg:text-6xl">
                {{ $page?->section('hero_title', 'Contact Us') }}
            </h1>
            @php $heroSubtitle = $page?->section('hero_subtitle', 'Connect with our Nigeria or Kenya chapter offices for trade inquiries, membership, and partnership opportunities.'); @endphp
            @if($heroSubtitle)
                <p class="mt-4 max-w-2xl text-zinc-300 text-lg">{{ $heroSubtitle }}</p>
            @endif
        </div>
    </section>

    {{-- ===================== FLOATING INFO CARDS ===================== --}}
    <div class="mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-5xl px-4 -mt-12 relative z-10 pb-12">

        {{-- Card 1: Email --}}
        <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:-translate-y-2 transition-all duration-500">
            <div class="flex justify-center mb-5">
                <svg class="h-10 w-10 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h4 class="text-base font-bold text-zinc-900 mb-3">Email Address</h4>
            <a href="mailto:{{ $page?->section('nigeria_email', 'nigeria@nikccima.org') }}"
               class="text-sm text-brand-600 hover:underline break-all">
                {{ $page?->section('nigeria_email', 'nigeria@nikccima.org') }}
            </a>
        </div>

        {{-- Card 2: Phone --}}
        <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:-translate-y-2 transition-all duration-500">
            <div class="flex justify-center mb-5">
                <svg class="h-10 w-10 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <h4 class="text-base font-bold text-zinc-900 mb-3">Phone Number</h4>
            <a href="tel:{{ $page?->section('nigeria_phone', '+234 900 000 0000') }}"
               class="text-sm text-brand-600 hover:underline">
                {{ $page?->section('nigeria_phone', '+234 900 000 0000') }}
            </a>
        </div>

        {{-- Card 3: Location --}}
        <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] text-center hover:-translate-y-2 transition-all duration-500">
            <div class="flex justify-center mb-5">
                <svg class="h-10 w-10 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h4 class="text-base font-bold text-zinc-900 mb-3">Headquartered at</h4>
            <p class="text-sm text-zinc-600">Abuja, Nigeria &amp; Nairobi, Kenya</p>
        </div>

    </div>

    {{-- ===================== CONTACT FORM SECTION ===================== --}}
    <section class="py-20 bg-zinc-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <div class="mb-12 text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-crimson-700">Get In Touch</span>
                <h2 class="mt-3 font-serif text-3xl font-bold text-zinc-900 lg:text-4xl">Send Us a Message</h2>
                <div class="mt-4 w-12 h-0.5 bg-crimson-700 rounded-full mx-auto"></div>
            </div>

            {{-- Form --}}
            <div class="max-w-3xl mx-auto">
                <livewire:public.contact-form />
            </div>

        </div>
    </section>

</x-layouts::website>
