@extends('layouts.master')

@section('title', 'Reset scores')

@php
    $count = fn (int $n, string $word) => $n.' '.Str::plural($word, $n);
    $summary = fn (int $entries, int $locks) => $entries || $locks
        ? $count($entries, 'score').' · '.$count($locks, 'lock-in')
        : 'No scores saved';
@endphp

@section('content')
    {{-- Clears rehearsal and dry-run scores without touching candidates or accounts. --}}
    <x-page-header title="Reset scores" />

    <p class="-mt-3 mb-6 max-w-prose text-sm text-ink-2">
        For clearing a rehearsal or dry run. A reset deletes the scores of every judge in both divisions,
        and the segment's lock-ins, so judges can score it again while it is open. It can't be undone.
    </p>

    <x-flash />

    <div class="flex flex-col gap-6">
        @foreach ($groups as $key => $data)
            @php $round = $data['round']; @endphp
            <section aria-labelledby="round-{{ $key }}" class="card overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-2 border-b border-line bg-surface-2 px-5 py-3">
                    <div>
                        <h2 id="round-{{ $key }}" class="font-semibold">{{ $data['label'] }}</h2>
                        <p class="text-sm text-ink-2">{{ $summary($data['entries'], $data['locks']) }}</p>
                    </div>
                    @if ($round)
                        <form method="POST" action="{{ route('reset.round', $round) }}"
                            data-confirm="Delete every {{ $data['label'] }} score and lock-in, from every judge in both divisions?{{ $round === 1 ? ' Saved finalists stay.' : '' }} This can't be undone.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" @disabled(! $data['entries'] && ! $data['locks'])>
                                <i class="fa-solid fa-rotate-left" aria-hidden="true"></i> Reset {{ $data['label'] }}
                            </button>
                        </form>
                    @endif
                </div>

                <ul class="divide-y divide-line">
                    @foreach ($data['segments'] as $row)
                        @php
                            $segment = $row['segment'];
                            $empty = ! $row['entries'] && ! $row['locks'];
                        @endphp
                        <li class="px-5 py-4">
                            <div class="flex flex-wrap items-start justify-between gap-x-6 gap-y-2">
                                <div>
                                    <p class="font-semibold">{{ $segment->label }}</p>
                                    <p class="text-sm text-ink-2">{{ $summary($row['entries'], $row['locks']) }}</p>
                                </div>
                                <form method="POST" action="{{ route('reset.segment', $segment->key) }}"
                                    data-confirm="Delete every {{ $segment->short }} score and lock-in, from every judge in both divisions? This can't be undone.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary" @disabled($empty)>
                                        Reset segment<span class="sr-only"> {{ $segment->short }}</span>
                                    </button>
                                </form>
                            </div>

                            <ul class="mt-3 flex flex-col gap-1 sm:pl-4">
                                @foreach ($segment->criteria as $key => $criterion)
                                    @php $entries = $row['criteria'][$key]; @endphp
                                    <li class="flex items-center justify-between gap-4 rounded-md border border-line py-1 pr-1 pl-3 text-sm">
                                        <span class="min-w-0">
                                            {{ $criterion['label'] }}
                                            <span class="text-ink-2">· {{ $entries ? $count($entries, 'score') : 'none saved' }}</span>
                                        </span>
                                        <form method="POST" action="{{ route('reset.criterion', [$segment->key, $key]) }}"
                                            data-confirm="Delete every {{ $segment->short }} score for {{ $criterion['label'] }}, from every judge in both divisions? {{ $segment->short }} lock-ins are cleared too. This can't be undone.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost min-h-8 shrink-0 px-2.5 text-xs" @disabled(! $entries)>
                                                Reset<span class="sr-only"> {{ $segment->short }}: {{ $criterion['label'] }}</span>
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach

        <section aria-labelledby="start-over" class="card px-5 py-4">
            <div class="flex flex-wrap items-center justify-between gap-x-6 gap-y-3">
                <div class="max-w-prose">
                    <h2 id="start-over" class="font-semibold">Start over</h2>
                    <p class="text-sm text-ink-2">
                        Deletes every score and lock-in, unticks the finalists, clears the announcement order and closes every segment.
                        Candidates and accounts stay. The same as <code class="text-ink">php artisan scores:clear</code>.
                    </p>
                </div>
                <form method="POST" action="{{ route('reset.everything') }}"
                    data-confirm="Delete every score, lock-in and finalist, and close every segment? This can't be undone.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" @disabled(! $anything)>
                        <i class="fa-solid fa-trash" aria-hidden="true"></i> Clear everything
                    </button>
                </form>
            </div>
        </section>
    </div>
@endsection
