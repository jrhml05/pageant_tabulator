@extends('admin.reports.pdf-layout')

@section('eyebrow', 'Mr. LCUAA 2026 · Preliminaries')
@section('heading', 'Casual Q&A results')
@section('signatures', 'panel')

@section('content')
    <table class="sheet">
        <thead>
            <tr>
                <th style="width:12%">Candidate</th>
                <th style="width:10%">Judge 1</th>
                <th style="width:5%">Rank</th>
                <th style="width:10%">Judge 2</th>
                <th style="width:5%">Rank</th>
                <th style="width:10%">Judge 3</th>
                <th style="width:5%">Rank</th>
                <th style="width:10%">Final rank</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['candidate'] as $candidate)
                <tr>
                    <td>{{ strtoupper($candidate->name) }}</td>
                    @foreach ($candidate->qna_score as $score)
                        @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)
                            @php
                                $score_judge1 = $score->relevance + $score->delivery + $score->content + $score->audience_impact;
                            @endphp
                            <td>{{ $score_judge1 }}</td>
                            @forelse ($data['rank'] as $rank)
                                @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)
                                    <td>{{ $rank->qna }}</td>
                                @endif
                            @empty
                                <td></td>
                            @endforelse
                        @endif
                        @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)
                            @php
                                $score_judge2 = $score->relevance + $score->delivery + $score->content + $score->audience_impact;
                            @endphp
                            <td>{{ $score_judge2 }}</td>
                            @forelse ($data['rank'] as $rank)
                                @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)
                                    <td>{{ $rank->qna }}</td>
                                @endif
                            @empty
                                <td></td>
                            @endforelse
                        @endif
                        @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)
                            @php
                                $score_judge3 = $score->relevance + $score->delivery + $score->content + $score->audience_impact;
                            @endphp
                            <td>{{ $score_judge3 }}</td>
                            @forelse ($data['rank'] as $rank)
                                @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)
                                    <td>{{ $rank->qna }}</td>
                                @endif
                            @empty
                                <td></td>
                            @endforelse
                        @endif
                    @endforeach
                    @forelse ($data['final_rank'] as $final_rank)
                        @if ($final_rank->candidate_id == $candidate->id)
                            <td style="width:5%; text-align: center">{{ $final_rank->qna }}</td>
                        @endif
                    @empty
                        <td></td>
                    @endforelse
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="empty">No candidates to show.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
