@extends('admin.reports.pdf-layout')

@section('eyebrow', 'Ms. LCUAA 2026')
@section('heading', 'Pre-pageant: Judge 2 scores')
@section('signatures', 'judge')
@section('judge-label', 'Judge 2')

@section('content')
    <table class="sheet">
        <thead>
            <tr>
                <th style="width:10%">Candidate</th>
                <th style="width:15%">Rave wear 50%</th>
                <th style="width:15%">Talent 50%</th>
                <th style="width:10%">Total 100%</th>
                <th style="width:10%">Rank</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['candidate'] as $candidate)
                <tr>
                    <td>{{ strtoupper($candidate->id) }}</td>
                    @foreach ( $candidate->prepageant_score as $score)
                        @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)
                            <td>{{ $score->rave_wear }}</td>
                            <td>{{ $score->talent }}</td>
                            <td>{{ $score->rave_wear + $score->talent }}</td>
                            @forelse   ($data['rank'] as $rank)
                                @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)
                                    <td>{{ $rank->prepageant }}</td>
                                @endif
                            @empty
                                <td></td>
                            @endforelse
                        @endif
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">No candidates to show.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
