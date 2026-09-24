@extends('admin.reports.pdf-layout')

@section('eyebrow', 'Mr. LCUAA 2026 · Preliminaries')
@section('heading', 'Top 5 results')
@section('signatures', 'panel')

@section('content')
    <table class="sheet">
        <thead>
            <tr>
                <th style="width:10%">Candidate</th>
                <th style="width:10%">Pre-pageant rank</th>
                <th style="width:10%">Preliminaries rank</th>
                <th style="width:15%">Overall rank</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data['final_rank'] as $rank)
            <tr>
                <td>{{ strtoupper($rank->candidate->name) }}</td>
                <td>{{ strtoupper($rank->prepageant) }}</td>
                <td>{{ strtoupper($rank->pageant) }}</td>
                <td>{{ strtoupper($rank->to_top_5) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="empty">No candidates to show.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
