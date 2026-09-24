@extends('layouts.master')

@section('title', 'Top 5 · Mr. LCUAA')

@section('content')
    <x-report-header division="mr" stage="Preliminaries" title="Top 5" :print="route('mr_pdftop_5')" />
    <x-table-card>
        <table class="data-table data-table-ranked">
            <thead>
                <tr>
                    <th scope="col" style="width:10%">Candidate</th>
                    <th scope="col" style="width:10%">Pre-pageant rank</th>
                    <th scope="col" style="width:10%">Preliminaries rank</th>
                    <th scope="col" style="width:15%">Overall rank</th>
                    {{-- <th scope="col" style="width:20%">Result</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($data['final_rank'] as $rank)
                <tr>
                    <td>{{ strtoupper($rank->candidate->name) }}</td>
                    <td>{{ strtoupper($rank->prepageant) }}</td>
                    <td>{{ strtoupper($rank->pageant) }}</td>
                    <td>{{ strtoupper($rank->to_top_5) }}</td>

                    {{-- <td>{{ ROUND(($score_judge1 + $score_judge2 + $score_judge3) / 3, 2) }}</td> --}}
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="data-table-empty">No ranks yet. Rank candidates on the Pre-pageant and Preliminaries results first.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-table-card>
@endsection