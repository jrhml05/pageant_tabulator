@extends('layouts.master')

@section('title', 'Departmental uniform · Judge 3 · Ms. LCUAA')

@section('content')
    <x-report-header division="ms" stage="Preliminaries" title="Departmental uniform" route="ms_departmental_uniform" judge="3" :print="route('ms_pdfdepartmental_uniformjudge3')" />
    <x-table-card>
        <table class="data-table data-table-ranked">
            <thead>
                <tr>
                    <th scope="col" style="width:10%">Candidate</th>
                    <th scope="col" style="width:15%">Presentation & neatness 40%</th>
                    <th scope="col" style="width:15%">Figure 30%</th>
                    <th scope="col" style="width:15%">Beauty & poise 20%</th>
                    <th scope="col" style="width:15%">Overall impact 10%</th>
                    <th scope="col" style="width:10%">Total 100%</th>
                    <th scope="col" style="width:10%">Rank</th>

                    {{-- <th scope="col" style="width:20%">Result</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($data['candidate'] as $candidate)

                    <tr>
                        <td>{{ strtoupper($candidate->id) }}</td>

                        @foreach ( $candidate->departmental_uniform_score as $score)

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)

                                <td>{{ $score->presentation }}</td>
                                <td>{{ $score->figure }}</td>
                                <td>{{ $score->beauty_poise }}</td>
                                <td>{{ $score->overall_impact }}</td>

                                <td>{{ $score->presentation + $score->figure + $score->beauty_poise + $score->overall_impact }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)

                                        <td>{{ $rank->dept_uniform }}</td>

                                    @endif

                                @empty

                                    <td></td>

                                @endforelse

                            @endif

                        @endforeach
                        {{-- <td>{{ ROUND(($score_judge1 + $score_judge2 + $score_judge3) / 3, 2) }}</td> --}}
                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="data-table-empty">No candidates in the database. Run <code>php artisan db:seed</code>, then reload this page.</td>
                    </tr>

                @endforelse
            </tbody>
        </table>
    </x-table-card>

@endsection
