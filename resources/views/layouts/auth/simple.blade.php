<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased bg-zinc-950 text-white">
        <div class="relative min-h-svh overflow-hidden">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-16 -top-24 h-80 w-80 rounded-full bg-green-500/20 blur-3xl"></div>
                <div class="absolute right-[-7rem] top-16 h-96 w-96 rounded-full bg-red-500/20 blur-3xl"></div>
                <div class="absolute bottom-[-8rem] left-1/3 h-96 w-96 rounded-full bg-emerald-400/10 blur-3xl"></div>
            </div>

            <div class="relative mx-auto grid min-h-svh w-full max-w-7xl grid-cols-1 px-6 py-8 md:px-10 lg:grid-cols-2 lg:items-stretch lg:gap-10 lg:py-10">
                <section class="hidden lg:flex lg:flex-col lg:justify-between">
                    <a href="{{ route('home') }}" class="inline-flex w-fit items-center gap-3 text-white" wire:navigate>
                        <span class="inline-flex size-10 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/20 backdrop-blur">
                            <x-app-logo-icon class="size-6 fill-current text-white" />
                        </span>
                        <span class="text-sm font-semibold tracking-[0.2em] uppercase">{{ config('app.name', 'NiKCCIMA') }}</span>
                    </a>

                    <div class="max-w-xl">
                        <p class="mb-4 inline-flex items-center rounded-full border border-green-500/30 bg-green-500/10 px-3 py-1 text-xs font-medium tracking-wide text-green-100/90">
                            Secure Member Access
                        </p>
                        <h1 class="text-4xl font-semibold leading-tight text-white xl:text-5xl">
                            Welcome to your chamber operations workspace.
                        </h1>
                        <p class="mt-5 max-w-lg text-base text-white/70">
                            Manage memberships, trade pipelines, policy data, and internal governance from one streamlined platform.
                        </p>
                    </div>

                    <div class="grid max-w-xl grid-cols-3 gap-3 text-xs text-white/70">
                        <div class="rounded-xl border border-green-500/20 bg-white/5 p-3 backdrop-blur">
                            Chapter-ready
                        </div>
                        <div class="rounded-xl border border-red-500/20 bg-white/5 p-3 backdrop-blur">
                            Role-based
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/5 p-3 backdrop-blur">
                            Audit-safe
                        </div>
                    </div>
                </section>

                <section class="flex items-center justify-center">
                    <div class="w-full max-w-md rounded-2xl border border-white/10 bg-zinc-900/80 p-6 shadow-2xl shadow-black/40 backdrop-blur md:p-8">
                        <a href="{{ route('home') }}" class="mb-6 inline-flex items-center gap-3 text-white lg:hidden" wire:navigate>
                            <span class="inline-flex size-9 items-center justify-center rounded-lg bg-white/10 text-white ring-1 ring-white/20">
                                <x-app-logo-icon class="size-5 fill-current text-white" />
                            </span>
                            <span class="text-xs font-semibold tracking-[0.2em] uppercase">{{ config('app.name', 'NiKCCIMA') }}</span>
                        </a>

                        <div class="flex flex-col gap-6">
                            {{ $slot }}
                        </div>
                    </div>
                </section>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
