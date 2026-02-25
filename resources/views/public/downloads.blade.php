<x-layouts::website :title="'Download Centre — NiKCCIMA'">

    {{-- Hero --}}
    <section class="relative overflow-hidden py-24 text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-green-900 to-green-800">
            <svg class="absolute inset-0 h-full w-full opacity-10" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dp" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dp)"/>
            </svg>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <span class="mb-4 inline-flex rounded-full bg-red-600 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-white">Resources</span>
            <h1 class="font-serif text-4xl font-bold lg:text-6xl">Download Centre</h1>
            <p class="mt-4 max-w-2xl text-lg text-green-200">Trade reports, policy briefs, AfCFTA documentation, and official NiKCCIMA publications.</p>
        </div>
    </section>

    {{-- File List --}}
    <section class="py-20 bg-white dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if($files->isEmpty())
                <div class="flex h-64 flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900">
                    <svg class="mb-4 h-10 w-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">No documents available for download yet.</p>
                </div>
            @else
                <div class="overflow-hidden rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Document</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Type</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Size</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Date</th>
                                <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Download</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @foreach($files as $file)
                                @php
                                    $ext = strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
                                    $iconBg = match(true) {
                                        $ext === 'pdf'               => 'bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400',
                                        in_array($ext, ['doc','docx'])  => 'bg-blue-50 dark:bg-blue-900/20 text-blue-500 dark:text-blue-400',
                                        in_array($ext, ['xls','xlsx'])  => 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400',
                                        in_array($ext, ['ppt','pptx'])  => 'bg-orange-50 dark:bg-orange-900/20 text-orange-500 dark:text-orange-400',
                                        default                      => 'bg-zinc-50 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400',
                                    };
                                    $extBadge = match(true) {
                                        $ext === 'pdf'               => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        in_array($ext, ['doc','docx'])  => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        in_array($ext, ['xls','xlsx'])  => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                        in_array($ext, ['ppt','pptx'])  => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                        default                      => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400',
                                    };
                                @endphp
                                <tr class="transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $iconBg }}">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-medium text-zinc-900 dark:text-white">
                                                    {{ $file->alt_text ?? $file->original_filename }}
                                                </p>
                                                @if($file->alt_text && $file->alt_text !== $file->original_filename)
                                                    <p class="truncate text-xs text-zinc-400 dark:text-zinc-500">{{ $file->original_filename }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase {{ $extBadge }}">
                                            {{ $ext ?: '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">{{ $file->humanSize() }}</td>
                                    <td class="px-6 py-4 text-sm text-zinc-500 dark:text-zinc-400">{{ $file->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ $file->url() }}" target="_blank" download
                                           class="inline-flex items-center gap-1.5 rounded-lg border border-green-200 bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700 transition hover:bg-green-100 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/40">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="border-t border-zinc-100 dark:border-zinc-800 px-6 py-4">
                        {{ $files->links() }}
                    </div>
                </div>
            @endif

        </div>
    </section>

</x-layouts::website>
