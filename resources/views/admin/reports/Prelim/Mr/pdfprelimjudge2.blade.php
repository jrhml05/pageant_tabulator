@extends('admin.reports.pdf-layout')

@section('eyebrow', 'Mr. LCUAA 2026')
@section('heading', 'Preliminaries: Judge 2 scores')
@section('signatures', 'judge')
@section('judge-label', 'Judge 2')

@section('content')
    <table class="sheet">
        <thead>
            <tr>
                <th style="width:10%">Candidate</th>
                <th style="width:15%">National costume 20%</th>
                <th style="width:15%">Departmental uniform 20%</th>
                <th style="width:15%">Swim wear 20%</th>
                <th style="width:15%">Formal wear 20%</th>
                <th style="width:15%">Casual Q&A 20%</th>
                <th style="width:10%">Total 100%</th>
                <th style="width:10%">Rank</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['candidate'] as $candidate)
                <tr>
                    <td>{{ strtoupper($candidate->name) }}</td>
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
                        @endif
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="empty">No candidates to show.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
