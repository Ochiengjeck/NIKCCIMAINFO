<div x-data="{ open: @entangle('open') }">

    {{-- Floating Toggle Button --}}
    <button wire:click="toggle"
        class="fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-green-700 text-white shadow-xl ring-4 ring-green-700/20 transition hover:bg-green-800 focus:outline-none"
        aria-label="Toggle chat assistant">
        <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3-3-3z"/>
        </svg>
        <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Chat Panel --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="fixed bottom-24 right-6 z-50 flex w-80 flex-col overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-900 shadow-2xl sm:w-96"
        style="max-height: 500px;">

        {{-- Header --}}
        <div class="flex items-center gap-3 bg-gradient-to-r from-green-800 to-green-700 px-4 py-3 text-white">
            <div class="relative">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-green-600 text-xs font-bold tracking-tight">NK</div>
                <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-green-700 bg-green-400"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold">NiKCCIMA Assistant</p>
                <p class="text-xs text-green-200">Trade & membership queries</p>
            </div>
            <button wire:click="toggle" class="rounded-lg p-1 text-green-200 hover:bg-green-600/50 hover:text-white transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Messages --}}
        <div class="flex-1 space-y-3 overflow-y-auto px-4 py-4"
            style="max-height: 320px;"
            x-data
            x-init="$el.scrollTop = $el.scrollHeight"
            x-on:livewire:update.window="setTimeout(() => $el.scrollTop = $el.scrollHeight, 50)">
            @foreach($messages as $msg)
                <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[78%] rounded-2xl px-3.5 py-2.5 text-sm leading-relaxed
                        {{ $msg['role'] === 'user'
                            ? 'rounded-br-sm bg-green-700 text-white'
                            : 'rounded-bl-sm bg-zinc-800 text-zinc-200' }}">
                        <p>{{ $msg['text'] }}</p>
                        <p class="mt-1 text-right text-[10px] opacity-50">{{ $msg['time'] }}</p>
                    </div>
                </div>
            @endforeach

            {{-- Typing indicator --}}
            <div wire:loading wire:target="send" class="flex justify-start">
                <div class="rounded-2xl rounded-bl-sm bg-zinc-800 px-4 py-3">
                    <span class="flex items-center gap-1">
                        <span class="h-2 w-2 animate-bounce rounded-full bg-zinc-500" style="animation-delay:0ms"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-zinc-500" style="animation-delay:150ms"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-zinc-500" style="animation-delay:300ms"></span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Input --}}
        <div class="border-t border-zinc-800 px-3 py-3">
            <div class="flex items-center gap-2">
                <input wire:model="input"
                    wire:keydown.enter="send"
                    type="text"
                    placeholder="Ask about membership, trade..."
                    class="flex-1 rounded-xl border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-sm text-zinc-100 placeholder:text-zinc-500 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20">
                <button wire:click="send"
                    wire:loading.attr="disabled"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-700 text-white transition hover:bg-green-800 disabled:opacity-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
