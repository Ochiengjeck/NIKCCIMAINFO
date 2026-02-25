<x-layouts::admin.sidebar :title="$title ?? 'NiKCCIMA Backoffice'">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::admin.sidebar>
