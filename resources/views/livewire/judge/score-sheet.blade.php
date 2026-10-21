@use('App\Scoring\Points')
@php
    $segment = $this->segment;
    $locked = $this->locked;
    $remaining = $progress['total'] - $progress['scored'];
    $pairs = $viewMode === 'pairs';
    $divisionLabel = $pairs ? null : config("pageant.divisions.{$viewMode}");
    $stateOf = fn ($c) => $states[$c->id]['invalid'] ? 'invalid' : ($states[$c->id]['complete'] ? 'done' : ($states[$c->id]['filled'] ? 'partial' : 'empty'));
    // One chip per pair, or per candidate when a single division is shown.
    $chips = $pairs
        ? collect($rows)->map(fn ($row) => ['href' => "#pair-{$row['number']}", 'number' => $row['number'], 'states' => collect([$row['ms'], $row['mr']])->filter()->map($stateOf)])
        : $visible->map(fn ($c) => ['href' => "#card-{$c->id}", 'number' => $c->number, 'states' => collect([$stateOf($c)])]);
    $views = ['pairs' => 'Side by side'] + collect(config('pageant.divisions'))->mapWithKeys(fn ($label, $d) => [$d => ($d === 'mr' ? 'Mr.' : 'Ms.').' only'])->all();
@endphp

<div>
    <div class="mb-5 flex flex-wrap items-end justify-between gap-x-6 gap-y-3">
        <div>
            <p class="text-sm font-medium text-ink-2">{{ $segment->roundLabel() }} · {{ $segment->panelLabel() }}</p>
            <h1 class="text-2xl font-semibold tracking-tight text-balance">{{ $segment->label }}</h1>
            <p class="mt-1 max-w-prose text-ink-2">
                @if ($locked)
                    Your scores are locked in and with the tabulator.
                @elseif ($pairs)
                    Scores save as you type. Ms. and Mr. candidates with the same number sit side by side.
                @else
                    Scores save as you type. Showing {{ $divisionLabel }} candidates only.
                @endif
            </p>
        </div>

        <div role="group" aria-label="Show candidates" class="inline-flex gap-1 rounded-lg border border-line bg-surface p-1">
            @foreach ($views as $mode => $text)
                <button type="button" wire:click="showView('{{ $mode }}')" aria-pressed="{{ $viewMode === $mode ? 'true' : 'false' }}"
                    class="flex min-h-11 cursor-pointer items-center gap-2 rounded-md px-4 font-medium whitespace-nowrap transition-colors {{ $viewMode === $mode ? 'bg-accent-soft text-accent-soft-ink' : 'text-ink-2 hover:bg-surface-2 hover:text-ink' }}">
                    @if ($mode === 'pairs')
                        <i class="fa-solid fa-table-columns" aria-hidden="true"></i>
                    @endif
                    {{ $text }}
                </button>
            @endforeach
        </div>
    </div>

    @if ($notice)
        <p role="alert" class="mb-4 rounded-md bg-danger-soft px-4 py-3 font-medium text-danger">{{ $notice }}</p>
    @endif

    <p wire:offline role="alert" class="mb-4 rounded-md bg-danger-soft px-4 py-3 font-medium text-danger">
        Lost the connection to the tabulator. Keep this page open; entries made now may not save until it reconnects.
    </p>

    @if ($locked)
        <div role="status" class="mb-5 flex items-start gap-3 rounded-md bg-success-soft px-4 py-3 text-success-ink">
            <i class="fa-solid fa-lock mt-1" aria-hidden="true"></i>
            <p class="font-medium">Locked in. The next segment appears here when the tabulator opens it. To change a score, ask the tabulator to unlock your sheet.</p>
        </div>
    @endif

    @if ($visible->isEmpty())
        <div class="card px-5 py-8 text-center">
            <p class="font-medium">No {{ $divisionLabel ? "{$divisionLabel} " : '' }}candidates to score yet.</p>
            <p class="mt-1 text-ink-2">
                {{ $segment->forFinalistsOnly() ? "The tabulator hasn't saved the finalists yet." : 'The tabulator has not added candidates yet.' }}
                Reload this page once they have.
            </p>
        </div>
    @else
        {{-- Jump strip: which pairs (or candidates) still need scores. --}}
        @unless ($segment->forFinalistsOnly())
            <nav aria-label="{{ $pairs ? 'Jump to a pair' : 'Jump to a candidate' }}" class="sticky top-16 z-10 -mx-4 mb-5 border-b border-line bg-canvas px-4 py-2 sm:-mx-6 sm:px-6">
                <ul class="flex flex-wrap gap-1.5">
                    @foreach ($chips as $chip)
                        @php
                            $cardStates = $chip['states'];
                            $state = $cardStates->contains('invalid') ? 'invalid' : ($cardStates->every(fn ($s) => $s === 'done') ? 'done' : ($cardStates->contains(fn ($s) => $s !== 'empty') ? 'partial' : 'empty'));
                            $label = ['invalid' => 'has an error', 'done' => 'scored', 'partial' => 'partly scored', 'empty' => 'not scored'][$state];
                        @endphp
                        <li>
                            <a href="{{ $chip['href'] }}"
                                class="flex min-h-11 min-w-11 items-center justify-center gap-1 rounded-md border px-2.5 font-semibold tabular-nums transition-colors
                                    {{ match ($state) {
                                        'done' => 'border-transparent bg-accent-soft text-accent-soft-ink',
                                        'invalid' => 'border-danger bg-danger-soft text-danger',
                                        'partial' => 'border-line-strong bg-surface text-ink',
                                        default => 'border-line bg-surface text-ink-2',
                                    } }}">
                                @if ($state === 'done')
                                    <i class="fa-solid fa-check text-xs" aria-hidden="true"></i>
                                @elseif ($state === 'invalid')
                                    <i class="fa-solid fa-triangle-exclamation text-xs" aria-hidden="true"></i>
                                @endif
                                {{ $chip['number'] }}<span class="sr-only">, {{ $label }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        @endunless

        @if (! $pairs)
            <div class="grid gap-3 md:grid-cols-2">
                @foreach ($visible as $candidate)
                    @include('livewire.judge.card', ['candidate' => $candidate, 'state' => $states[$candidate->id]])
                @endforeach
            </div>
        @else
            <div class="flex flex-col gap-6">
                @foreach ($rows as $row)
                    <section @if ($row['number']) id="pair-{{ $row['number'] }}" aria-label="Pair No. {{ $row['number'] }}" @endif class="scroll-mt-32">
                        @if ($row['number'])
                            <h2 class="mb-2 text-lg font-semibold tabular-nums">No. {{ $row['number'] }}</h2>
                        @endif
                        <div class="grid gap-3 md:grid-cols-2">
                            @foreach (config('pageant.divisions') as $division => $divisionName)
                                @if ($candidate = $row[$division])
                                    @include('livewire.judge.card', ['candidate' => $candidate, 'state' => $states[$candidate->id]])
                                @else
                                    <div class="hidden rounded-lg border border-dashed border-line px-4 py-6 text-center text-sm text-ink-2 md:grid md:place-items-center">
                                        No {{ $divisionName }} candidate {{ $row['number'] ? "No. {$row['number']}" : '' }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        @endif
    @endif

    <x-judge.action-bar>
        <div class="mr-auto flex flex-col">
            <p class="font-semibold tabular-nums">
                {{ $progress['scored'] }} of {{ $progress['total'] }} scored
            </p>
            <p class="text-sm text-ink-2" aria-live="polite">
                <span wire:loading.remove>{{ $locked ? 'Locked in' : 'All changes saved' }}</span>
                <span wire:loading>Saving…</span>
            </p>
        </div>

        @if ($locked)
            <p class="inline-flex items-center gap-2 font-medium text-success-ink"><i class="fa-solid fa-lock" aria-hidden="true"></i> Locked in</p>
        @elseif ($progress['total'] > 0)
            @if ($firstUnscored)
                <a href="#card-{{ $firstUnscored->id }}" class="btn btn-secondary btn-lg"
                    x-on:click="$nextTick(() => document.querySelector('#card-{{ $firstUnscored->id }} input')?.focus({ preventScroll: true }))">
                    Next to score: {{ $firstUnscored->shortName() }}
                </a>
            @elseif ($unscoredElsewhere->isNotEmpty())
                @php $otherDivision = $unscoredElsewhere->keys()->first(); @endphp
                <button type="button" class="btn btn-secondary btn-lg" wire:click="showView('{{ $otherDivision }}')">
                    Go to {{ $otherDivision === 'mr' ? 'Mr.' : 'Ms.' }} candidates ({{ $unscoredElsewhere[$otherDivision] }} left)
                </button>
            @endif
            <div x-data>
                <button type="button" class="btn btn-primary btn-lg" x-on:click="$refs.confirm.showModal()"
                    @disabled($remaining > 0 || $progress['invalid'] > 0)
                    @if ($remaining > 0 || $progress['invalid'] > 0) aria-describedby="lock-hint" @endif>
                    <i class="fa-solid fa-lock" aria-hidden="true"></i> Lock in scores
                </button>
                @if ($remaining > 0 || $progress['invalid'] > 0)
                    <span id="lock-hint" class="sr-only">Available once every candidate is scored.</span>
                @endif

                <dialog x-ref="confirm" aria-labelledby="confirm-title"
                    class="m-auto w-[min(28rem,calc(100vw-2rem))] rounded-lg border border-line bg-surface p-0 text-ink backdrop:bg-black/50">
                    <div class="p-6">
                        <h2 id="confirm-title" class="text-xl font-semibold">Lock in {{ $segment->short }}?</h2>
                        <p class="mt-2 text-ink-2">
                            You've scored all {{ $progress['total'] }} candidates. After locking in you can't change these scores unless the tabulator unlocks your sheet.
                        </p>
                    </div>
                    <div class="flex justify-end gap-2 border-t border-line px-6 py-4">
                        <button type="button" class="btn btn-secondary btn-lg" x-on:click="$refs.confirm.close()">Keep editing</button>
                        <button type="button" class="btn btn-primary btn-lg" wire:click="lock" wire:loading.attr="disabled" x-on:click="$refs.confirm.close()">
                            <i class="fa-solid fa-lock" aria-hidden="true"></i> Lock in
                        </button>
                    </div>
                </dialog>
            </div>
        @endif
    </x-judge.action-bar>
</div>
