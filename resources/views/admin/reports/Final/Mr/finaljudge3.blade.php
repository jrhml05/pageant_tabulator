@extends('layouts.master')

@section('title', 'Final · Judge 3 · Mr. LCUAA')

@section('content')
    <x-report-header division="mr" stage="Final" title="Final" route="mr_final" judge="3" />
    <x-table-card>
        <table class="data-table data-table-ranked">
            <thead>
                <tr>
                    <th scope="col" style="width:10%">Candidate</th>
                    <th scope="col" style="width:15%">Wit & content 40%</th>
                    <th scope="col" style="width:15%">Projection & delivery 30%</th>
                    <th scope="col" style="width:15%">Stage presence 20%</th>
                    <th scope="col" style="width:15%">Overall impact 20%</th>
                    <th scope="col" style="width:10%">Total 100%</th>
                    <th scope="col" style="width:10%">Rank</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data['candidate'] as $candidate)
                    <tr>
                        <td>{{ strtoupper($candidate->name) }}</td>
                        @foreach ( $candidate->final_score as $score)

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)

                                <td>{{ $score->wit }}</td>
                                <td>{{ $score->projection }}</td>
                                <td>{{ $score->stage_presence }}</td>
                                <td>{{ $score->overall_impact }}</td>
                                <td>{{ $score->wit + $score->projection + $score->stage_presence + $score->overall_impact }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)
                                        <td>{{ $rank->final }}</td>
                                    @endif

                                @empty
                                    <td></td>
                                @endforelse

                                {{-- <td></td> --}}

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
