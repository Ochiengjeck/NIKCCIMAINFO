{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Contact Us"
    banner_image    : Banner background image (upload via Media Library — PLACEHOLDER UNTIL UPLOADED)
    hero_title      : Page heading (default: "Contact Us")
    hero_subtitle   : Hero subheading

    CONTACT DETAILS are GLOBAL — Admin → Settings → Contact Details
    (nigeria_address/phone/email, kenya_address/phone/email, map_embed_url)
--}}

@php
    $nigeriaAddress = \App\Models\SystemSetting::get('nigeria_address', 'Abuja, Federal Capital Territory, Federal Republic of Nigeria');
    $nigeriaPhone   = \App\Models\SystemSetting::get('nigeria_phone', '');
    $nigeriaEmail   = \App\Models\SystemSetting::get('nigeria_email', 'nigeria@nikccima.org');
    $kenyaAddress   = \App\Models\SystemSetting::get('kenya_address', 'Nairobi, Republic of Kenya');
    $kenyaPhone     = \App\Models\SystemSetting::get('kenya_phone', '');
    $kenyaEmail     = \App\Models\SystemSetting::get('kenya_email', 'kenya@nikccima.org');
    $mapEmbedUrl    = \App\Models\SystemSetting::get('map_embed_url', '');
@endphp

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

    {{-- ===================== CHAPTER CONTACT CARDS ===================== --}}
    <div class="mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl px-4 -mt-12 relative z-10 pb-12">

        @php
            $chapters = [
                ['flag' => '🇳🇬', 'name' => 'Nigeria Chapter', 'address' => $nigeriaAddress, 'phone' => $nigeriaPhone, 'email' => $nigeriaEmail, 'accent' => 'text-brand-600', 'icon' => 'text-brand-500'],
                ['flag' => '🇰🇪', 'name' => 'Kenya Chapter',   'address' => $kenyaAddress,   'phone' => $kenyaPhone,   'email' => $kenyaEmail,   'accent' => 'text-crimson-700', 'icon' => 'text-crimson-600'],
            ];
        @endphp

        @foreach($chapters as $c)
            <div class="rounded bg-white p-10 shadow-[0_0_15px_rgba(0,0,0,0.1)] hover:-translate-y-2 transition-all duration-500">
                <h4 class="mb-6 flex items-center gap-2 text-lg font-bold text-zinc-900">
                    <span class="text-xl leading-none">{{ $c['flag'] }}</span> {{ $c['name'] }}
                </h4>
                <ul class="space-y-4 text-sm">
                    {{-- Address --}}
                    @if($c['address'])
                        <li class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-zinc-600">{{ $c['address'] }}</span>
                        </li>
                    @endif
                    {{-- Phone --}}
                    @if($c['phone'])
                        <li class="flex items-center gap-3">
                            <svg class="h-5 w-5 shrink-0 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $c['phone']) }}" class="{{ $c['accent'] }} hover:underline">{{ $c['phone'] }}</a>
                        </li>
                    @endif
                    {{-- Email --}}
                    @if($c['email'])
                        <li class="flex items-center gap-3">
                            <svg class="h-5 w-5 shrink-0 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:{{ $c['email'] }}" class="{{ $c['accent'] }} hover:underline break-all">{{ $c['email'] }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        @endforeach

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

    {{-- ===================== MAP ===================== --}}
    @if($mapEmbedUrl)
        <section class="bg-white">
            <div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-xl border border-zinc-200 shadow-sm">
                    <iframe src="{{ $mapEmbedUrl }}"
                            width="100%" height="420" style="border:0;"
                            allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="NiKCCIMA location map"></iframe>
                </div>
            </div>
        </section>
    @endif

</x-layouts::website>
