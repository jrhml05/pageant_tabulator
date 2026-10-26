{{--
    One judge's sheet for one segment, in candidate order. Shared by the results page and its PDF ($print).
    A scaled segment (Talent) also shows what the judge's total is worth at its weight in the round.
    A ranked segment (Casual wear) shows only the rank the judge gave.
--}}
@use('App\Scoring\Points')
@php
    $segment = $result['segment'];
    $scaled = $segment->isScaled();
    $weight = Points::plain($segment->weight());
    $ranked = $segment->isRanked();
@endphp

<table class="{{ $print ? 'sheet' : 'data-table data-table-ranked' }}">
    <thead>
        <tr>
            <th scope="col">Candidate</th>
            @if ($ranked)
                <th scope="col">Rank<span class="sub">1 is the top pick</span></th>
            @else
                @foreach ($segment->criteria as $criterion)
                    <th scope="col">{{ $criterion['label'] }}<span class="sub">of {{ Points::plain($criterion['max']) }}</span></th>
                @endforeach
                <th scope="col">Total<span class="sub">of {{ Points::plain($segment->maxPoints()) }}</span></th>
            @endif
            @if ($scaled)
                <th scope="col">{{ $segment->roundLabel() }} ({{ $weight }}%)<span class="sub">of {{ $weight }}</span></th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse ($result['rows'] as $row)
            <tr>
                <th scope="row">No. {{ $row['candidate']->number }}@if ($print && $row['candidate']->school)<span class="sub">{{ $row['candidate']->school }}</span>@endif</th>
                @if ($ranked)
                    <td class="strong">@include('admin.results.tables.value', ['value' => $row['totals'][$judge->id], 'plain' => true])</td>
                @else
                    @foreach (array_keys($segment->criteria) as $criterion)
                        <td>@include('admin.results.tables.value', ['value' => $row['points'][$judge->id][$criterion]])</td>
                    @endforeach
                    <td class="strong">@include('admin.results.tables.value', ['value' => $row['totals'][$judge->id]])</td>
                @endif
                @if ($scaled)
                    <td>@include('admin.results.tables.value', ['value' => $segment->weighted($row['totals'][$judge->id])])</td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ $ranked ? 2 : count($segment->criteria) + ($scaled ? 3 : 2) }}" class="{{ $print ? 'empty' : 'data-table-empty' }}">
                    {{ $segment->forFinalistsOnly() ? 'No finalists saved yet.' : 'No candidates yet.' }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
