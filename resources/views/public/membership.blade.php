{{--
    CMS-MANAGED SECTIONS — Admin → CMS → Pages → "Membership"
    banner_image       : Banner background image (PLACEHOLDER UNTIL UPLOADED)
    hero_title         : Heading (default: "Membership & Tiers")
    hero_subtitle      : Subheading
    intro_body         : Intro paragraph (shown above table if set)
    apply_heading      : CTA section heading
    apply_body         : CTA section body text
    platinum_benefits  : Platinum tier benefits (newline-separated, optional)
    gold_benefits      : Gold tier benefits
    silver_benefits    : Silver tier benefits
    bronze_benefits    : Bronze tier benefits
--}}
<x-layouts::website
    :title="$page?->meta_title ?: 'Membership — NiKCCIMA'"
    :meta-description="$page?->meta_description ?: $page?->section('hero_subtitle', 'Join NiKCCIMA — membership tiers, benefits, and how to apply for the Nigeria-Kenya Chamber of Commerce.')">

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
            <span class="block text-xs font-bold uppercase tracking-widest text-brand-200 mb-3">Join</span>
            <h1 class="font-serif text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl mb-4">
                {{ $page?->section('hero_title', 'Membership &amp; Tiers') }}
            </h1>
            <p class="max-w-2xl mx-auto text-lg text-zinc-300 sm:text-xl">
                {{ $page?->section('hero_subtitle', 'Become a member and access bilateral trade corridors, policy advocacy, and structured B2B pipelines across the Nigeria–Kenya AfCFTA corridor.') }}
            </p>
        </div>
    </section>

    {{-- ===================== MEMBERSHIP TABLE ===================== --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4">

            <span class="block text-xs font-bold uppercase tracking-widest text-crimson-700 text-center mb-3">Membership</span>
            <h2 class="text-3xl lg:text-4xl font-bold font-serif text-zinc-900 text-center mb-4">Our Membership Tiers</h2>
            <div class="w-20 h-1.5 bg-brand-500 rounded-full mx-auto mb-14"></div>

            @if($page?->section('intro_body'))
                <div class="prose prose-zinc max-w-none mb-10 text-zinc-600 leading-relaxed">
                    {!! $page->section('intro_body') !!}
                </div>
            @endif

            {{-- Table header --}}
            <div class="flex items-center border-b-2 border-crimson-700 pb-4 mb-2">
                <span class="w-1/4 text-sm font-bold uppercase tracking-wide text-crimson-700">Membership Tier</span>
                <span class="w-1/4 text-sm font-bold uppercase tracking-wide text-crimson-700">Annual Fee</span>
                <span class="w-1/2 text-sm font-bold uppercase tracking-wide text-crimson-700">Key Benefits</span>
            </div>

            {{-- Table rows --}}
            @forelse($categories as $category)
                @php
                    $nameLower = strtolower($category->name);
                    $benefitsKey = $nameLower . '_benefits';
                @endphp
                <div class="flex items-start border-b border-zinc-200 py-5 hover:bg-zinc-50 transition-colors">
                    <div class="w-1/4 font-semibold text-zinc-900">{{ $category->name }}</div>
                    <div class="w-1/4 text-zinc-700">
                        @if($category->fee_ngn)
                            &#8358;{{ number_format($category->fee_ngn) }} / yr
                        @elseif($category->fee_kes)
                            KES {{ number_format($category->fee_kes) }} / yr
                        @else
                            &mdash;
                        @endif
                    </div>
                    <div class="w-1/2 text-zinc-600 text-sm leading-relaxed">
                        {{ $category->description ?? '—' }}
                    </div>
                </div>
            @empty
                {{-- Static fallback rows --}}
                <div class="flex items-start border-b border-zinc-200 py-5 hover:bg-zinc-50 transition-colors">
                    <div class="w-1/4 font-semibold text-zinc-900">Patron's Circle</div>
                    <div class="w-1/4 text-zinc-700">&#8358;5,000,000 / yr</div>
                    <div class="w-1/2 text-zinc-600 text-sm leading-relaxed">Full bilateral corridor access, flagship summit sponsorship, priority B2B matchmaking, and dedicated secretariat support.</div>
                </div>
                <div class="flex items-start border-b border-zinc-200 py-5 hover:bg-zinc-50 transition-colors">
                    <div class="w-1/4 font-semibold text-zinc-900">Premier Member</div>
                    <div class="w-1/4 text-zinc-700">&#8358;1,500,000 / yr</div>
                    <div class="w-1/2 text-zinc-600 text-sm leading-relaxed">Trade lead access, AfCFTA compliance guidance, event participation, and directory listing in both countries.</div>
                </div>
                <div class="flex items-start border-b border-zinc-200 py-5 hover:bg-zinc-50 transition-colors">
                    <div class="w-1/4 font-semibold text-zinc-900">Standard Member</div>
                    <div class="w-1/4 text-zinc-700">&#8358;500,000 / yr</div>
                    <div class="w-1/2 text-zinc-600 text-sm leading-relaxed">Chamber network access, policy bulletins, sector forum attendance, and member chamber directory listing.</div>
                </div>
            @endforelse

            <div class="mt-8 text-center">
                <a href="{{ route('membership.apply') }}" class="inline-block bg-brand-500 text-white px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                    Apply for Membership
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== CTA STRIP ===================== --}}
    <section class="bg-crimson-700 py-20">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="font-serif text-3xl font-bold text-white mb-4">Ready to Join NiKCCIMA?</h2>
            <div class="w-20 h-1.5 bg-white/50 rounded-full mx-auto mb-10"></div>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('membership.apply') }}"
                   class="inline-block bg-white text-crimson-700 px-8 py-3 rounded text-sm font-medium hover:opacity-90 transition-all">
                    Apply Now
                </a>
                <a href="{{ route('about') }}"
                   class="inline-block border border-white/60 text-white px-8 py-3 rounded text-sm font-medium hover:bg-white/10 transition-all">
                    Learn More
                </a>
            </div>
            <p class="text-center text-white/70 mt-6 text-sm">Members gain access to the full AfCFTA Nigeria-Kenya trade facilitation network.</p>
        </div>
    </section>

</x-layouts::website>
