<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700,800&display=swap" rel="stylesheet" />
        <title>{{ $title ?? 'NiKCCIMA Backoffice' }}</title>
    </head>

    <body class="min-h-screen bg-zinc-900 antialiased">

        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-800/60 bg-zinc-950">

            {{-- ═══ HEADER ═══ --}}
            <flux:sidebar.header class="border-b border-zinc-800/60 px-4 py-3.5">
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="group flex min-w-0 flex-1 items-center gap-3">
                    @php $siteLogo = \App\Models\SystemSetting::get('site_logo'); @endphp
                    @if($siteLogo)
                        <img src="{{ Storage::disk('public')->url($siteLogo) }}"
                             alt="{{ config('app.name') }}"
                             class="h-8 w-8 rounded-lg object-contain" />
                    @else
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-green-700 shadow-md shadow-green-950 ring-1 ring-green-600/30 transition group-hover:bg-green-600">
                            <span class="text-[11px] font-bold leading-none text-white" style="font-family:'Playfair Display',serif">NK</span>
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="truncate text-[13px] font-semibold leading-tight text-white" style="font-family:'Playfair Display',serif">NiKCCIMA</p>
                        <p class="text-[10px] leading-tight text-zinc-500">Backoffice Panel</p>
                    </div>
                </a>
                <flux:sidebar.collapse class="ml-1 shrink-0 text-zinc-500 hover:text-zinc-300 lg:hidden" />
            </flux:sidebar.header>

            {{-- ═══ NAVIGATION ═══ --}}
            @php
                // Shared nav item classes
                $a  = 'group flex w-full items-center gap-2.5 rounded-lg px-3 py-[7px] text-[13px] font-medium text-green-300 bg-green-500/10';
                $i  = 'group flex w-full items-center gap-2.5 rounded-lg px-3 py-[7px] text-[13px] font-medium text-zinc-400 transition-colors duration-150 hover:bg-zinc-800/60 hover:text-zinc-100';
                $ai = 'h-4 w-4 shrink-0 text-green-400';
                $ii = 'h-4 w-4 shrink-0 text-zinc-600 transition-colors duration-150 group-hover:text-zinc-400';
                // Group label
                $g  = 'mb-0.5 mt-5 px-3 text-[10px] font-semibold uppercase tracking-widest text-zinc-600';
            @endphp

            <div class="flex flex-1 flex-col overflow-y-auto px-2 py-3 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">

                {{-- ── Dashboard ── --}}
                @php $r = request()->routeIs('admin.dashboard'); @endphp
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="squares-2x2" class="{{ $r ? $ai : $ii }}" />
                    Dashboard
                </a>

                {{-- ── Governance ── --}}
                <p class="{{ $g }}">Governance</p>

                @php $r = request()->routeIs('admin.governance.plans'); @endphp
                <a href="{{ route('admin.governance.plans') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="briefcase" class="{{ $r ? $ai : $ii }}" />
                    Strategic Plans
                </a>

                @php $r = request()->routeIs('admin.governance.resolutions*'); @endphp
                <a href="{{ route('admin.governance.resolutions') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="document-text" class="{{ $r ? $ai : $ii }}" />
                    Board Resolutions
                </a>

                @php $r = request()->routeIs('admin.governance.mous'); @endphp
                <a href="{{ route('admin.governance.mous') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="paper-clip" class="{{ $r ? $ai : $ii }}" />
                    MoU Repository
                </a>

                @php $r = request()->routeIs('admin.governance.engagements'); @endphp
                <a href="{{ route('admin.governance.engagements') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="building-office" class="{{ $r ? $ai : $ii }}" />
                    Gov. Liaison Tracker
                </a>

                {{-- ── Membership ── --}}
                <p class="{{ $g }}">Membership</p>

                @php $r = request()->routeIs('admin.membership.members*'); @endphp
                <a href="{{ route('admin.membership.members') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="users" class="{{ $r ? $ai : $ii }}" />
                    Members
                </a>

                @php $r = request()->routeIs('admin.membership.applications*'); @endphp
                <a href="{{ route('admin.membership.applications') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="clipboard-document-list" class="{{ $r ? $ai : $ii }}" />
                    Applications
                </a>

                @php $r = request()->routeIs('admin.membership.categories'); @endphp
                <a href="{{ route('admin.membership.categories') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="tag" class="{{ $r ? $ai : $ii }}" />
                    Categories &amp; Fees
                </a>

                {{-- ── Trade & Investment ── --}}
                <p class="{{ $g }}">Trade &amp; Investment</p>

                @php $r = request()->routeIs('admin.trade.leads'); @endphp
                <a href="{{ route('admin.trade.leads') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="arrow-trending-up" class="{{ $r ? $ai : $ii }}" />
                    Trade Leads
                </a>

                @php $r = request()->routeIs('admin.trade.deals'); @endphp
                <a href="{{ route('admin.trade.deals') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="circle-stack" class="{{ $r ? $ai : $ii }}" />
                    Deal Pipeline
                </a>

                @php $r = request()->routeIs('admin.trade.corridors'); @endphp
                <a href="{{ route('admin.trade.corridors') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="map" class="{{ $r ? $ai : $ii }}" />
                    Corridors
                </a>

                @php $r = request()->routeIs('admin.trade.investors'); @endphp
                <a href="{{ route('admin.trade.investors') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="building-storefront" class="{{ $r ? $ai : $ii }}" />
                    Anchor Investors
                </a>

                @php $r = request()->routeIs('admin.trade.b2b'); @endphp
                <a href="{{ route('admin.trade.b2b') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="arrows-right-left" class="{{ $r ? $ai : $ii }}" />
                    B2B Matchmaking
                </a>

                {{-- ── Policy & Research ── --}}
                <p class="{{ $g }}">Policy &amp; Research</p>

                @php $r = request()->routeIs('admin.policy.ntbs'); @endphp
                <a href="{{ route('admin.policy.ntbs') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="exclamation-triangle" class="{{ $r ? $ai : $ii }}" />
                    NTBs
                </a>

                @php $r = request()->routeIs('admin.policy.briefs'); @endphp
                <a href="{{ route('admin.policy.briefs') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="document-magnifying-glass" class="{{ $r ? $ai : $ii }}" />
                    Policy Briefs
                </a>

                @php $r = request()->routeIs('admin.policy.roo'); @endphp
                <a href="{{ route('admin.policy.roo') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="book-open" class="{{ $r ? $ai : $ii }}" />
                    Rules of Origin
                </a>

                {{-- ── Finance ── --}}
                <p class="{{ $g }}">Finance</p>

                @php $r = request()->routeIs('admin.finance.transactions'); @endphp
                <a href="{{ route('admin.finance.transactions') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="banknotes" class="{{ $r ? $ai : $ii }}" />
                    Transactions
                </a>

                @php $r = request()->routeIs('admin.finance.invoices'); @endphp
                <a href="{{ route('admin.finance.invoices') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="receipt-percent" class="{{ $r ? $ai : $ii }}" />
                    Invoices
                </a>

                @php $r = request()->routeIs('admin.finance.budgets'); @endphp
                <a href="{{ route('admin.finance.budgets') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="chart-bar" class="{{ $r ? $ai : $ii }}" />
                    Budget Tracker
                </a>

                {{-- ── Events ── --}}
                <p class="{{ $g }}">Events</p>

                @php $r = request()->routeIs('admin.events.index'); @endphp
                <a href="{{ route('admin.events.index') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="calendar" class="{{ $r ? $ai : $ii }}" />
                    Events &amp; Missions
                </a>

                @php $r = request()->routeIs('admin.events.desk'); @endphp
                <a href="{{ route('admin.events.desk') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="identification" class="{{ $r ? $ai : $ii }}" />
                    Registration Desk
                </a>

                {{-- ── Knowledge ── --}}
                <p class="{{ $g }}">Knowledge</p>

                @php $r = request()->routeIs('admin.documents.*'); @endphp
                <a href="{{ route('admin.documents.index') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="folder-open" class="{{ $r ? $ai : $ii }}" />
                    Document Library
                </a>

                @php $r = request()->routeIs('admin.platforms.*'); @endphp
                <a href="{{ route('admin.platforms.index') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="puzzle-piece" class="{{ $r ? $ai : $ii }}" />
                    Technical Platforms
                </a>

                {{-- ── Website ── --}}
                <p class="{{ $g }}">Website</p>

                @php $r = request()->routeIs('admin.cms.pages'); @endphp
                <a href="{{ route('admin.cms.pages') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="document-text" class="{{ $r ? $ai : $ii }}" />
                    Pages
                </a>

                @php $r = request()->routeIs('admin.cms.news'); @endphp
                <a href="{{ route('admin.cms.news') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="newspaper" class="{{ $r ? $ai : $ii }}" />
                    News &amp; Press
                </a>

                @php $r = request()->routeIs('admin.cms.leadership'); @endphp
                <a href="{{ route('admin.cms.leadership') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="identification" class="{{ $r ? $ai : $ii }}" />
                    Leadership
                </a>

                @php $r = request()->routeIs('admin.cms.media'); @endphp
                <a href="{{ route('admin.cms.media') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="photo" class="{{ $r ? $ai : $ii }}" />
                    Media Library
                </a>

                @php $r = request()->routeIs('admin.cms.contact'); @endphp
                <a href="{{ route('admin.cms.contact') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="envelope" class="{{ $r ? $ai : $ii }}" />
                    Contact Inquiries
                </a>

                {{-- ── Intelligence ── --}}
                <p class="{{ $g }}">Intelligence</p>

                @php $r = request()->routeIs('admin.kpi.*'); @endphp
                <a href="{{ route('admin.kpi.dashboard') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="chart-pie" class="{{ $r ? $ai : $ii }}" />
                    KPI Dashboard
                </a>

                @php $r = request()->routeIs('admin.reports.*'); @endphp
                <a href="{{ route('admin.reports.index') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="printer" class="{{ $r ? $ai : $ii }}" />
                    Reports
                </a>

                @php $r = request()->routeIs('admin.audit.*'); @endphp
                <a href="{{ route('admin.audit.index') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="shield-check" class="{{ $r ? $ai : $ii }}" />
                    Audit Log
                </a>

                {{-- ── System ── --}}
                <p class="{{ $g }}">System</p>

                @php $r = request()->routeIs('admin.system.users') || request()->routeIs('admin.system.user-detail'); @endphp
                <a href="{{ route('admin.system.users') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="user-group" class="{{ $r ? $ai : $ii }}" />
                    Users &amp; Roles
                </a>

                @php $r = request()->routeIs('admin.system.chapters'); @endphp
                <a href="{{ route('admin.system.chapters') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="globe-alt" class="{{ $r ? $ai : $ii }}" />
                    Chapters
                </a>

                @php $r = request()->routeIs('admin.chatbot.*'); @endphp
                <a href="{{ route('admin.chatbot.faqs') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="chat-bubble-left-ellipsis" class="{{ $r ? $ai : $ii }}" />
                    Chatbot
                </a>

                @php $r = request()->routeIs('admin.system.settings'); @endphp
                <a href="{{ route('admin.system.settings') }}" wire:navigate class="{{ $r ? $a : $i }}">
                    <flux:icon name="cog-6-tooth" class="{{ $r ? $ai : $ii }}" />
                    Settings
                </a>

                {{-- Bottom padding --}}
                <div class="h-4"></div>
            </div>

            {{-- ═══ FOOTER ═══ --}}
            <div class="shrink-0 border-t border-zinc-800/60 p-3 pb-4">
                {{-- Chapter badge --}}
                @if(auth()->user()->chapter)
                    <div class="mb-3 px-1">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-900/40 px-2.5 py-1 text-[10px] font-medium text-green-300">
                            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-green-400"></span>
                            {{ auth()->user()->chapter->name }} Chapter
                        </span>
                    </div>
                @endif

                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            </div>

        </flux:sidebar>

        {{-- ═══ MOBILE HEADER ═══ --}}
        <flux:header class="border-b border-zinc-800/60 bg-zinc-950 lg:hidden">
            <flux:sidebar.toggle icon="bars-2" inset="left" class="text-zinc-400 hover:text-zinc-100" />

            <div class="ml-2 flex items-center gap-2.5">
                @php $siteLogo = \App\Models\SystemSetting::get('site_logo'); @endphp
                @if($siteLogo)
                    <img src="{{ Storage::disk('public')->url($siteLogo) }}" alt="{{ config('app.name') }}"
                         class="h-7 w-7 rounded-lg object-contain" />
                @else
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-green-700">
                        <span class="text-[10px] font-bold text-white" style="font-family:'Playfair Display',serif">NK</span>
                    </div>
                @endif
                <span class="text-sm font-semibold text-white" style="font-family:'Playfair Display',serif">NiKCCIMA</span>
            </div>

            <flux:spacer />

            {{-- Mobile user dropdown --}}
            <flux:dropdown position="bottom" align="end">
                @php $mobilePhoto = auth()->user()->profilePhotoUrl(); @endphp
                @if ($mobilePhoto)
                    <button type="button" class="flex items-center gap-1.5 rounded-full text-zinc-300">
                        <img src="{{ $mobilePhoto }}" alt="{{ auth()->user()->name }}"
                             class="h-8 w-8 rounded-full object-cover ring-1 ring-zinc-700" />
                        <flux:icon name="chevron-down" class="h-3.5 w-3.5 text-zinc-500" />
                    </button>
                @else
                    <flux:profile
                        :initials="auth()->user()->initials()"
                        icon-trailing="chevron-down"
                        class="text-zinc-300"
                    />
                @endif
                <flux:menu>
                    <div class="flex items-center gap-2.5 px-3 py-2.5">
                        @if ($mobilePhoto)
                            <img src="{{ $mobilePhoto }}" alt="{{ auth()->user()->name }}"
                                 class="h-8 w-8 rounded-full object-cover ring-1 ring-zinc-700" />
                        @else
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                        @endif
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-zinc-100">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-zinc-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <flux:menu.separator />
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
