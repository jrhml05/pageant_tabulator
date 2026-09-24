{{-- One criterion input. Bound straight to the Eloquent row (legacy_model_binding), saved on every change. --}}
@props(['index', 'field', 'label', 'max', 'record', 'readonly' => false])

@php
    $value = $record->$field;
    $invalid = ! $readonly && is_numeric($value) && ($value > $max || $value < 0);
    $id = "score-{$record->candidate_id}-{$field}";
@endphp

<div>
    <div class="flex items-baseline justify-between gap-2">
        <label for="{{ $id }}" class="label">{{ $label }}</label>
        <span id="{{ $id }}-max" class="text-xs text-ink-2 tabular-nums">out of {{ $max }}</span>
    </div>
    <input id="{{ $id }}" wire:model.live="records.{{ $index }}.{{ $field }}" type="number" inputmode="decimal"
        step="any" min="0" max="{{ $max }}" onfocus="this.select()"
        class="input mt-1 text-center text-lg font-semibold tabular-nums"
        aria-describedby="{{ $id }}-max{{ $invalid ? " {$id}-error" : '' }}"
        @if ($invalid) aria-invalid="true" @endif
        @disabled($readonly || $record->is_lock)>
    @if ($invalid)
        <p id="{{ $id }}-error" class="field-error">Enter a score from 0 to {{ $max }}.</p>
    @endif
</div>
