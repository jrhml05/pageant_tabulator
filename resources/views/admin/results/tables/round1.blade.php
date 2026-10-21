{{--
    Round 1 by panel and in total, sorted by rank. Shared by the results page and its PDF ($print).
    Each judge cell is that judge's total across the panel's segments, with the judge's rank under it.
--}}
@use('App\Scoring\Points')
@php
    $panels = $result['panels'];
    $criteria = $result['criteria'];
    $rows = collect($result['rows'])->sortBy(fn ($row) => [$row['rank'] ?? PHP_INT_MAX, $row['candidate']->number]);
    $columns = 3 + $panels->sum(fn ($panel) => $panel['judges']->count() + 3);
@endphp

<table class="{{ $print ? 'sheet' : 'data-table data-table-ranked' }}">
    <thead>
        <tr>
            <th aria-hidden="true"></th>
            @foreach ($panels as $panel)
                <th scope="colgroup" colspan="{{ $panel['judges']->count() + 3 }}">{{ $panel['label'] }}<span class="sub">{{ $panel['segments']->map->short->join(' + ') }}</span></th>
            @endforeach
            <th aria-hidden="true"></th>
            <th aria-hidden="true"></th>
        </tr>
        <tr>
            <th scope="col">Candidate</th>
            @foreach ($panels as $panel)
                @foreach ($panel['judges'] as $judge)
                    <th scope="col">Judge {{ $loop->iteration }}@if ($judge->name !== "Judge {$loop->iteration}")<span class="sub">{{ $judge->name }}</span>@endif</th>
                @endforeach
                <th scope="col">Total rank</th>
                <th scope="col">Avg rank</th>
                <th scope="col">{{ Points::plain($panel['weight']) }}%</th>
            @endforeach
            <th scope="col">Total<span class="sub">lowest wins</span></th>
            <th scope="col">Rank</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($rows as $row)
            <tr>
                <th scope="row">
                    No. {{ $row['candidate']->number }}
                    @if ($row['candidate']->is_finalist)
                        <span class="finalist">Finalist</span>
                    @endif
                    @if ($row['candidate']->school)<span class="sub">{{ $row['candidate']->school }}</span>@endif
                </th>
                @foreach ($panels as $key => $panel)
                    @php $scores = $row['panels'][$key]; @endphp
                    @foreach ($panel['judges'] as $judge)
                        <td>
                            @include('admin.results.tables.value', ['value' => $scores['totals'][$judge->id]])
                            @if ($panel['rank_judges']->contains($judge->id))
                                <span class="sub">rank {{ $scores['judge_ranks'][$judge->id] }}</span>
                            @endif
                        </td>
                    @endforeach
                    <td>@if ($scores['rank_sum'] === null)–<span class="sr-only"> not ranked</span>@else{{ $scores['rank_sum'] }}@endif</td>
                    <td>@include('admin.results.tables.value', ['value' => $scores['average_rank']])</td>
                    <td class="strong">@include('admin.results.tables.value', ['value' => $scores['weighted']])</td>
                @endforeach
                <td class="strong">@include('admin.results.tables.value', ['value' => $row['total']])</td>
                <td>
                    @include('admin.results.tables.rank', ['row' => $row])
                    @if ($row['tie_break'])
                        <span class="sub">tie broken on {{ collect($row['tie_break'])->map(fn ($key) => $criteria[$key]['segment']->short)->join(', then ') }}</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ $columns }}" class="{{ $print ? 'empty' : 'data-table-empty' }}">No candidates yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>
