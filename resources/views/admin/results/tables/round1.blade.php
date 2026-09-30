{{-- Round 1 per segment and in total, sorted by rank. Shared by the results page and its PDF ($print). --}}
@use('App\Scoring\Points')
@php
    $segments = $result['segments'];
    $rows = collect($result['rows'])->sortBy(fn ($row) => [$row['rank'] ?? PHP_INT_MAX, $row['candidate']->number]);
@endphp

<table class="{{ $print ? 'sheet' : 'data-table data-table-ranked' }}">
    <thead>
        <tr>
            <th scope="col">Candidate</th>
            @foreach ($segments as $segment)
                <th scope="col">{{ $segment->short }}<span class="sub">of {{ Points::plain($segment->maxPoints()) }}</span></th>
            @endforeach
            <th scope="col">Total<span class="sub">of {{ Points::plain($segments->sum(fn ($s) => $s->maxPoints())) }}</span></th>
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
                @foreach ($segments as $segment)
                    <td>@include('admin.results.tables.value', ['value' => $row['averages'][$segment->key]])</td>
                @endforeach
                <td class="strong">@include('admin.results.tables.value', ['value' => $row['total']])</td>
                <td>@include('admin.results.tables.rank', ['row' => $row])</td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ $segments->count() + 3 }}" class="{{ $print ? 'empty' : 'data-table-empty' }}">No candidates yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>
