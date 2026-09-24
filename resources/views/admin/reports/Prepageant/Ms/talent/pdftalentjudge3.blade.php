@extends('admin.reports.pdf-layout')

@section('eyebrow', 'Ms. LCUAA 2026 · Pre-pageant')
@section('heading', 'Talent: Judge 3 scores')
@section('signatures', 'judge')
@section('judge-label', 'Judge 3')

@section('content')
    <table class="sheet">
        <thead>
            <tr>
                <th style="width:10%">Candidate</th>
                <th style="width:15%">Mastery 40%</th>
                <th style="width:15%">Uniqueness 30%</th>
                <th style="width:15%">Stage presence 20%</th>
                <th style="width:15%">Audience impact 10%</th>
                <th style="width:10%">Total 100%</th>
                <th style="width:10%">Rank</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['candidate'] as $candidate)
                <tr>
                    <td>{{ strtoupper($candidate->id) }}</td>
                    @foreach ( $candidate->talent_score as $score)
                        @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)
                            <td>{{ $score->mastery }}</td>
                            <td>{{ $score->uniqueness }}</td>
                            <td>{{ $score->stage_presence }}</td>
                            <td>{{ $score->audience_impact }}</td>
                            <td>{{ $score->mastery + $score->uniqueness + $score->stage_presence + $score->audience_impact; }}</td>
                            @forelse   ($data['rank'] as $rank)
                                @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)
                                    <td>{{ $rank->talent }}</td>
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
