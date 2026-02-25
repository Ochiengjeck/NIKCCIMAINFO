<div>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">Contact Inquiries</flux:heading>
            <flux:subheading>Messages submitted via the public Contact page.</flux:subheading>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats Strip --}}
    <div class="mb-6 grid grid-cols-3 gap-4">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-500">Total</p>
            <p class="mt-1 text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-900/20">
            <p class="text-xs font-medium uppercase tracking-wide text-green-600 dark:text-green-400">New</p>
            <p class="mt-1 text-3xl font-bold text-green-700 dark:text-green-300">{{ $stats['new'] }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-xs font-medium uppercase tracking-wide text-zinc-500">Responded</p>
            <p class="mt-1 text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['responded'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-wrap gap-3">
        <div class="relative">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search name, email, subject..."
                class="w-64 rounded-lg border border-zinc-200 py-2 pl-9 pr-3 text-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
            <svg class="absolute left-3 top-2.5 h-4 w-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <select wire:model.live="statusFilter"
            class="rounded-lg border border-zinc-200 px-3 py-2 text-sm focus:border-green-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
            <option value="">All Statuses</option>
            <option value="new">New</option>
            <option value="read">Read</option>
            <option value="responded">Responded</option>
        </select>
        <select wire:model.live="chapterFilter"
            class="rounded-lg border border-zinc-200 px-3 py-2 text-sm focus:border-green-500 focus:outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
            <option value="">All Chapters</option>
            <option value="nigeria">🇳🇬 Nigeria</option>
            <option value="kenya">🇰🇪 Kenya</option>
            <option value="general">General</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        @if($inquiries->isEmpty())
            <div class="flex h-40 items-center justify-center">
                <div class="text-center">
                    <svg class="mx-auto mb-2 h-10 w-10 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm text-zinc-500">No inquiries found.</p>
                </div>
            </div>
        @else
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">From</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Subject / Message</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Chapter</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-zinc-500">Date</th>
                        <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wide text-zinc-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach($inquiries as $inquiry)
                        <tr
                            x-data="{ expanded: false }"
                            class="{{ $inquiry->status === 'new' ? 'bg-green-50/60 dark:bg-green-900/10' : '' }}"
                        >
                            <td class="px-5 py-4">
                                <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $inquiry->name }}</p>
                                <p class="text-xs text-zinc-400">{{ $inquiry->email }}</p>
                            </td>
                            <td class="px-5 py-4 max-w-sm">
                                <button @click="expanded = !expanded" class="w-full text-left">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 flex-shrink-0 text-zinc-400 transition-transform" :class="expanded && 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        <p class="text-sm font-medium text-zinc-700 hover:text-green-700 dark:text-zinc-300 dark:hover:text-green-400">{{ $inquiry->subject }}</p>
                                    </div>
                                </button>
                                <div
                                    x-show="expanded"
                                    x-collapse
                                    class="mt-2 rounded-lg border border-zinc-100 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800"
                                >
                                    <p class="whitespace-pre-wrap text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $inquiry->message }}</p>
                                    <div class="mt-3 border-t border-zinc-200 pt-2 dark:border-zinc-700">
                                        <a
                                            href="mailto:{{ $inquiry->email }}?subject=Re: {{ urlencode($inquiry->subject) }}"
                                            class="inline-flex items-center gap-1.5 text-xs font-medium text-green-600 hover:underline dark:text-green-400"
                                        >
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Reply via email
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-xs text-zinc-600 dark:text-zinc-400">
                                    @if($inquiry->chapter === 'nigeria') 🇳🇬 Nigeria
                                    @elseif($inquiry->chapter === 'kenya') 🇰🇪 Kenya
                                    @else General @endif
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($inquiry->status === 'new')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/40 dark:text-green-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        New
                                    </span>
                                @elseif($inquiry->status === 'read')
                                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Read</span>
                                @else
                                    <span class="inline-flex rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-600 dark:bg-zinc-700 dark:text-zinc-400">Responded</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-zinc-500">{{ $inquiry->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    @if($inquiry->status === 'new')
                                        <button wire:click="markRead({{ $inquiry->id }})" class="rounded px-2 py-1 text-xs text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20">Mark Read</button>
                                    @endif
                                    @if(in_array($inquiry->status, ['new', 'read']))
                                        <button wire:click="markResponded({{ $inquiry->id }})" class="rounded px-2 py-1 text-xs text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20">Responded</button>
                                    @endif
                                    <button wire:click="delete({{ $inquiry->id }})"
                                        wire:confirm="Delete this inquiry?"
                                        class="rounded px-2 py-1 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-5 py-3">{{ $inquiries->links() }}</div>
        @endif
    </div>
</div>
