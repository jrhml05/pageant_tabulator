{{--
    All judges' totals for one segment, in candidate number order, with their average and its rank.
    A ranked segment shows each judge's rank and the rank total instead. Shared by the results page and its PDF ($print).
--}}
@use('App\Scoring\Points')
@use('App\Scoring\Tabulator')
@php
    $segment = $result['segment'];
    $judges = $result['judges'];
    $placements = $segment->forFinalistsOnly();
    $rows = collect($result['rows'])->sortBy(fn ($row) => $row['candidate']->number);
    $max = Points::plain($segment->maxPoints());
    $ranked = $segment->isRanked();
@endphp

<table class="{{ $print ? 'sheet' : 'data-table data-table-ranked' }}">
    <thead>
        <tr>
            <th scope="col">Candidate</th>
            @foreach ($judges as $judge)
                <th scope="col">Judge {{ $loop->iteration }}@if ($judge->name !== "Judge {$loop->iteration}")<span class="sub">{{ $judge->name }}</span>@endif</th>
            @endforeach
            @if ($ranked)
                <th scope="col">Rank total<span class="sub">lowest wins</span></th>
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
                <th scope="row">No. {{ $row['candidate']->number }}@if ($print && $row['candidate']->school)<span class="sub">{{ $row['candidate']->school }}</span>@endif</th>
                @foreach ($judges as $judge)
                    <td>@include('admin.results.tables.value', ['value' => $row['totals'][$judge->id], 'plain' => $ranked])</td>
                @endforeach
                <td class="strong">@include('admin.results.tables.value', $ranked ? ['value' => $row['rank_total'], 'plain' => true] : ['value' => $row['average']])</td>
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
