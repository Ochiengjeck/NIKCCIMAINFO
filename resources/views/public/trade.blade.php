<x-layouts::website :title="'Trade & Investment — NiKCCIMA'">

    <section class="bg-green-900 py-16 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span class="mb-3 inline-block text-xs font-semibold uppercase tracking-widest text-green-300">Portal</span>
            <h1 class="text-4xl font-bold">{{ $page?->section('hero_title', 'Trade & Investment Portal') }}</h1>
            <p class="mt-3 max-w-2xl text-green-200">{{ $page?->section('hero_subtitle', 'Connect with active trade opportunities and anchor investors across the Nigeria-Kenya AfCFTA corridor.') }}</p>
        </div>
    </section>

    {{-- Intro --}}
    @if($page?->section('intro_body'))
        <section class="border-b border-zinc-100 py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="max-w-3xl text-zinc-600">{{ $page->section('intro_body') }}</p>
            </div>
        </section>
    @endif

    {{-- Trade Opportunity Board --}}
    <section class="py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <span class="mb-2 inline-block text-xs font-semibold uppercase tracking-widest text-green-600">Live</span>
                <h2 class="text-2xl font-bold text-zinc-900">Trade Opportunity Board</h2>
                <p class="mt-1 text-sm text-zinc-500">Active trade leads from chamber members. Submit an inquiry to request an introduction.</p>
            </div>

            @if($leads->isEmpty())
                <div class="flex h-40 items-center justify-center rounded-xl border border-dashed border-zinc-300 bg-zinc-50">
                    <p class="text-zinc-500">No active trade leads at this time.</p>
                </div>
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($leads as $lead)
                        <div class="rounded-xl border border-zinc-200 bg-white p-6">
                            <div class="mb-3 flex flex-wrap items-center gap-2">
                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                    {{ ucwords(str_replace('-', ' ', $lead->type ?? 'Trade Lead')) }}
                                </span>
                                @if($lead->sector)
                                    <span class="inline-flex rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-600">
                                        {{ $lead->sector->name ?? $lead->sector }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="mb-2 font-semibold text-zinc-900">{{ $lead->title }}</h3>
                            @if($lead->description)
                                <p class="mb-4 text-sm text-zinc-500 line-clamp-3">{{ $lead->description }}</p>
                            @endif
                            <a href="{{ route('contact') }}?subject={{ urlencode('Trade Inquiry: '.$lead->title) }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-green-200 bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-100">
                                Submit Inquiry &rarr;
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">{{ $leads->links() }}</div>
            @endif
        </div>
    </section>

    {{-- Anchor Investor Showcase --}}
    @if($investors->isNotEmpty())
        <section class="bg-zinc-50 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <span class="mb-2 inline-block text-xs font-semibold uppercase tracking-widest text-green-600">Investment</span>
                    <h2 class="text-2xl font-bold text-zinc-900">Anchor Investor Showcase</h2>
                    <p class="mt-1 text-sm text-zinc-500">Major investors active in the Nigeria-Kenya trade corridor.</p>
                </div>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($investors as $investor)
                        <div class="rounded-xl border border-zinc-200 bg-white p-6">
                            <h3 class="mb-2 font-semibold text-zinc-900">{{ $investor->company_name }}</h3>
                            <div class="flex flex-wrap gap-2">
                                @if($investor->sector)
                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                        {{ $investor->sector->name ?? $investor->sector }}
                                    </span>
                                @endif
                                @if($investor->investment_range)
                                    <span class="inline-flex rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-600">
                                        {{ $investor->investment_range }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Export Readiness Resources --}}
    @if($page?->section('export_resources_body'))
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-zinc-900">{{ $page->section('export_resources_heading', 'Export Readiness Resources') }}</h2>
                </div>
                <div class="prose max-w-3xl text-zinc-600">
                    {!! $page->section('export_resources_body') !!}
                </div>
                <div class="mt-6">
                    <a href="{{ route('downloads') }}" class="inline-flex items-center gap-2 rounded-lg bg-green-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-800">
                        Browse All Downloads &rarr;
                    </a>
                </div>
            </div>
        </section>
    @endif

</x-layouts::website>
