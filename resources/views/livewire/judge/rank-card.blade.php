{{--
    One candidate on a ranked sheet: a button per rank, 1 to the number of candidates in the division.
    A rank another candidate holds shows their number; tapping it moves the rank here (ScoreSheet::rank()).
--}}
@php
    $limit = $this->candidates[$candidate->division]->count();
    $given = $state['total'] === null ? null : (int) $state['total'];
    $held = $holders[$candidate->division];
@endphp

<article id="card-{{ $candidate->id }}" wire:key="card-{{ $candidate->id }}" aria-labelledby="card-{{ $candidate->id }}-title"
    class="card flex scroll-mt-32 overflow-hidden {{ $state['invalid'] ? 'border-danger' : '' }}">
    <x-candidate-photo :division="$candidate->division" :number="$candidate->number" class="w-28 shrink-0 self-start sm:w-36" />

    <div class="flex min-w-0 flex-1 flex-col gap-3 p-3 sm:p-4">
        <div class="flex items-start justify-between gap-2">
            <h3 id="card-{{ $candidate->id }}-title" class="leading-tight">
                <span class="block text-sm font-medium text-ink-2">{{ $candidate->divisionLabel() }}</span>
                <span class="block text-xl font-semibold tabular-nums">No. {{ $candidate->number }}</span>
            </h3>
            @if ($state['invalid'])
                <span class="inline-flex shrink-0 items-center gap-1 text-sm font-medium text-danger"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> Shares rank {{ $given }}</span>
            @elseif ($state['complete'])
                <span class="inline-flex shrink-0 items-center gap-1 text-sm font-medium text-success-ink"><i class="fa-solid fa-check" aria-hidden="true"></i> {{ $ordinal($given) }}</span>
            @endif
        </div>

        <div role="group" aria-label="Rank for {{ $candidate->shortName() }}">
            <div class="grid w-fit grid-cols-3 gap-2">
                @for ($r = 1; $r <= $limit; $r++)
                    @php
                        $holder = $held[$r] ?? null;
                        $mine = $given === $r;
                        $taken = $holder && $holder->id !== $candidate->id;
                    @endphp
                    <button type="button" wire:click="rank({{ $candidate->id }}, {{ $r }})" aria-pressed="{{ $mine ? 'true' : 'false' }}"
                        @if ($taken) aria-label="{{ $r }}, now with No. {{ $holder->number }}" @endif
                        @disabled($this->locked)
                        class="flex size-12 cursor-pointer flex-col items-center justify-center rounded-md border text-lg leading-none font-semibold tabular-nums transition-colors disabled:cursor-default
                            {{ $mine ? 'border-accent bg-accent-soft text-accent-soft-ink' : ($taken ? 'border-line bg-surface-2 text-ink-2' : 'border-line-strong bg-surface text-ink enabled:hover:bg-surface-2') }}">
                        {{ $r }}
                        @if ($taken)
                            <span class="mt-1 text-[11px] font-medium" aria-hidden="true">No. {{ $holder->number }}</span>
                        @endif
                    </button>
                @endfor
            </div>
        </div>
    </div>
</article>
