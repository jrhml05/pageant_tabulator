@extends('admin.results.pdf.layout')

@use('App\Scoring\Points')
@php
    $segment = $result['segment'];
    $judges = $result['judges'];
    $locked = $judges->whereIn('id', $result['locked'])->count();
    $showSchool = $winners->contains(fn ($row) => filled($row['candidate']->school));
    $missing = collect($result['rows'])->filter(fn ($row) => $row['counted'] < $judges->count())->sortBy('candidate.number');
@endphp

@section('eyebrow', "{$label} · {$segment->roundLabel()}")
@section('heading', "{$segment->label}: winner")

@section('content')
    @if ($locked < $judges->count())
        <p class="note"><strong>Not final:</strong> {{ $locked }} of {{ $judges->count() }} judges have locked in this segment.</p>
    @elseif ($missing->isNotEmpty())
        <p class="note"><strong>Not final:</strong> {{ $missing->map(fn ($row) => 'No. '.$row['candidate']->number)->join(', ', ' and ') }} {{ $missing->count() === 1 ? "isn't" : "aren't" }} scored by every judge.</p>
    @endif
    @if ($winners->count() > 1)
        <p class="note"><strong>Tied for first.</strong> The board decides the winner.</p>
    @endif

    <table class="sheet announce">
        <thead>
            <tr>
                <th style="width: 25%;">Candidate</th>
                @if ($showSchool)
                    <th class="school">College/University</th>
                @endif
                <th style="width: 20%;">{{ $segment->showsAverageRank() ? 'Avg rank' : ($segment->ranksBySum() ? 'Rank sum' : 'Average') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($winners as $row)
                <tr>
                    <td>No. {{ $row['candidate']->number }}</td>
                    @if ($showSchool)
                        <td class="school">{{ $row['candidate']->school }}</td>
                    @endif
                    <td>{{ $segment->showsAverageRank() ? Points::format($row['average_rank']) : ($segment->ranksBySum() ? $row['rank_sum'] : Points::format($row['average'])) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $showSchool ? 3 : 2 }}" class="empty">No winner yet. Each judge has to score every candidate first.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

@section('signatures')
    @include('admin.results.pdf.signatures', ['signers' => [...$judges->pluck('name')->all(), 'Tabulator']])
@endsection
