@extends('layouts.master')

@section('title', 'Preliminaries · Judge 2 · Ms. LCUAA')

@section('content')
    <x-report-header division="ms" stage="Preliminaries" title="Preliminaries" route="ms_prelim" judge="2" :print="route('ms_pdfprelim_judge2')" />
    <x-table-card>
        <table class="data-table data-table-ranked">
            <thead>
                <tr>
                    <th scope="col" style="width:10%">Candidate</th>
                    <th scope="col" style="width:15%">National costume 20%</th>
                    <th scope="col" style="width:15%">Departmental uniform 20%</th>
                    <th scope="col" style="width:15%">Swim wear 20%</th>
                    <th scope="col" style="width:15%">Formal wear 20%</th>
                    <th scope="col" style="width:15%">Casual Q&A 20%</th>
                    <th scope="col" style="width:10%">Total 100%</th>
                    <th scope="col" style="width:10%">Rank</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data['candidate'] as $candidate)
                    <tr>
                        <td>{{ strtoupper($candidate->id) }}</td>
                        @foreach ( $candidate->prelim_score as $score)

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)

                                <td>{{ $score->national_costume }}</td>
                                <td>{{ $score->dept_uniform }}</td>
                                <td>{{ $score->swim_wear }}</td>
                                <td>{{ $score->formal_wear }}</td>
                                <td>{{ $score->qna }}</td>
                                <td>{{ $score->national_costume + $score->dept_uniform + $score->swim_wear + $score->formal_wear + $score->qna }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)
                                        <td>{{ $rank->pageant }}</td>
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
                        <td colspan="8" class="data-table-empty">No candidates in the database. Run <code>php artisan db:seed</code>, then reload this page.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-table-card>
@endsection
