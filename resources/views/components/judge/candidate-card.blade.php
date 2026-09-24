@props(['division', 'record'])

@php $locked = (bool) $record->is_lock; @endphp

<article {{ $attributes->merge(['class' => 'card flex flex-col overflow-hidden']) }} aria-labelledby="candidate-{{ $division }}-{{ $record->candidate_id }}">
    {{-- Kept off the photo: the posters carry their own number and name artwork. --}}
    <div class="flex min-h-12 items-center justify-between gap-2 border-b border-line px-4">
        <h2 id="candidate-{{ $division }}-{{ $record->candidate_id }}" class="text-lg font-semibold tabular-nums">
            No. {{ $record->candidate_id }}
        </h2>
        @if ($locked)
            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-ink-2">
                <i class="fa-solid fa-lock" aria-hidden="true"></i> Locked
            </span>
        @endif
    </div>

    <x-candidate-photo :division="$division" :number="$record->candidate_id" />

    <div class="flex flex-1 flex-col gap-3 p-4">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="border-t border-line bg-surface-2 px-4 py-3">
            {{ $footer }}
        </div>
    @endisset
</article>
