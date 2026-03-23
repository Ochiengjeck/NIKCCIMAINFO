{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Membership"
    banner_image       : Banner background image (PLACEHOLDER UNTIL UPLOADED)
    hero_title         : Heading (default: "Join NiKCCIMA")
    hero_subtitle      : Subheading
    intro_body         : Intro paragraph
    apply_heading      : CTA section heading
    apply_body         : CTA section body text
    platinum_benefits  : Platinum tier benefits (newline-separated, optional)
    gold_benefits      : Gold tier benefits
    silver_benefits    : Silver tier benefits
    bronze_benefits    : Bronze tier benefits
--}}
<x-layouts::website :title="'Membership — NiKCCIMA'">

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden py-24 text-white lg:py-32">
        {{-- Background: CMS banner_image or gradient --}}
        @if($page?->section('banner_image'))
            <div class="absolute inset-0">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('banner_image')) }}"
                     alt=""
                     class="h-full w-full object-cover"
                     aria-hidden="true">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-950/90 via-brand-900/80 to-[#7a1e22]/70"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-brand-950 via-brand-900 to-[#7a1e22]"></div>
        @endif

        {{-- Dot pattern overlay --}}
        <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <defs>
                <pattern id="dp-membership" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dp-membership)"/>
        </svg>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <span class="mb-4 inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-[#922529]">
                    Join
                </span>
                <h1 class="mb-5 font-serif text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                    {{ $page?->section('hero_title', 'Join NiKCCIMA') }}
                </h1>
                <p class="max-w-2xl text-lg text-[#922529] sm:text-xl">
                    {{ $page?->section('hero_subtitle', 'Become a member and access bilateral trade corridors, policy advocacy, and structured B2B pipelines across the Nigeria–Kenya AfCFTA corridor.') }}
                </p>
            </div>
        </div>
    </section>

    {{-- ===================== INTRO ===================== --}}
    @if($page?->section('intro_body'))
        <section class="border-b border-zinc-100 py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="max-w-3xl text-lg leading-relaxed text-zinc-600">
                    {{ $page->section('intro_body') }}
                </p>
            </div>
        </section>
    @endif

    {{-- ===================== BENEFITS VISUAL ===================== --}}
    {{-- CMS: membership → benefits_image (upload 800×600) --}}
    @if($page?->section('benefits_image'))
        <section class="py-16 bg-zinc-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-widest text-[#922529]">Why Join</span>
                        <h2 class="mt-2 font-serif text-3xl font-bold text-zinc-900 lg:text-4xl">Membership Benefits</h2>
                        <ul class="mt-6 space-y-4">
                            @foreach([
                                'Direct access to the Nigeria-Kenya bilateral trade corridor',
                                'Structured B2B matchmaking and deal pipeline facilitation',
                                'Policy advocacy, NTB resolution, and AfCFTA compliance support',
                                'Flagship summit, trade mission, and networking event access',
                                'Visibility across both countries through chamber directories',
                            ] as $benefit)
                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#A8DCAB]/20">
                                        <svg class="h-3 w-3 text-[#922529]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span class="text-sm text-zinc-600">{{ $benefit }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('membership.apply') }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-[#922529] px-6 py-3 text-sm font-semibold text-white shadow transition hover:bg-[#922529]">
                            Apply Now
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                    <div class="relative">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($page->section('benefits_image')) }}"
                            alt="NiKCCIMA Membership Benefits"
                            class="w-full rounded-2xl shadow-2xl object-cover"
                        />
                        <div class="absolute -bottom-4 -right-4 -z-10 h-full w-full rounded-2xl border-2 border-[#A8DCAB]/30"></div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ===================== MEMBERSHIP TIERS ===================== --}}
    <section class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <div class="mb-12 text-center">
                <span class="mb-2 inline-block text-xs font-semibold uppercase tracking-widest text-[#922529]">
                    Categories
                </span>
                <h2 class="font-serif text-3xl font-bold text-zinc-900 sm:text-4xl">
                    Membership Tiers &amp; Fees
                </h2>
                <p class="mt-3 text-zinc-500">
                    Choose the tier that best fits your organisation's scale and ambitions.
                </p>
            </div>

            @if($categories->isEmpty())
                <div class="flex h-48 items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-zinc-50">
                    <div class="text-center">
                        <svg class="mx-auto mb-3 h-10 w-10 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-zinc-500">Membership categories coming soon.</p>
                    </div>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($categories as $category)
                        @php
                            $nameLower = strtolower($category->name);
                            $isPlatinum = str_contains($nameLower, 'platinum');
                            $isGold = str_contains($nameLower, 'gold');
                            $benefitsKey = $nameLower . '_benefits';
                        @endphp
                        <div class="flex flex-col rounded-2xl border bg-white p-6 transition hover:-translate-y-1 hover:shadow-lg
                            {{ $isPlatinum ? 'border-yellow-400 ring-2 ring-yellow-400' : ($isGold ? 'border-yellow-300/80 ring-2 ring-yellow-300/60' : 'border-zinc-200') }}">

                            {{-- Platinum badge --}}
                            @if($isPlatinum)
                                <div class="mb-3 self-start">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-semibold text-yellow-700 ring-1 ring-yellow-300">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Featured
                                    </span>
                                </div>
                            @endif

                            {{-- Tier name --}}
                            <h3 class="font-serif text-xl font-bold text-zinc-900">
                                {{ $category->name }}
                            </h3>

                            {{-- Description --}}
                            @if($category->description)
                                <p class="mt-2 mb-4 flex-1 text-sm text-zinc-500">
                                    {{ $category->description }}
                                </p>
                            @else
                                <div class="flex-1"></div>
                            @endif

                            {{-- Benefits list from CMS --}}
                            @if($page?->section($benefitsKey))
                                <ul class="mb-4 space-y-1">
                                    @foreach(array_filter(explode("\n", $page->section($benefitsKey))) as $b)
                                        <li class="flex items-start gap-1.5 text-xs text-zinc-600">
                                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-[#922529]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            {{ trim($b) }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            {{-- Fees --}}
                            <div class="mt-auto border-t pt-4
                                {{ $isPlatinum ? 'border-yellow-200' : 'border-zinc-100' }}">
                                <div class="flex items-baseline justify-between">
                                    <span class="text-lg font-bold text-zinc-900">
                                        &#8358;{{ number_format($category->fee_ngn) }}
                                    </span>
                                    <span class="text-xs font-medium text-zinc-400">NGN / yr</span>
                                </div>
                                <p class="mt-1 text-sm text-zinc-500">
                                    KES {{ number_format($category->fee_kes) }} / yr
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ===================== APPLY CTA ===================== --}}
    <section class="relative overflow-hidden py-20 bg-[#922529] text-white">
        {{-- Dot pattern --}}
        <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <defs>
                <pattern id="dp-membership-cta" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="white"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dp-membership-cta)"/>
        </svg>

        <div class="relative mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
            <span class="mb-3 inline-block text-xs font-semibold uppercase tracking-widest text-[#922529]">
                Get Started
            </span>
            <h2 class="mb-4 font-serif text-3xl font-bold text-white sm:text-4xl">
                {{ $page?->section('apply_heading', 'Ready to Apply?') }}
            </h2>
            <p class="mb-10 text-lg text-[#922529]">
                {{ $page?->section('apply_body', 'Submit your membership application online. Our team reviews all applications within 5 working days.') }}
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('membership.apply') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3.5 text-sm font-bold text-[#922529] shadow-sm transition hover:bg-[#A8DCAB]/20 hover:shadow-md">
                    Apply for Membership
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-[#922529] px-8 py-3.5 text-sm font-semibold text-[#922529] transition hover:border-[#A8DCAB]/30 hover:text-white">
                    View Details
                </a>
            </div>
        </div>
    </section>

</x-layouts::website>
