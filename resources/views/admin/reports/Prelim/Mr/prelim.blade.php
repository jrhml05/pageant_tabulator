@extends('layouts.master')

@section('title', 'Preliminaries · Mr. LCUAA')

@section('content')
    <x-report-header division="mr" stage="Preliminaries" title="Preliminaries" route="mr_prelim" :print="route('mr_pdfprelim')" rank="/mr_prelim_rank">
        <button type="button" class="btn btn-secondary" data-action-url="/mr_to_top_5_rank"
            data-busy-label="Marking…" data-error-label="Marking finalists"
            data-confirm="Mark the five best combined ranks as finalists? This changes which candidates judges score in the final.">
            <i class="fa-solid fa-medal" aria-hidden="true"></i>
            <span data-label>Mark top 5 as finalists</span>
        </button>
    </x-report-header>
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
                        @foreach ( $candidate->prelim_score as $score)

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)

                                @php
                                    $score_judge1 = $score->national_costume + $score->dept_uniform + $score->swim_wear + $score->formal_wear + $score->qna;
                                @endphp

                                <td>{{ $score_judge1 }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)
                                        <td>{{ $rank->pageant }}</td>
                                    @endif

                                @empty
                                    <td></td>
                                @endforelse

                                {{-- <td></td> --}}

                            @endif

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)

                                @php
                                    $score_judge2 = $score->national_costume + $score->dept_uniform + $score->swim_wear + $score->formal_wear + $score->qna;
                                @endphp

                                <td>{{ $score_judge2 }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)
                                        <td>{{ $rank->pageant }}</td>
                                    @endif

                                @empty
                                    <td></td>
                                @endforelse

                            @endif

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)

                                @php
                                    $score_judge3 = $score->national_costume + $score->dept_uniform + $score->swim_wear + $score->formal_wear + $score->qna;
                                @endphp

                                <td>{{ $score_judge3 }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)
                                        <td>{{ $rank->pageant }}</td>
                                    @endif

                                @empty
                                    <td></td>
                                @endforelse

                            @endif

                        @endforeach

                        @forelse   ($data['final_rank'] as $final_rank)

                            @if ($final_rank->candidate_id == $score->candidate_id )

                                <td style="width:5%; text-align: center">{{ $final_rank->pageant }}</td>

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
