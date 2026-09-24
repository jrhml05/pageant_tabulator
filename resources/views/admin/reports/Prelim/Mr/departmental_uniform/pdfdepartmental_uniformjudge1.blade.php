@extends('admin.reports.pdf-layout')

@section('eyebrow', 'Mr. LCUAA 2026 · Preliminaries')
@section('heading', 'Departmental uniform: Judge 1 scores')
@section('signatures', 'judge')
@section('judge-label', 'Judge 1')

@section('content')
    <table class="sheet">
        <thead>
            <tr>
                <th style="width:10%">Candidate</th>
                <th style="width:15%">Presentation & neatness 40%</th>
                <th style="width:15%">Figure 30%</th>
                <th style="width:15%">Beauty & poise 20%</th>
                <th style="width:15%">Overall impact 10%</th>
                <th style="width:10%">Total 100%</th>
                <th style="width:10%">Rank</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['candidate'] as $candidate)
                <tr>
                    <td>{{ strtoupper($candidate->name) }}</td>
                    @foreach ( $candidate->departmental_uniform_score as $score)
                        @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)
                            <td>{{ $score->presentation }}</td>
                            <td>{{ $score->figure }}</td>
                            <td>{{ $score->beauty_poise }}</td>
                            <td>{{ $score->overall_impact }}</td>
                            <td>{{ $score->presentation + $score->figure + $score->beauty_poise + $score->overall_impact }}</td>
                            @forelse   ($data['rank'] as $rank)
                                @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)
                                    <td>{{ $rank->dept_uniform }}</td>
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
