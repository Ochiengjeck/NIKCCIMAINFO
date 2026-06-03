@props(['statePath', 'value' => ''])

@php $inputId = 'trix-input-'.\Illuminate\Support\Str::random(8); @endphp

{{--
    Trix rich-text editor bound to a Livewire string property.
    - wire:ignore keeps Livewire's DOM morphing away from Trix's internal DOM.
    - Initial HTML is loaded on trix-initialize (covers edit mode).
    - On every change the HTML is staged into Livewire WITHOUT a round-trip
      ($wire.set(..., false)) so it's present at save() with no per-keystroke chatter.
    - Pass wire:key (e.g. wire:key="event-desc-{{ $editingId }}") so switching records
      rebuilds the editor with the correct content.
--}}
<div
    wire:ignore
    {{ $attributes }}
    x-data="{
        html: @js($value),
        init() {
            const editor = this.$refs.editor;
            editor.addEventListener('trix-initialize', () => {
                if (this.html) {
                    editor.editor.loadHTML(this.html);
                }
            });
            editor.addEventListener('trix-change', () => {
                this.$wire.set('{{ $statePath }}', editor.value, false);
            });
        }
    }"
>
    <input id="{{ $inputId }}" type="hidden">
    <trix-editor
        input="{{ $inputId }}"
        x-ref="editor"
        class="trix-content min-h-[220px] rounded-lg border border-zinc-200 bg-white text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
    ></trix-editor>
</div>
