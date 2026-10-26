{{--
    Round 1 by segment and in total, sorted by rank. Shared by the results page and its PDF ($print).
    Each segment cell is the segment's average scaled to its weight. A scaled segment (Talent) shows its
    own average under it; a segment some judges haven't scored yet shows how many have.
--}}
@use('App\Scoring\Points')
@php
    $segments = $result['segments'];
    $judges = $result['judges'];
    $panels = $segments->groupBy(fn ($segment) => $segment->panel, preserveKeys: true);
    $rows = collect($result['rows'])->sortBy(fn ($row) => [$row['rank'] ?? PHP_INT_MAX, $row['candidate']->number]);
    $columns = 3 + $segments->count();
@endphp

<table class="{{ $print ? 'sheet' : 'data-table data-table-ranked' }}">
    <thead>
        <tr>
            <th aria-hidden="true"></th>
            @foreach ($panels as $panel => $group)
                <th scope="colgroup" colspan="{{ $group->count() }}">{{ config("pageant.panels.{$panel}") }}<span class="sub">{{ Points::plain($group->sum(fn ($s) => $s->weight())) }}%</span></th>
            @endforeach
            <th aria-hidden="true"></th>
            <th aria-hidden="true"></th>
        </tr>
        <tr>
            <th scope="col">Candidate</th>
            @foreach ($panels as $group)
                @foreach ($group as $segment)
                    <th scope="col">{{ $segment->short }}<span class="sub">{{ $segment->isScaled() ? 'of '.Points::plain($segment->maxPoints()).' at ' : '' }}{{ Points::plain($segment->weight()) }}%</span></th>
                @endforeach
            @endforeach
            <th scope="col">Total<span class="sub">of 100</span></th>
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
                    @if ($print && $row['candidate']->school)<span class="sub">{{ $row['candidate']->school }}</span>@endif
                </th>
                @foreach ($panels as $group)
                    @foreach ($group as $key => $segment)
                        @php $score = $row['segments'][$key]; @endphp
                        <td>
                            @include('admin.results.tables.value', ['value' => $score['weighted']])
                            @if ($score['average'] !== null && $segment->isScaled())
                                <span class="sub">avg {{ Points::format($score['average']) }}</span>
                            @endif
                            @if ($score['counted'] > 0 && $score['counted'] < $judges[$key])
                                <span class="sub">{{ $score['counted'] }} of {{ $judges[$key] }} judges</span>
                            @endif
                        </td>
                    @endforeach
                @endforeach
                <td class="strong">@include('admin.results.tables.value', ['value' => $row['total']])</td>
                <td>
                    @include('admin.results.tables.rank', ['row' => $row])
                    @if ($row['tie_break'])
                        <span class="sub">tie broken on {{ collect($row['tie_break'])->map(fn ($key) => $segments[$key]->short)->join(', then ') }}</span>
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
