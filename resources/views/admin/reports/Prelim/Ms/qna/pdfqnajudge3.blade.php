@extends('admin.reports.pdf-layout')

@section('eyebrow', 'Ms. LCUAA 2026 · Preliminaries')
@section('heading', 'Casual Q&A: Judge 3 scores')
@section('signatures', 'judge')
@section('judge-label', 'Judge 3')

@section('content')
    <table class="sheet">
        <thead>
            <tr>
                <th style="width:10%">Candidate</th>
                <th style="width:15%">Relevance 40%</th>
                <th style="width:15%">Delivery/confidence 20%</th>
                <th style="width:15%">Content of answer 30%</th>
                <th style="width:15%">Audience impact 10%</th>
                <th style="width:10%">Total 100%</th>
                <th style="width:10%">Rank</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['candidate'] as $candidate)
                <tr>
                    <td>{{ strtoupper($candidate->id) }}</td>
                    @foreach ( $candidate->qna_score as $score)
                        @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)
                            <td>{{ $score->relevance }}</td>
                            <td>{{ $score->delivery }}</td>
                            <td>{{ $score->content }}</td>
                            <td>{{ $score->audience_impact }}</td>
                            <td>{{ $score->relevance + $score->delivery + $score->content + $score->audience_impact }}</td>
                            @forelse   ($data['rank'] as $rank)
                                @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)
                                    <td>{{ $rank->qna }}</td>
                                @endif
                            @empty
                                <td></td>
                            @endforelse
                        @endif
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">No candidates to show.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
