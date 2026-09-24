@extends('admin.reports.pdf-layout')

@section('eyebrow', 'Ms. LCUAA 2026 · Preliminaries')
@section('heading', 'Formal wear: Judge 1 scores')
@section('signatures', 'judge')
@section('judge-label', 'Judge 1')

@section('content')
    <table class="sheet">
        <thead>
            <tr>
                <th style="width:5%">Candidate</th>
                <th style="width:15%">Beauty & poise 40%</th>
                <th style="width:20%">Stage deportment/presence 30%</th>
                <th style="width:15%">Design & fitting 20%</th>
                <th style="width:15%">Overall impact 10%</th>
                <th style="width:10%">Total 100%</th>
                <th style="width:10%">Rank</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['candidate'] as $candidate)
                <tr>
                    <td>{{ strtoupper($candidate->id) }}</td>
                    @foreach ( $candidate->formal_wear_score as $score)
                        @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)
                            <td>{{ $score->beauty }}</td>
                            <td>{{ $score->stage_presence }}</td>
                            <td>{{ $score->design }}</td>
                            <td>{{ $score->overall_impact }}</td>
                            <td>{{ $score->beauty + $score->stage_presence + $score->design + $score->overall_impact }}</td>
                            @forelse   ($data['rank'] as $rank)
                                @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)
                                    <td>{{ $rank->formal_wear }}</td>
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
