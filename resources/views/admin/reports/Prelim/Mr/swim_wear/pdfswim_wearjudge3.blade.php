@extends('admin.reports.pdf-layout')

@section('eyebrow', 'Mr. LCUAA 2026 · Preliminaries')
@section('heading', 'Swim wear: Judge 3 scores')
@section('signatures', 'judge')
@section('judge-label', 'Judge 3')

@section('content')
    <table class="sheet">
        <thead>
            <tr>
                <th style="width:10%">Candidate</th>
                <th style="width:15%">Creative design 40%</th>
                <th style="width:15%">Stage presence 30%</th>
                <th style="width:15%">Poise & bearing 20%</th>
                <th style="width:15%">Overall impact 10%</th>
                <th style="width:10%">Total 100%</th>
                <th style="width:10%">Rank</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['candidate'] as $candidate)
                <tr>
                    <td>{{ strtoupper($candidate->name) }}</td>
                    @foreach ( $candidate->swim_wear_score as $score)
                        @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)
                            <td>{{ $score->body }}</td>
                            <td>{{ $score->poise }}</td>
                            <td>{{ $score->stage_presence }}</td>
                            <td>{{ $score->audience_impact }}</td>
                            <td>{{ $score->body + $score->poise + $score->stage_presence + $score->audience_impact }}</td>
                            @forelse   ($data['rank'] as $rank)
                                @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)
                                    <td>{{ $rank->swim_wear }}</td>
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
