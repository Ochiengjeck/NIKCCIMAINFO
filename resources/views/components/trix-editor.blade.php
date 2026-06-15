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
    {{ $attributes->merge(['class' => 'trix-field']) }}
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
        class="trix-content"
    ></trix-editor>
</div>
