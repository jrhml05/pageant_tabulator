@extends('admin.results.pdf.layout')

@use('App\Scoring\Points')
@use('App\Scoring\Tabulator')
@use('App\Scoring\TieBreak')
@php
    $segment = $result['segment'];
    $judges = $result['judges'];
    $locked = $judges->whereIn('id', $result['locked'])->count();
    $showSchool = $winners->contains(fn ($row) => filled($row['candidate']->school));
    $missing = collect($result['rows'])->filter(fn ($row) => $row['counted'] < $judges->count())->sortBy('candidate.number');
    $ranked = $segment->isRanked();
    // Round 2 announces the top 5 with their placements; any other segment names its winner only.
    $announce = $segment->forFinalistsOnly();
    $tied = $winners->where('tied', true)->groupBy('rank')->sortKeys();
@endphp

@section('eyebrow', "{$label} · {$segment->roundLabel()}")
@section('heading', $announce ? 'Announcement of winners' : "{$segment->label}: winner")

@section('content')
    @if ($locked < $judges->count())
        <p class="note"><strong>Not final:</strong> {{ $locked }} of {{ $judges->count() }} judges have locked in this segment.</p>
    @elseif ($missing->isNotEmpty())
        <p class="note"><strong>Not final:</strong> {{ $missing->map(fn ($row) => 'No. '.$row['candidate']->number)->join(', ', ' and ') }} {{ $missing->count() === 1 ? "isn't" : "aren't" }} {{ $ranked ? 'ranked' : 'scored' }} by every judge.</p>
    @endif
    @if ($announce)
        <p class="note">Read from the top down: the 4th runner-up first, the winner last.</p>
        @if ($tied->isNotEmpty())
            <p class="note"><strong>Tied, the board decides their order:</strong>
                {{ $tied->map(fn ($rows, $rank) => Tabulator::placement($rank, $division).', '.$rows->map(fn ($r) => 'No. '.$r['candidate']->number)->join(' and '))->join('; ') }}.</p>
        @endif
    @elseif ($winners->count() > 1)
        <p class="note"><strong>Tied for first.</strong> The board decides the winner.</p>
    @endif
    @foreach (collect($result['ties'])->whereNotNull('order') as $tie)
        <p class="note"><strong>Tie settled by the board:</strong> {{ TieBreak::note($segment, $tie) }}</p>
    @endforeach

    <table class="sheet announce">
        <thead>
            <tr>
                @if ($announce)
                    <th style="width: 25%;">Placement</th>
                @endif
                <th style="width: 25%;">Candidate</th>
                @if ($showSchool)
                    <th class="school">College/University</th>
                @endif
                <th style="width: 20%;">{{ $ranked ? 'Rank total' : 'Average' }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($winners as $row)
                <tr>
                    @if ($announce)
                        <td>{{ Tabulator::placement($row['rank'], $division) }}</td>
                    @endif
                    <td>No. {{ $row['candidate']->number }}</td>
                    @if ($showSchool)
                        <td class="school">{{ $row['candidate']->school }}</td>
                    @endif
                    <td>{{ $ranked ? Points::plain($row['rank_total'] / 100) : Points::format($row['average']) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 2 + ($announce ? 1 : 0) + ($showSchool ? 1 : 0) }}" class="empty">
                        No winner yet. No {{ $announce ? 'finalist' : 'candidate' }} has been {{ $ranked ? 'ranked' : 'scored' }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@section('signatures')
    @include('admin.results.pdf.signatures', ['signers' => [...$judges->pluck('name')->all(), 'Tabulator']])
@endsection
