{{--
    All judges' totals for one segment, sorted by rank. Shared by the results page and its PDF ($print).
    Rank-sum segments (Round 2) also show each judge's rank of the candidate and the sum of those ranks.
--}}
@use('App\Scoring\Points')
@use('App\Scoring\Tabulator')
@php
    $segment = $result['segment'];
    $judges = $result['judges'];
    $placements = $segment->forFinalistsOnly();
    $rows = collect($result['rows'])->sortBy(fn ($row) => [$row['rank'] ?? PHP_INT_MAX, $row['candidate']->number]);
    $max = Points::plain($segment->maxPoints());
    $bySum = $segment->ranksBySum();
@endphp

<table class="{{ $print ? 'sheet' : 'data-table data-table-ranked' }}">
    <thead>
        <tr>
            <th scope="col">Candidate</th>
            @foreach ($judges as $judge)
                <th scope="col">Judge {{ $loop->iteration }}@if ($judge->name !== "Judge {$loop->iteration}")<span class="sub">{{ $judge->name }}</span>@endif</th>
            @endforeach
            @if ($bySum)
                <th scope="col">Rank sum<span class="sub">lowest wins</span></th>
            @else
                <th scope="col">Average<span class="sub">of {{ $max }}</span></th>
            @endif
            <th scope="col">Rank</th>
            @if ($placements)
                <th scope="col">Placement</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse ($rows as $row)
            <tr>
                <th scope="row">No. {{ $row['candidate']->number }}@if ($row['candidate']->school)<span class="sub">{{ $row['candidate']->school }}</span>@endif</th>
                @foreach ($judges as $judge)
                    <td>
                        @include('admin.results.tables.value', ['value' => $row['totals'][$judge->id]])
                        @if ($bySum && $result['rank_judges']->contains($judge->id))
                            <span class="sub">rank {{ $row['judge_ranks'][$judge->id] }}</span>
                        @endif
                    </td>
                @endforeach
                @if ($bySum)
                    <td class="strong">@if ($row['rank_sum'] === null)–<span class="sr-only"> not ranked</span>@else{{ $row['rank_sum'] }}@endif</td>
                @else
                    <td class="strong">@include('admin.results.tables.value', ['value' => $row['average']])</td>
                @endif
                <td>@include('admin.results.tables.rank', ['row' => $row])</td>
                @if ($placements)
                    <td>{{ Tabulator::placement($row['rank'], $division) }}</td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ $judges->count() + ($placements ? 4 : 3) }}" class="{{ $print ? 'empty' : 'data-table-empty' }}">
                    {{ $placements ? 'No finalists saved yet. Save them on the Round 1 results page.' : 'No candidates yet.' }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
