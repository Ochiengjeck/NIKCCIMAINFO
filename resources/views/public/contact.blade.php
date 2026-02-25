<x-layouts::website :title="'Contact Us — NiKCCIMA'">

    {{--
        CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Contact Us"
        banner_image    : Banner background image (upload via Media Library — PLACEHOLDER UNTIL UPLOADED)
        hero_title      : Page heading (default: "Get in Touch")
        nigeria_address : Nigeria office address
        nigeria_phone   : Nigeria phone number
        nigeria_email   : Nigeria email address
        kenya_address   : Kenya office address
        kenya_phone     : Kenya phone number
        kenya_email     : Kenya email address
        map_embed_url   : Google Maps embed URL (set via Admin → CMS → Pages → Contact → map_embed_url — PLACEHOLDER UNTIL SET)
    --}}

    {{-- Hero --}}
    <section class="relative overflow-hidden py-24 text-white">
        @if($page?->section('banner_image'))
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                 style="background-image: url('{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('banner_image')) }}')">
                <div class="absolute inset-0 bg-green-900/85"></div>
            </div>
        @else
            {{-- PLACEHOLDER — upload banner_image via Admin → CMS → Pages → Contact --}}
            <div class="absolute inset-0 bg-gradient-to-br from-green-900 to-green-800">
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
            <span class="mb-4 inline-flex rounded-full bg-red-600 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">Reach Out</span>
            <h1 class="font-serif text-4xl font-bold lg:text-6xl">
                {{ $page?->section('hero_title', 'Get in Touch') }}
            </h1>
            <p class="mt-4 max-w-xl text-lg text-green-200">
                Connect with our Nigeria or Kenya chapter offices for trade inquiries, membership, and partnership opportunities.
            </p>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="bg-white py-20 dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-16 lg:grid-cols-2">

                {{-- Contact Form --}}
                <div>
                    <span class="mb-2 inline-block text-xs font-semibold uppercase tracking-widest text-green-600">Inquiry</span>
                    <h2 class="mb-8 font-serif text-3xl font-bold text-zinc-900 dark:text-white">Send an Inquiry</h2>
                    <livewire:public.contact-form />
                </div>

                {{-- Office Details --}}
                <div class="space-y-6">
                    <div>
                        <span class="mb-2 inline-block text-xs font-semibold uppercase tracking-widest text-green-600">Our Offices</span>
                        <h2 class="font-serif text-2xl font-bold text-zinc-900 dark:text-white">Chapter Offices</h2>
                    </div>

                    {{-- Nigeria Office Card --}}
                    <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6 dark:border-zinc-700 dark:bg-zinc-900 border-l-4 border-l-green-600">
                        <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-green-800 dark:text-green-400">
                            🇳🇬 Nigeria Chapter
                        </h3>
                        <ul class="space-y-3 text-sm text-zinc-600 dark:text-zinc-400">
                            <li class="flex items-start gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $page?->section('nigeria_address', 'Abuja, Federal Capital Territory, Nigeria') }}
                            </li>
                            @if($page?->section('nigeria_phone'))
                                <li class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $page->section('nigeria_phone') }}
                                </li>
                            @endif
                            <li class="flex items-center gap-2.5">
                                <svg class="h-4 w-4 shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="mailto:{{ $page?->section('nigeria_email', 'nigeria@nikccima.org') }}"
                                   class="text-green-700 hover:underline dark:text-green-400">
                                    {{ $page?->section('nigeria_email', 'nigeria@nikccima.org') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Kenya Office Card --}}
                    <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6 dark:border-zinc-700 dark:bg-zinc-900 border-l-4 border-l-red-600">
                        <h3 class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-red-800 dark:text-red-400">
                            🇰🇪 Kenya Chapter
                        </h3>
                        <ul class="space-y-3 text-sm text-zinc-600 dark:text-zinc-400">
                            <li class="flex items-start gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $page?->section('kenya_address', 'Nairobi, Republic of Kenya') }}
                            </li>
                            @if($page?->section('kenya_phone'))
                                <li class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $page->section('kenya_phone') }}
                                </li>
                            @endif
                            <li class="flex items-center gap-2.5">
                                <svg class="h-4 w-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="mailto:{{ $page?->section('kenya_email', 'kenya@nikccima.org') }}"
                                   class="text-red-700 hover:underline dark:text-red-400">
                                    {{ $page?->section('kenya_email', 'kenya@nikccima.org') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Map --}}
                    @if($page?->section('map_embed_url'))
                        <div class="overflow-hidden rounded-2xl border border-zinc-200 dark:border-zinc-700">
                            <iframe
                                src="{{ $page->section('map_embed_url') }}"
                                width="100%" height="240"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="NiKCCIMA Office Location">
                            </iframe>
                        </div>
                    @else
                        {{-- MAP PLACEHOLDER — set map_embed_url via Admin → CMS → Pages → Contact → map_embed_url --}}
                        <div class="flex h-48 flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800">
                            <svg class="mb-2 h-8 w-8 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            <p class="text-sm font-medium text-zinc-500">Map not configured</p>
                            <p class="mt-1 text-xs text-zinc-400">Set <code class="rounded bg-zinc-200 px-1 dark:bg-zinc-700">map_embed_url</code> via Admin → CMS → Contact</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

</x-layouts::website>
