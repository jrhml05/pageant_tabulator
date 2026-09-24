@extends('layouts.master')

@section('title', 'Swim wear · Judge 1 · Mr. LCUAA')

@section('content')
    <x-report-header division="mr" stage="Preliminaries" title="Swim wear" route="mr_swim_wear" judge="1" :print="route('mr_pdfswim_wearjudge1')" />
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

                        @foreach ( $candidate->swim_wear_score as $score)

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)

                                <td>{{ $score->body }}</td>
                                <td>{{ $score->poise }}</td>
                                <td>{{ $score->stage_presence }}</td>
                                <td>{{ $score->audience_impact }}</td>

                                <td>{{ $score->body + $score->poise + $score->stage_presence + $score->audience_impact }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)

                                        <td>{{ $rank->swim_wear }}</td>

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
