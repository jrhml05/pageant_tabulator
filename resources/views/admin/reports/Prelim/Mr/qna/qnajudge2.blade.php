@extends('layouts.master')

@section('title', 'Casual Q&A · Judge 2 · Mr. LCUAA')

@section('content')
    <x-report-header division="mr" stage="Preliminaries" title="Casual Q&A" route="mr_qna" judge="2" :print="route('mr_pdfqnajudge2')" />
    <x-table-card>
        <table class="data-table data-table-ranked">
            <thead>
                <tr>
                    <th scope="col" style="width:10%">Candidate</th>
                    <th scope="col" style="width:15%">Relevance 40%</th>
                    <th scope="col" style="width:15%">Delivery/confidence 20%</th>
                    <th scope="col" style="width:15%">Content of answer 30%</th>
                    <th scope="col" style="width:15%">Audience impact 10%</th>
                    <th scope="col" style="width:10%">Total 100%</th>
                    <th scope="col" style="width:10%">Rank</th>

                    {{-- <th scope="col" style="width:20%">Result</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($data['candidate'] as $candidate)

                    <tr>
                        <td>{{ strtoupper($candidate->name) }}</td>

                        @foreach ( $candidate->qna_score as $score)

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)

                                <td>{{ $score->relevance }}</td>
                                <td>{{ $score->delivery }}</td>
                                <td>{{ $score->content }}</td>
                                <td>{{ $score->audience_impact }}</td>

                                <td>{{ $score->relevance + $score->delivery + $score->content + $score->audience_impact }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)

                                        <td>{{ $rank->qna }}</td>

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
