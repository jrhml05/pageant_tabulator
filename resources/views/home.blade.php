@extends('layouts.master')

@section('title', 'Scoring control')

@section('content')
    {{-- The tabulator's run sheet: open the segment on stage, watch the locks come in, close it. --}}
    <x-page-header title="Scoring control" />

    <div class="-mt-3 mb-6 flex flex-wrap items-end justify-between gap-x-6 gap-y-2">
        <p class="max-w-prose text-sm text-ink-2">
            Open a segment when it starts on stage; that panel's tablets switch to it within a few seconds.
            Close it once every judge has locked in.
            <span class="mt-2 block">
                <a href="{{ route('candidates.index') }}" class="font-medium text-ink underline-offset-4 hover:underline">
                    @foreach (config('pageant.divisions') as $division => $label)
                        {{ (int) ($data['counts'][$division]->total ?? 0) }} {{ $division === 'mr' ? 'Mr.' : 'Ms.' }}{{ $loop->last ? '' : ' and' }}
                    @endforeach
                    candidates</a>
                ·
                <a href="{{ route('judges.index') }}" class="font-medium text-ink underline-offset-4 hover:underline">{{ $data['judges']->count() }} {{ Str::plural('judge', $data['judges']->count()) }}</a>
            </span>
        </p>
        <p data-live-report class="text-sm text-ink-2">
            <span data-live-note role="status" class="font-medium text-ink empty:hidden"></span>
            <span data-live-time>Locks refresh automatically.</span>
        </p>
    </div>

    <x-flash />

    @if ($data['unassigned']->isNotEmpty())
        <div role="alert" class="mb-5 rounded-md bg-danger-soft px-4 py-3 text-sm font-medium text-danger">
            {{ $data['unassigned']->pluck('name')->join(', ', ' and ') }}
            {{ $data['unassigned']->count() === 1 ? 'is' : 'are' }} not on a panel and can't score.
            <a href="{{ route('judges.index') }}" class="underline underline-offset-4">Assign a panel</a>
        </div>
    @endif

    <div class="flex flex-col gap-6" data-refresh-region>
        @foreach ($data['groups'] as $groupLabel => $rows)
            <section aria-labelledby="group-{{ $loop->index }}" class="card overflow-hidden">
                <h2 id="group-{{ $loop->index }}" class="border-b border-line bg-surface-2 px-5 py-3 font-semibold">{{ $groupLabel }}</h2>
                <ul class="divide-y divide-line">
                    @foreach ($rows as $row)
                        @php
                            $segment = $row['segment'];
                            $judges = $row['judges'];
                            $lockedCount = $judges->whereIn('id', $row['locked'])->count();
                            $allLocked = $judges->isNotEmpty() && $lockedCount === $judges->count();
                        @endphp
                        <li class="px-5 py-4">
                            <div class="flex flex-wrap items-start justify-between gap-x-6 gap-y-3">
                                <div>
                                    <p class="font-semibold">{{ $segment->label }}</p>
                                    <p class="text-sm text-ink-2">
                                        {{ \App\Scoring\Points::plain($segment->maxPoints()) }} points
                                        @if ($segment->forFinalistsOnly())
                                            · finalists only
                                        @endif
                                    </p>
                                    <p class="mt-1.5">
                                        @if ($row['open'])
                                            <span class="inline-flex items-center gap-1.5 rounded bg-accent-soft px-2 py-0.5 text-xs font-medium text-accent-soft-ink">
                                                <i class="fa-solid fa-circle-play" aria-hidden="true"></i> Open to judges
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded bg-surface-2 px-2 py-0.5 text-xs font-medium text-ink-2">
                                                <i class="fa-solid fa-circle-stop" aria-hidden="true"></i> Closed
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    @foreach (config('pageant.divisions') as $division => $label)
                                        <a href="{{ route('results.segment', [$division, $segment->key]) }}" class="btn btn-ghost">
                                            {{ $division === 'mr' ? 'Mr.' : 'Ms.' }} results
                                        </a>
                                    @endforeach
                                    <form method="POST" action="{{ route($row['open'] ? 'segments.close' : 'segments.open', $segment->key) }}"
                                        @if ($row['open'] && ! $allLocked) data-confirm="Not every judge has locked in {{ $segment->short }}. Close it anyway? Judges can't change its scores once it's closed." @endif>
                                        @csrf
                                        <button type="submit" class="btn btn-secondary min-w-24">
                                            <i class="fa-solid {{ $row['open'] ? 'fa-stop' : 'fa-play' }}" aria-hidden="true"></i>
                                            {{ $row['open'] ? 'Close' : 'Open' }}<span class="sr-only"> {{ $segment->short }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="mt-3">
                                <p class="text-sm font-medium {{ $allLocked ? 'text-success-ink' : '' }}">
                                    <i class="fa-solid {{ $allLocked ? 'fa-lock' : 'fa-lock-open text-ink-2' }}" aria-hidden="true"></i>
                                    @if ($judges->isEmpty())
                                        No judges on the {{ $segment->panelLabel() }} panel
                                    @elseif ($allLocked)
                                        All {{ $judges->count() }} judges locked in
                                    @else
                                        {{ $lockedCount }} of {{ $judges->count() }} judges locked in
                                    @endif
                                </p>
                                <ul class="mt-2 flex flex-wrap gap-1.5">
                                    @foreach ($judges as $judge)
                                        @php $locked = $row['locked']->contains($judge->id); @endphp
                                        <li class="inline-flex min-h-9 items-center whitespace-nowrap gap-1.5 rounded-md border border-line py-0.5 pl-2.5 text-sm {{ $locked ? 'pr-1' : 'pr-2.5 text-ink-2' }}">
                                            <i class="fa-solid {{ $locked ? 'fa-lock' : 'fa-pen' }} text-xs" aria-hidden="true"></i>
                                            <span>{{ $judge->name }}<span class="sr-only">{{ $locked ? ', locked in' : ', still scoring' }}</span></span>
                                            @if ($locked)
                                                <form method="POST" action="{{ route('segments.unlock', [$segment->key, $judge->id]) }}"
                                                    data-confirm="Unlock {{ $judge->name }}'s {{ $segment->short }} sheet? They can change their scores again while the segment is open.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-ghost min-h-8 px-2 text-xs">Unlock<span class="sr-only"> {{ $judge->name }}</span></button>
                                                </form>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach
    </div>
@endsection
