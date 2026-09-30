@extends('layouts.master')

@use('App\Scoring\Points')
@php
    $rows = collect($result['rows'])->sortBy(fn ($row) => [$row['rank'] ?? PHP_INT_MAX, $row['candidate']->number]);
    $incomplete = $rows->where('complete', false)->isNotEmpty();
    $tied = $rows->where('tied', true)->groupBy('rank')->sortKeys();
    $expected = (int) config('pageant.finalists');
    // Until finalists are saved, the form starts from the current top of the ranking.
    $checked = old('finalists', $finalists->isNotEmpty() ? $finalists->all() : $result['suggested']);
@endphp

@section('title', "Round 1 · {$label}")

@section('content')
    <x-results-header title="Round 1 results" eyebrow="Overall and finalists" :print="route('results.round1.pdf', $division)" />

    <p class="-mt-2 mb-4 max-w-prose text-sm text-ink-2">
        Each column is the segment's average across its judges. The total adds those averages up, out of 100.
    </p>

    <div data-refresh-region class="mb-4 flex flex-col gap-2 text-sm">
        @if ($incomplete)
            <p class="text-ink-2"><i class="fa-solid fa-hourglass-half" aria-hidden="true"></i> Some segments have no scores yet (shown as –), so totals and ranks will change.</p>
        @endif
        @if ($tied->isNotEmpty())
            <p @if ($result['cutoff_tie']) role="alert" @endif
                class="{{ $result['cutoff_tie'] ? 'rounded-md bg-danger-soft px-3 py-2 font-medium text-danger' : 'text-ink-2' }}">
                Tied{{ $incomplete ? ' for now' : '' }}:
                {{ $tied->map(fn ($group, $rank) => "rank {$rank}, ".$group->map(fn ($r) => 'No. '.$r['candidate']->number)->join(' and '))->join('; ') }}.
                @if ($result['cutoff_tie'])
                    One tie crosses the top {{ $expected }} cut, so the board decides who goes through.
                @endif
            </p>
        @endif
    </div>

    <x-table-card>
        @include('admin.results.tables.round1', ['print' => false])
    </x-table-card>

    <section aria-labelledby="finalists-heading" class="card mt-8 max-w-2xl">
        <form method="POST" action="{{ route('results.finalists', $division) }}">
            @csrf
            @method('PUT')
            <div class="p-5 sm:p-6">
                <h2 id="finalists-heading" class="text-lg font-semibold">Top {{ $expected }} finalists</h2>
                <p class="mt-1 text-sm text-ink-2">
                    Only saved finalists appear on the Round 2 score sheet.
                    @if ($finalists->isEmpty())
                        Nothing saved yet: the ticks below follow the current ranking.
                    @else
                        {{ $finalists->count() }} saved.
                    @endif
                </p>
                @if ($result['cutoff_tie'])
                    <p role="alert" class="mt-3 rounded-md bg-danger-soft px-3 py-2 text-sm font-medium text-danger">
                        Candidates are tied for the last finalist spot. Untick the ones the board leaves out, then save.
                    </p>
                @endif
                @error('finalists')
                    <p class="field-error">{{ $message }}</p>
                @enderror

                <ul class="mt-4 grid gap-2 sm:grid-cols-2">
                    @foreach ($rows as $row)
                        @php $candidate = $row['candidate']; @endphp
                        <li>
                            <label class="flex min-h-12 cursor-pointer items-center gap-3 rounded-md border border-line-strong px-3 has-checked:border-accent has-checked:bg-accent-soft">
                                <input type="checkbox" name="finalists[]" value="{{ $candidate->id }}" @checked(in_array($candidate->id, $checked)) class="size-5 accent-accent">
                                <span class="font-semibold tabular-nums">No. {{ $candidate->number }}</span>
                                <span class="ml-auto text-sm text-ink-2 tabular-nums">
                                    @if ($row['rank'])
                                        Rank {{ $row['rank'] }} · {{ Points::format($row['total']) }}
                                    @else
                                        Not scored
                                    @endif
                                </span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="flex justify-end border-t border-line px-5 py-4 sm:px-6">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-medal" aria-hidden="true"></i> Save finalists
                </button>
            </div>
        </form>
    </section>

    @if ($announcement->isNotEmpty())
        <section aria-labelledby="announcement-heading" class="card mt-6 max-w-2xl p-5 sm:p-6">
            <h2 id="announcement-heading" class="text-lg font-semibold">Announcement order</h2>
            <p class="mt-1 text-sm text-ink-2">
                The emcee calls the saved finalists in this random order. It never matches the ranking or the numbers, and it stays the same on every reprint until you reshuffle.
            </p>
            <ol class="mt-4 flex flex-wrap gap-2">
                @foreach ($announcement as $candidate)
                    <li class="inline-flex min-h-10 items-center gap-2 rounded-md border border-line px-3 tabular-nums">
                        <span class="text-sm text-ink-2">{{ $loop->iteration }}.</span>
                        <span class="font-semibold">No. {{ $candidate->number }}</span>
                    </li>
                @endforeach
            </ol>
            <div class="mt-5 flex flex-wrap gap-2">
                <a href="{{ route('announcement.pdf') }}" target="_blank" rel="noopener" class="btn btn-secondary">
                    <i class="fa-solid fa-print" aria-hidden="true"></i> Print announcement sheet
                </a>
                <form method="POST" action="{{ route('results.finalists.shuffle', $division) }}"
                    data-confirm="Draw a new call order for the {{ $label }} top 5? Any announcement sheet already printed will no longer match.">
                    @csrf
                    <button type="submit" class="btn btn-ghost">
                        <i class="fa-solid fa-shuffle" aria-hidden="true"></i> Reshuffle
                    </button>
                </form>
            </div>
        </section>
    @endif
@endsection
