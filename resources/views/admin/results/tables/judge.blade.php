{{-- One judge's sheet for one segment, in candidate order. Shared by the results page and its PDF ($print). --}}
@use('App\Scoring\Points')
@php
    $segment = $result['segment'];
@endphp

<table class="{{ $print ? 'sheet' : 'data-table data-table-ranked' }}">
    <thead>
        <tr>
            <th scope="col">Candidate</th>
            @foreach ($segment->criteria as $criterion)
                <th scope="col">{{ $criterion['label'] }}<span class="sub">of {{ Points::plain($criterion['max']) }}</span></th>
            @endforeach
            <th scope="col">Total<span class="sub">of {{ Points::plain($segment->maxPoints()) }}</span></th>
            <th scope="col">Rank</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($result['rows'] as $row)
            <tr>
                <th scope="row">No. {{ $row['candidate']->number }}@if ($row['candidate']->school)<span class="sub">{{ $row['candidate']->school }}</span>@endif</th>
                @foreach (array_keys($segment->criteria) as $criterion)
                    <td>@include('admin.results.tables.value', ['value' => $row['points'][$judge->id][$criterion]])</td>
                @endforeach
                <td class="strong">@include('admin.results.tables.value', ['value' => $row['totals'][$judge->id]])</td>
                <td>{{ $row['judge_ranks'][$judge->id] ?? '' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($segment->criteria) + 3 }}" class="{{ $print ? 'empty' : 'data-table-empty' }}">
                    {{ $segment->forFinalistsOnly() ? 'No finalists saved yet.' : 'No candidates yet.' }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
