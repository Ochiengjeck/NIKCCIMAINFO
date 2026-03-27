<x-layouts::website :title="'Apply for Membership — NiKCCIMA'">

    {{--
        CMS-MANAGED SECTIONS — None (static wrapper for the Livewire application form)
        To manage form categories: Admin → Membership → Categories & Fees
    --}}

    {{-- Hero --}}
    <section class="relative overflow-hidden py-20 text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-green-950 to-[#922529]">
            <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dp" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dp)"/>
            </svg>
        </div>
        <div class="relative mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-5 flex items-center gap-2 text-sm text-brand-200">
                <a href="{{ route('membership') }}" class="hover:text-white transition">Membership</a>
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-white">Apply</span>
            </nav>
            <span class="mb-4 inline-flex rounded-full bg-crimson-700 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">Application</span>
            <h1 class="font-serif text-4xl font-bold lg:text-5xl">Membership Application</h1>
            <p class="mt-4 max-w-xl text-brand-100">Complete the form below to apply for NiKCCIMA membership. Our team reviews all applications within 5 working days.</p>

            {{-- Steps indicator --}}
            <div class="mt-8 flex items-center gap-3">
                @foreach(['Personal Info', 'Category', 'Business Profile', 'Declaration'] as $i => $label)
                    <div class="flex items-center gap-2">
                        <div class="flex h-6 w-6 items-center justify-center rounded-full {{ $i === 0 ? 'bg-white text-brand-900' : 'bg-crimson-800/60 text-brand-200' }} text-xs font-bold">{{ $i + 1 }}</div>
                        <span class="hidden text-xs text-brand-200 sm:block">{{ $label }}</span>
                    </div>
                    @if($i < 3)
                        <div class="h-px w-6 bg-crimson-800/50"></div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    {{-- Form --}}
    <section class="bg-zinc-50 py-16">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <livewire:public.membership-apply />
        </div>
    </section>

</x-layouts::website>
