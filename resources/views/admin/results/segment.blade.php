@extends('layouts.master')

@use('App\Scoring\Tabulator')
@use('App\Scoring\TieBreak')
@php
    $segment = $result['segment'];
    $judges = $result['judges'];
    $base = route('results.segment', [$division, $segment->key]);
    $tabs = ['Overall' => $base] + $judges->values()->mapWithKeys(fn ($j, $i) => ['Judge '.($i + 1) => $base.'?judge='.($i + 1)])->all();
    $waiting = $judges->whereNotIn('id', $result['locked']);
    // Not scored by every judge: a lock-in from before they became a finalist doesn't cover them.
    $missing = collect($result['rows'])->filter(fn ($row) => $row['counted'] < $judges->count())->sortBy('candidate.number');
    $final = Tabulator::isFinal($result);
    // Ties the board settles on this page (Settle ties, below the table) get their own line once final.
    $ties = collect($result['ties']);
    $unsettled = $final ? $ties->whereNull('order') : collect();
    $tied = collect($result['rows'])->where('tied', true)->whereNotIn('rank', $unsettled->pluck('rank'))->sortBy('rank')->groupBy('rank');
    $ranked = $segment->isRanked();
@endphp

@section('title', "{$segment->short} · {$label}")

@section('content')
    <x-results-header
        :title="$judge ? $segment->short.': Judge '.$seat : $segment->label"
        :eyebrow="$segment->roundLabel().($judge ? ' · '.$judge->name : '')"
        :tabs="$tabs"
        :print="route('results.segment.pdf', [$division, $segment->key]).($judge ? '?judge='.$seat : '')"
        :print-label="$judge ? 'Print judge '.$seat.' sheet' : 'Print results'">
        @unless ($judge)
            <a href="{{ route('results.segment.winners', [$division, $segment->key]) }}" target="_blank" rel="noopener" class="btn btn-secondary">
                <i class="fa-solid fa-trophy" aria-hidden="true"></i> {{ $segment->forFinalistsOnly() ? 'Announcement of winners' : 'Print winner' }}
            </a>
        @endunless
    </x-results-header>

    <div data-refresh-region class="mb-4 flex flex-col gap-2 text-sm">
        @if ($judge)
            <p class="{{ $result['locked']->contains($judge->id) ? 'font-medium text-success-ink' : 'text-ink-2' }}">
                <i class="fa-solid {{ $result['locked']->contains($judge->id) ? 'fa-lock' : 'fa-lock-open' }}" aria-hidden="true"></i>
                {{ $judge->name }} {{ $result['locked']->contains($judge->id) ? 'has locked in this sheet.' : 'is still scoring.' }}
            </p>
        @elseif ($judges->isEmpty())
            <p class="text-danger">No judges are on the {{ $segment->panelLabel() }} panel. Assign some on the Judges page.</p>
        @else
            <p @if ($waiting->isEmpty() && $missing->isNotEmpty()) role="alert" @endif
                class="{{ $final ? 'font-medium text-success-ink' : ($waiting->isEmpty() ? 'rounded-md bg-danger-soft px-3 py-2 font-medium text-danger' : 'text-ink-2') }}">
                <i class="fa-solid {{ $final ? 'fa-lock' : ($waiting->isEmpty() ? 'fa-triangle-exclamation' : 'fa-lock-open') }}" aria-hidden="true"></i>
                @if ($waiting->isEmpty() && $missing->isNotEmpty())
                    All {{ $judges->count() }} judges locked in, but {{ $missing->map(fn ($row) => 'No. '.$row['candidate']->number)->join(', ', ' and ') }}
                    {{ $missing->count() === 1 ? "isn't" : "aren't" }} {{ $ranked ? 'ranked' : 'scored' }} by every judge, so {{ $missing->count() === 1 ? 'that '.($ranked ? 'total' : 'average').' leaves judges out' : 'those '.($ranked ? 'totals' : 'averages').' leave judges out' }}.
                    Unlock the judges' sheets on Scoring control so they can {{ $ranked ? 'rank' : 'score' }}.
                @elseif ($waiting->isEmpty())
                    All {{ $judges->count() }} judges locked in. These results are final unless a sheet is unlocked.
                @else
                    {{ $judges->count() - $waiting->count() }} of {{ $judges->count() }} judges locked in. Waiting on {{ $waiting->pluck('name')->join(', ', ' and ') }}.
                    {{ $ranked ? "A judge's rank counts once they give one." : "A judge's total counts toward the average once every criterion has points." }}
                @endif
            </p>
            @if ($ranked)
                <p class="text-ink-2">@include('admin.results.ranked-method')</p>
            @else
                <p class="text-ink-2">The judges' totals for each {{ $segment->forFinalistsOnly() ? 'finalist' : 'candidate' }} are added up and divided by the number of judges. The highest average, rounded to 2 decimals, places first; equal averages share a {{ $segment->forFinalistsOnly() ? 'placement' : 'rank' }}.</p>
            @endif
            @if ($segment->isAward())
                <p class="text-ink-2">A separate award: these {{ $ranked ? 'ranks' : 'scores' }} don't count toward Round 1 or Round 2.</p>
            @endif
            @if ($unsettled->isNotEmpty())
                <p role="alert" class="rounded-md bg-danger-soft px-3 py-2 font-medium text-danger">
                    {{ $unsettled->map(fn ($tie) => 'Tied for '.TieBreak::place($segment, $tie['rank'], $division).': '.$tie['candidates']->map(fn ($c) => 'No. '.$c->number)->join(', ', ' and '))->join('; ') }}.
                    {{ $segment->forFinalistsOnly() ? 'Record the board of judges\' order' : 'Record the board of judges\' pick for the winner' }} under {{ $ties->count() === 1 ? 'Settle the tie' : 'Settle ties' }}, below the table.
                </p>
            @endif
            @foreach ($ties->whereNotNull('order') as $tie)
                <p class="text-ink-2"><i class="fa-solid fa-scale-balanced" aria-hidden="true"></i> {{ TieBreak::note($segment, $tie) }}</p>
            @endforeach
            @if ($tied->isNotEmpty())
                {{-- Ties are routine while sheets are open; they need the board only once every judge has locked in. --}}
                <p @if ($final) role="alert" @endif
                    class="{{ $final ? 'rounded-md bg-danger-soft px-3 py-2 font-medium text-danger' : 'text-ink-2' }}">
                    {{ $final ? 'Tied, the board of judges decides their order:' : 'Tied for now:' }}
                    {{ $tied->map(fn ($rows, $rank) => "rank {$rank}, ".$rows->map(fn ($r) => 'No. '.$r['candidate']->number)->join(' and '))->join('; ') }}.
                </p>
            @endif
            @if (! $final && $ties->isNotEmpty())
                <p class="text-ink-2">
                    <i class="fa-solid fa-scale-balanced" aria-hidden="true"></i>
                    If {{ $ties->count() === 1 ? 'this tie' : 'these ties' }} still {{ $ties->count() === 1 ? 'stands' : 'stand' }} once every judge has locked in, a form below the table records {{ $segment->forFinalistsOnly() ? 'the board of judges\' order' : 'the board of judges\' pick for the winner' }}.
                </p>
            @endif
        @endif
    </div>

    <x-table-card>
        @include($judge ? 'admin.results.tables.judge' : 'admin.results.tables.segment', ['print' => false])
    </x-table-card>

    @if (! $judge && $final && $ties->isNotEmpty())
        @include('admin.results.settle-ties')
    @endif
@endsection
