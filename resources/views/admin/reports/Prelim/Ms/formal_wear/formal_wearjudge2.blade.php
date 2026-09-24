@extends('layouts.master')

@section('title', 'Formal wear · Judge 2 · Ms. LCUAA')

@section('content')
    <x-report-header division="ms" stage="Preliminaries" title="Formal wear" route="ms_formal_wear" judge="2" :print="route('ms_pdfformal_wearjudge2')" />
    <x-table-card>
        <table class="data-table data-table-ranked">
            <thead>
                <tr>
                    <th scope="col" style="width:5%">Candidate</th>
                    <th scope="col" style="width:15%">Beauty & poise 40%</th>
                    <th scope="col" style="width:20%">Stage deportment/presence 30%</th>
                    <th scope="col" style="width:15%">Design & fitting 20%</th>
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

                        @foreach ( $candidate->formal_wear_score as $score)

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)

                                <td>{{ $score->beauty }}</td>
                                <td>{{ $score->stage_presence }}</td>
                                <td>{{ $score->design }}</td>
                                <td>{{ $score->overall_impact }}</td>

                                <td>{{ $score->beauty + $score->stage_presence + $score->design + $score->overall_impact }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)

                                        <td>{{ $rank->formal_wear }}</td>

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
