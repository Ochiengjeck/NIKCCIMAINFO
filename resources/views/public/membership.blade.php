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
                <div class="absolute inset-0 bg-gradient-to-br from-green-950/90 via-green-900/80 to-emerald-800/70"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-green-950 via-green-900 to-emerald-800"></div>
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
                <span class="mb-4 inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-green-200">
                    Join
                </span>
                <h1 class="mb-5 font-serif text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                    {{ $page?->section('hero_title', 'Join NiKCCIMA') }}
                </h1>
                <p class="max-w-2xl text-lg text-green-100 sm:text-xl">
                    {{ $page?->section('hero_subtitle', 'Become a member and access bilateral trade corridors, policy advocacy, and structured B2B pipelines across the Nigeria–Kenya AfCFTA corridor.') }}
                </p>
            </div>
        </div>
    </section>

    {{-- ===================== INTRO ===================== --}}
    @if($page?->section('intro_body'))
        <section class="border-b border-zinc-100 py-8 dark:border-zinc-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="max-w-3xl text-lg leading-relaxed text-zinc-600 dark:text-zinc-300">
                    {{ $page->section('intro_body') }}
                </p>
            </div>
        </section>
    @endif

    {{-- ===================== MEMBERSHIP TIERS ===================== --}}
    <section class="py-20 bg-white dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Section header --}}
            <div class="mb-12 text-center">
                <span class="mb-2 inline-block text-xs font-semibold uppercase tracking-widest text-green-600 dark:text-green-400">
                    Categories
                </span>
                <h2 class="font-serif text-3xl font-bold text-zinc-900 dark:text-white sm:text-4xl">
                    Membership Tiers &amp; Fees
                </h2>
                <p class="mt-3 text-zinc-500 dark:text-zinc-400">
                    Choose the tier that best fits your organisation's scale and ambitions.
                </p>
            </div>

            @if($categories->isEmpty())
                <div class="flex h-48 items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="text-center">
                        <svg class="mx-auto mb-3 h-10 w-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-zinc-500 dark:text-zinc-400">Membership categories coming soon.</p>
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
                        <div class="flex flex-col rounded-2xl border bg-white p-6 transition hover:-translate-y-1 hover:shadow-lg dark:bg-zinc-900
                            {{ $isPlatinum ? 'border-yellow-400 ring-2 ring-yellow-400 dark:border-yellow-500 dark:ring-yellow-500' : ($isGold ? 'border-yellow-300/80 ring-2 ring-yellow-300/60 dark:border-yellow-600/60 dark:ring-yellow-600/40' : 'border-zinc-200 dark:border-zinc-700') }}">

                            {{-- Platinum badge --}}
                            @if($isPlatinum)
                                <div class="mb-3 self-start">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-semibold text-yellow-700 ring-1 ring-yellow-300 dark:bg-yellow-900/30 dark:text-yellow-400 dark:ring-yellow-700">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Featured
                                    </span>
                                </div>
                            @endif

                            {{-- Tier name --}}
                            <h3 class="font-serif text-xl font-bold text-zinc-900 dark:text-white">
                                {{ $category->name }}
                            </h3>

                            {{-- Description --}}
                            @if($category->description)
                                <p class="mt-2 mb-4 flex-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $category->description }}
                                </p>
                            @else
                                <div class="flex-1"></div>
                            @endif

                            {{-- Benefits list from CMS --}}
                            @if($page?->section($benefitsKey))
                                <ul class="mb-4 space-y-1">
                                    @foreach(array_filter(explode("\n", $page->section($benefitsKey))) as $b)
                                        <li class="flex items-start gap-1.5 text-xs text-zinc-600 dark:text-zinc-400">
                                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            {{ trim($b) }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            {{-- Fees --}}
                            <div class="mt-auto border-t pt-4
                                {{ $isPlatinum ? 'border-yellow-200 dark:border-yellow-800/40' : 'border-zinc-100 dark:border-zinc-800' }}">
                                <div class="flex items-baseline justify-between">
                                    <span class="text-lg font-bold text-zinc-900 dark:text-white">
                                        &#8358;{{ number_format($category->fee_ngn) }}
                                    </span>
                                    <span class="text-xs font-medium text-zinc-400 dark:text-zinc-500">NGN / yr</span>
                                </div>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
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
    <section class="relative overflow-hidden py-20 bg-green-900 text-white">
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
            <span class="mb-3 inline-block text-xs font-semibold uppercase tracking-widest text-green-300">
                Get Started
            </span>
            <h2 class="mb-4 font-serif text-3xl font-bold text-white sm:text-4xl">
                {{ $page?->section('apply_heading', 'Ready to Apply?') }}
            </h2>
            <p class="mb-10 text-lg text-green-200">
                {{ $page?->section('apply_body', 'Submit your membership application online. Our team reviews all applications within 5 working days.') }}
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('membership.apply') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3.5 text-sm font-bold text-green-900 shadow-sm transition hover:bg-green-50 hover:shadow-md">
                    Apply for Membership
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-green-600 px-8 py-3.5 text-sm font-semibold text-green-200 transition hover:border-green-400 hover:text-white">
                    View Details
                </a>
            </div>
        </div>
    </section>

</x-layouts::website>
