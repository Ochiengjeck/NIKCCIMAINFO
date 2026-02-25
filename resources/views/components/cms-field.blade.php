@props([
    'fieldKey',
    'type' => 'text',
    'value' => null,
])

<flux:field>
    <div class="mb-1.5 flex items-center gap-2">
        <flux:label>{{ ucwords(str_replace('_', ' ', $fieldKey)) }}</flux:label>
        <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-[10px] text-zinc-400 dark:bg-zinc-800">{{ $fieldKey }}</code>
        @if($type === 'icon')
            <span class="rounded bg-zinc-50 px-1.5 py-0.5 text-[10px] text-zinc-400 dark:bg-zinc-800">Emoji or icon code</span>
        @endif
    </div>

    <flux:input
        wire:model="sections.{{ $fieldKey }}"
        type="{{ $type === 'tel' ? 'tel' : ($type === 'email' ? 'email' : ($type === 'url' ? 'url' : 'text')) }}"
        @if($type === 'url')
            placeholder="https://"
        @endif
    />

    @if($type === 'url' && $fieldKey === 'map_embed_url' && !empty($value))
        <div class="mt-2 overflow-hidden rounded-lg">
            <iframe src="{{ $value }}" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    @endif
</flux:field>
