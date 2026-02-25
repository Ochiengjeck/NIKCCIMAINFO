<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <title>{{ $title ?? 'NiKCCIMA Backoffice' }}</title>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-2 px-2 py-1">
                    <span class="text-sm font-bold tracking-wide text-zinc-900 dark:text-white">NiKCCIMA</span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">Backoffice</span>
                </a>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="grow">

                {{-- Dashboard --}}
                <flux:sidebar.item
                    icon="squares-2x2"
                    :href="route('admin.dashboard')"
                    :current="request()->routeIs('admin.dashboard')"
                    wire:navigate
                >
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                {{-- Governance --}}
                <flux:sidebar.group heading="Governance" class="grid">
                    <flux:sidebar.item icon="briefcase" :href="route('admin.governance.plans')" :current="request()->routeIs('admin.governance.plans')" wire:navigate>{{ __('Strategic Plans') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="document-text" :href="route('admin.governance.resolutions')" :current="request()->routeIs('admin.governance.resolutions*')" wire:navigate>{{ __('Board Resolutions') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="paper-clip" :href="route('admin.governance.mous')" :current="request()->routeIs('admin.governance.mous')" wire:navigate>{{ __('MoU Repository') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="building-office" :href="route('admin.governance.engagements')" :current="request()->routeIs('admin.governance.engagements')" wire:navigate>{{ __('Gov. Liaison Tracker') }}</flux:sidebar.item>
                </flux:sidebar.group>

                {{-- Membership --}}
                <flux:sidebar.group heading="Membership" class="grid">
                    <flux:sidebar.item icon="users" :href="route('admin.membership.members')" :current="request()->routeIs('admin.membership.members*')" wire:navigate>{{ __('Members') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="clipboard-document-list" :href="route('admin.membership.applications')" :current="request()->routeIs('admin.membership.applications*')" wire:navigate>{{ __('Applications') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="tag" :href="route('admin.membership.categories')" :current="request()->routeIs('admin.membership.categories')" wire:navigate>{{ __('Categories & Fees') }}</flux:sidebar.item>
                </flux:sidebar.group>

                {{-- Trade & Investment --}}
                <flux:sidebar.group heading="Trade & Investment" class="grid">
                    <flux:sidebar.item icon="arrow-trending-up" :href="route('admin.trade.leads')" :current="request()->routeIs('admin.trade.leads')" wire:navigate>{{ __('Trade Leads') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="circle-stack" :href="route('admin.trade.deals')" :current="request()->routeIs('admin.trade.deals')" wire:navigate>{{ __('Deal Pipeline') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="map" :href="route('admin.trade.corridors')" :current="request()->routeIs('admin.trade.corridors')" wire:navigate>{{ __('Corridors') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="building-storefront" :href="route('admin.trade.investors')" :current="request()->routeIs('admin.trade.investors')" wire:navigate>{{ __('Anchor Investors') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="arrows-right-left" :href="route('admin.trade.b2b')" :current="request()->routeIs('admin.trade.b2b')" wire:navigate>{{ __('B2B Matchmaking') }}</flux:sidebar.item>
                </flux:sidebar.group>

                {{-- Policy & Research --}}
                <flux:sidebar.group heading="Policy & Research" class="grid">
                    <flux:sidebar.item icon="exclamation-triangle" :href="route('admin.policy.ntbs')" :current="request()->routeIs('admin.policy.ntbs')" wire:navigate>{{ __('NTBs') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="document-magnifying-glass" :href="route('admin.policy.briefs')" :current="request()->routeIs('admin.policy.briefs')" wire:navigate>{{ __('Policy Briefs') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="book-open" :href="route('admin.policy.roo')" :current="request()->routeIs('admin.policy.roo')" wire:navigate>{{ __('Rules of Origin') }}</flux:sidebar.item>
                </flux:sidebar.group>

                {{-- Finance --}}
                <flux:sidebar.group heading="Finance" class="grid">
                    <flux:sidebar.item icon="banknotes" :href="route('admin.finance.transactions')" :current="request()->routeIs('admin.finance.transactions')" wire:navigate>{{ __('Transactions') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="receipt-percent" :href="route('admin.finance.invoices')" :current="request()->routeIs('admin.finance.invoices')" wire:navigate>{{ __('Invoices') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="chart-bar" :href="route('admin.finance.budgets')" :current="request()->routeIs('admin.finance.budgets')" wire:navigate>{{ __('Budget Tracker') }}</flux:sidebar.item>
                </flux:sidebar.group>

                {{-- Events --}}
                <flux:sidebar.group heading="Events" class="grid">
                    <flux:sidebar.item icon="calendar" :href="route('admin.events.index')" :current="request()->routeIs('admin.events.index')" wire:navigate>{{ __('Events & Missions') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="identification" :href="route('admin.events.desk')" :current="request()->routeIs('admin.events.desk')" wire:navigate>{{ __('Registration Desk') }}</flux:sidebar.item>
                </flux:sidebar.group>

                {{-- Documents --}}
                <flux:sidebar.group heading="Knowledge" class="grid">
                    <flux:sidebar.item icon="folder-open" :href="route('admin.documents.index')" :current="request()->routeIs('admin.documents.*')" wire:navigate>{{ __('Document Library') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="puzzle-piece" :href="route('admin.platforms.index')" :current="request()->routeIs('admin.platforms.*')" wire:navigate>{{ __('Technical Platforms') }}</flux:sidebar.item>
                </flux:sidebar.group>

                {{-- Website / CMS --}}
                <flux:sidebar.group heading="Website" class="grid">
                    <flux:sidebar.item icon="document-text" :href="route('admin.cms.pages')" :current="request()->routeIs('admin.cms.pages')" wire:navigate>{{ __('Pages') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="newspaper" :href="route('admin.cms.news')" :current="request()->routeIs('admin.cms.news')" wire:navigate>{{ __('News & Press') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="identification" :href="route('admin.cms.leadership')" :current="request()->routeIs('admin.cms.leadership')" wire:navigate>{{ __('Leadership') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="photo" :href="route('admin.cms.media')" :current="request()->routeIs('admin.cms.media')" wire:navigate>{{ __('Media Library') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="envelope" :href="route('admin.cms.contact')" :current="request()->routeIs('admin.cms.contact')" wire:navigate>{{ __('Contact Inquiries') }}</flux:sidebar.item>
                </flux:sidebar.group>

                {{-- Reports & KPIs --}}
                <flux:sidebar.group heading="Intelligence" class="grid">
                    <flux:sidebar.item icon="chart-pie" :href="route('admin.kpi.dashboard')" :current="request()->routeIs('admin.kpi.*')" wire:navigate>{{ __('KPI Dashboard') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="printer" :href="route('admin.reports.index')" :current="request()->routeIs('admin.reports.*')" wire:navigate>{{ __('Reports') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="shield-check" :href="route('admin.audit.index')" :current="request()->routeIs('admin.audit.*')" wire:navigate>{{ __('Audit Log') }}</flux:sidebar.item>
                </flux:sidebar.group>

                {{-- System --}}
                <flux:sidebar.group heading="System" class="grid">
                    <flux:sidebar.item icon="user-group" :href="route('admin.system.users')" :current="request()->routeIs('admin.system.users')" wire:navigate>{{ __('Users & Roles') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="globe-alt" :href="route('admin.system.chapters')" :current="request()->routeIs('admin.system.chapters')" wire:navigate>{{ __('Chapters') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="chat-bubble-left-ellipsis" :href="route('admin.chatbot.faqs')" :current="request()->routeIs('admin.chatbot.*')" wire:navigate>{{ __('Chatbot') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="cog-6-tooth" :href="route('admin.system.settings')" :current="request()->routeIs('admin.system.settings')" wire:navigate>{{ __('Settings') }}</flux:sidebar.item>
                </flux:sidebar.group>

            </flux:sidebar.nav>

            <flux:spacer />

            {{-- Chapter Badge --}}
            @if(auth()->user()->chapter)
                <div class="px-3 pb-2">
                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                        {{ auth()->user()->chapter->name }} Chapter
                    </span>
                </div>
            @endif

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile Header -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <span class="text-sm font-semibold text-zinc-900 dark:text-white">NiKCCIMA</span>
            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />
                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>
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
