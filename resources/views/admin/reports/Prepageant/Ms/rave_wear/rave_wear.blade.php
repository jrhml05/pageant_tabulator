@extends('layouts.master')

@section('title', 'Rave wear · Ms. LCUAA')

@section('content')
    <x-report-header division="ms" stage="Pre-pageant" title="Rave wear" route="ms_rave_wear" :print="route('ms_pdfrave_wear')" rank="/ms_rave_wear_rank" />
    <x-table-card>
        <table class="data-table data-table-ranked">
            <thead>
                <tr>
                    <th scope="col" style="width:15%">Candidate</th>
                    <th scope="col" style="width:10%">Judge 1</th>
                    <th scope="col" style="width:5%">Rank</th>
                    <th scope="col" style="width:10%">Judge 2</th>
                    <th scope="col" style="width:5%">Rank</th>
                    <th scope="col" style="width:10%">Judge 3</th>
                    <th scope="col" style="width:5%">Rank</th>
                    <th scope="col" style="width:10%">Final rank</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data['candidate'] as $candidate)

                    <tr>
                        <td>{{ strtoupper($candidate->id) }}</td>

                        @foreach ( $candidate->rave_wear_score as $score)

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)

                                @php
                                    $score_judge1 = $score->style + $score->creativity + $score->functionality + $score->audience_impact;
                                @endphp

                                <td>{{ $score_judge1 }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)

                                        <td>{{ $rank->rave_wear }}</td>

                                    @endif

                                @empty

                                    <td></td>

                                @endforelse

                            @endif

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)

                                @php
                                    $score_judge2 = $score->style + $score->creativity + $score->functionality + $score->audience_impact;
                                @endphp

                                <td>{{ $score_judge2 }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)

                                        <td>{{ $rank->rave_wear }}</td>

                                    @endif

                                @empty

                                    <td></td>

                                @endforelse

                            @endif

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)

                                @php
                                    $score_judge3 = $score->style + $score->creativity + $score->functionality + $score->audience_impact;
                                @endphp

                                <td>{{ $score_judge3 }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)

                                        <td>{{ $rank->rave_wear }}</td>

                                    @endif

                                @empty

                                    <td></td>

                                @endforelse

                            @endif

                        @endforeach


                        @forelse   ($data['final_rank'] as $final_rank)

                            @if ($final_rank->candidate_id == $score->candidate_id )

                                <td style="width:5%; text-align: center">{{ $final_rank->rave_wear }}</td>

                            @endif

                        @empty

                            <td></td>

                        @endforelse
                    </tr>

                @empty

                    <tr>
                        <td colspan="8" class="data-table-empty">No candidates in the database. Run <code>php artisan db:seed</code>, then reload this page.</td>
                    </tr>

                @endforelse
            </tbody>
        </table>
    </x-table-card>

@endsection
