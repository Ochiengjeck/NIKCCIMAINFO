<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        {{-- Playfair Display for brand headlines --}}
        <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700,800&display=swap" rel="stylesheet" />
    </head>

    <body class="min-h-screen antialiased" style="background: linear-gradient(135deg, #052e16 0%, #14532d 55%, #166534 100%)">

        {{-- Decorative layer: dot pattern + blobs --}}
        <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
            {{-- Subtle dot grid --}}
            <div class="absolute inset-0 opacity-[0.035]"
                 style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 28px 28px;"></div>
            {{-- Blobs --}}
            <div class="absolute -left-40 -top-40 h-[520px] w-[520px] rounded-full bg-green-400/20 blur-3xl"></div>
            <div class="absolute right-[-8rem] top-16 h-[420px] w-[420px] rounded-full bg-emerald-400/15 blur-3xl"></div>
            <div class="absolute bottom-[-4rem] left-1/3 h-[380px] w-[380px] rounded-full bg-red-500/10 blur-3xl"></div>
            <div class="absolute bottom-1/2 right-1/4 h-[280px] w-[280px] rounded-full bg-green-600/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto grid min-h-screen w-full max-w-7xl grid-cols-1 px-6 py-10 md:px-10 lg:grid-cols-2 lg:items-stretch lg:gap-16 lg:py-14">

            {{-- ======= LEFT PANEL (desktop only) ======= --}}
            <section class="hidden flex-col justify-between lg:flex">

                {{-- Logo --}}
                @php $siteLogo = \App\Models\SystemSetting::get('site_logo'); @endphp
                <a href="{{ route('home') }}" class="inline-flex w-fit items-center gap-3 group" wire:navigate>
                    @if($siteLogo)
                        <img src="{{ Storage::disk('public')->url($siteLogo) }}" alt="NiKCCIMA" class="h-10 w-auto object-contain brightness-0 invert">
                    @else
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-700 text-white shadow-lg ring-1 ring-green-600/40 transition group-hover:bg-green-600">
                            <span class="text-sm font-bold leading-none" style="font-family: 'Playfair Display', serif;">NK</span>
                        </div>
                        <div>
                            <span class="block text-sm font-bold tracking-wide text-white" style="font-family: 'Playfair Display', serif;">NiKCCIMA</span>
                            <span class="block text-[11px] text-green-300/60">Nigeria-Kenya Chamber</span>
                        </div>
                    @endif
                </a>

                {{-- Headline block --}}
                <div class="max-w-lg">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-red-500/40 bg-red-500/10 px-3 py-1.5 text-xs font-medium tracking-wide text-red-300">
                        <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-red-400"></span>
                        AfCFTA Corridor Execution
                    </div>
                    <h1 class="text-4xl font-bold leading-tight text-white xl:text-[2.75rem]"
                        style="font-family: 'Playfair Display', serif;">
                        Welcome to your chamber operations workspace.
                    </h1>
                    <p class="mt-5 text-base leading-relaxed text-green-200/65">
                        Manage memberships, trade pipelines, policy data, and bilateral governance — all from one platform built for the Nigeria-Kenya AfCFTA corridor.
                    </p>
                </div>

                {{-- Feature tiles --}}
                <div class="grid max-w-lg grid-cols-3 gap-3">
                    <div class="rounded-xl border border-green-500/20 bg-green-500/5 p-4 backdrop-blur-sm">
                        <p class="text-xs font-semibold text-white">Chapter-ready</p>
                        <p class="mt-1 text-[11px] leading-relaxed text-green-200/55">Nigeria &amp; Kenya isolation</p>
                    </div>
                    <div class="rounded-xl border border-green-500/20 bg-green-500/8 p-4 backdrop-blur-sm">
                        <p class="text-xs font-semibold text-white">Role-based</p>
                        <p class="mt-1 text-[11px] leading-relaxed text-green-200/55">16 governed access roles</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                        <p class="text-xs font-semibold text-white">Audit-safe</p>
                        <p class="mt-1 text-[11px] leading-relaxed text-green-200/55">Full activity trail</p>
                    </div>
                </div>
            </section>

            {{-- ======= RIGHT PANEL (form card) ======= --}}
            <section class="flex items-center justify-center">
                <div class="w-full max-w-md">

                    {{-- Mobile logo --}}
                    <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-3 lg:hidden" wire:navigate>
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-700 text-white shadow-lg ring-1 ring-green-600/40">
                            <span class="text-xs font-bold leading-none" style="font-family: 'Playfair Display', serif;">NK</span>
                        </div>
                        <div>
                            <span class="block text-sm font-bold tracking-wide text-white" style="font-family: 'Playfair Display', serif;">NiKCCIMA</span>
                            <span class="block text-[11px] text-green-300/60">Nigeria-Kenya Chamber</span>
                        </div>
                    </a>

                    {{-- Card --}}
                    <div class="rounded-2xl border border-green-800/40 bg-zinc-950/70 px-7 py-8 shadow-2xl shadow-black/60 backdrop-blur-xl md:px-9 md:py-10">
                        <div class="flex flex-col gap-6">
                            {{ $slot }}
                        </div>
                    </div>

                    {{-- Back link --}}
                    <p class="mt-6 text-center text-xs text-green-300/40">
                        <a href="{{ route('home') }}" class="transition hover:text-green-300/70" wire:navigate>
                            ← Back to NiKCCIMA website
                        </a>
                    </p>
                </div>
            </section>

        </div>

        @fluxScripts
    </body>
</html>
