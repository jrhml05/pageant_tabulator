{{-- One candidate on the judge's sheet. $state comes from ScoreSheet::cardStates(). --}}
@php
    $entry = "c{$candidate->id}";
    $max = $segment->maxPoints();
@endphp

<article id="card-{{ $candidate->id }}" wire:key="card-{{ $candidate->id }}" aria-labelledby="card-{{ $candidate->id }}-title"
    class="card flex scroll-mt-32 overflow-hidden {{ $state['invalid'] ? 'border-danger' : '' }}">
    {{-- Kept beside the inputs, never under them: the posters carry their own number and name art. --}}
    <x-candidate-photo :division="$candidate->division" :number="$candidate->number" class="w-28 shrink-0 self-start sm:w-36" />

    <div class="flex min-w-0 flex-1 flex-col gap-3 p-3 sm:p-4">
        <div class="flex items-start justify-between gap-2">
            <h3 id="card-{{ $candidate->id }}-title" class="leading-tight">
                <span class="block text-sm font-medium text-ink-2">{{ $candidate->divisionLabel() }}</span>
                <span class="block text-xl font-semibold tabular-nums">No. {{ $candidate->number }}</span>
                @if ($candidate->school)
                    <span class="block text-sm text-ink-2">{{ $candidate->school }}</span>
                @endif
            </h3>
            @if ($state['invalid'])
                <span class="inline-flex shrink-0 items-center gap-1 text-sm font-medium text-danger"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> Check</span>
            @elseif ($state['complete'])
                <span class="inline-flex shrink-0 items-center gap-1 text-sm font-medium text-success-ink"><i class="fa-solid fa-check" aria-hidden="true"></i> Scored</span>
            @endif
        </div>

        @foreach ($segment->criteria as $key => $criterion)
            @php
                $id = "score-{$candidate->id}-{$key}";
                $invalid = App\Scoring\Points::parse($points[$entry][$key] ?? '', $criterion['max']) === false;
                $maxLabel = App\Scoring\Points::plain($criterion['max']);
            @endphp
            <div>
                <label for="{{ $id }}" class="flex items-baseline justify-between gap-2 text-sm font-medium">
                    <span>{{ $criterion['label'] }}</span>
                    <span class="shrink-0 font-normal text-ink-2 tabular-nums">of {{ $maxLabel }}</span>
                </label>
                <input id="{{ $id }}" type="text" inputmode="decimal" autocomplete="off" enterkeyhint="next" data-score-input
                    wire:model.live.debounce.300ms="points.{{ $entry }}.{{ $key }}"
                    class="input mt-1 min-h-12 text-center text-xl font-semibold tabular-nums"
                    @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
                    @disabled($this->locked)>
                @if ($invalid)
                    <p id="{{ $id }}-error" class="field-error">Enter 0 to {{ $maxLabel }}, up to 2 decimals.</p>
                @endif
            </div>
        @endforeach

        <p class="mt-auto flex items-baseline justify-between gap-2 border-t border-line pt-2 tabular-nums">
            <span class="font-medium">Total</span>
            <span><span class="text-xl font-semibold">{{ $state['total'] === null ? '0' : App\Scoring\Points::plain($state['total']) }}</span> <span class="text-sm text-ink-2">of {{ App\Scoring\Points::plain($max) }}</span></span>
        </p>
    </div>
</article>
