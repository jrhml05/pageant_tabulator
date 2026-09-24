@extends('layouts.master')

@section('title', 'National costume · Judge 1 · Mr. LCUAA')

@section('content')
    <x-report-header division="mr" stage="Preliminaries" title="National costume" route="mr_national_costume" judge="1" :print="route('mr_pdfnational_costumejudge1')" />
    <x-table-card>
        <table class="data-table data-table-ranked">
            <thead>
                <tr>
                    <th scope="col" style="width:10%">Candidate</th>
                    <th scope="col" style="width:15%">Creative design 40%</th>
                    <th scope="col" style="width:15%">Stage presence 30%</th>
                    <th scope="col" style="width:15%">Poise & bearing 20%</th>
                    <th scope="col" style="width:15%">Overall impact 10%</th>
                    <th scope="col" style="width:10%">Total 100%</th>
                    <th scope="col" style="width:10%">Rank</th>

                    {{-- <th scope="col" style="width:20%">Result</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($data['candidate'] as $candidate)

                    <tr>
                        <td>{{ strtoupper($candidate->name) }}</td>

                        @foreach ( $candidate->national_costume_score as $score)

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)

                                <td>{{ $score->design }}</td>
                                <td>{{ $score->stage_presence }}</td>
                                <td>{{ $score->poise_bearing }}</td>
                                <td>{{ $score->overall_impact }}</td>

                                <td>{{ $score->design + $score->stage_presence + $score->poise_bearing + $score->overall_impact }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)

                                        <td>{{ $rank->national_costume }}</td>

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
