@extends('layouts.master')

@section('title', 'Formal wear · Mr. LCUAA')

@section('content')
    <x-report-header division="mr" stage="Preliminaries" title="Formal wear" route="mr_formal_wear" :print="route('mr_pdfformal_wear')" rank="/mr_formal_wear_rank" />
    <x-table-card>
        <table class="data-table data-table-ranked">
            <thead>
                <tr>
                    <th scope="col" style="width:12%">Candidate</th>
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
                        <td>{{ strtoupper($candidate->name) }}</td>
                        @foreach ( $candidate->formal_wear_score as $score)

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)

                                @php
                                    $score_judge1 = $score->beauty + $score->stage_presence + $score->design + $score->overall_impact;
                                @endphp

                                <td>{{ $score_judge1 }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)
                                        <td>{{ $rank->formal_wear }}</td>
                                    @endif

                                @empty
                                    <td></td>
                                @endforelse

                                {{-- <td></td> --}}

                            @endif

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)

                                @php
                                    $score_judge2 = $score->beauty + $score->stage_presence + $score->design + $score->overall_impact;
                                @endphp

                                <td>{{ $score_judge2 }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)
                                        <td>{{ $rank->formal_wear }}</td>
                                    @endif

                                @empty
                                    <td></td>
                                @endforelse

                            @endif

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)

                                @php
                                    $score_judge3 = $score->beauty + $score->stage_presence + $score->design + $score->overall_impact;
                                @endphp

                                <td>{{ $score_judge3 }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)
                                        <td>{{ $rank->formal_wear }}</td>
                                    @endif

                                @empty
                                    <td></td>
                                @endforelse

                            @endif

                        @endforeach

                        @forelse ($data['final_rank'] as $final_rank)
                            @if ($final_rank->candidate_id == $candidate->id)
                                <td style="width:5%; text-align: center">{{ $final_rank->formal_wear }}</td>
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
