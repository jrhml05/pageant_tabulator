@extends('admin.results.pdf.layout')

@php
    $segment = $result['segment'];
    $signers = $judge
        ? [$judge->name]
        : [...$result['judges']->pluck('name')->all(), 'Tabulator'];
@endphp

@section('eyebrow', "{$label} · {$segment->roundLabel()}")
@section('heading', $judge ? "{$segment->label}: Judge {$seat}" : "{$segment->label} results")

@section('content')
    @unless ($judge)
        @if ($segment->showsAverageRank())
            <p class="note">Each judge ranks the candidates by total score (equal totals share a rank). The ranks are averaged over the judges; the lowest average places first. Tied averages share a rank.</p>
        @elseif ($segment->ranksBySum())
            <p class="note">Each judge ranks the {{ $segment->forFinalistsOnly() ? 'finalists' : 'candidates' }} by total score (equal totals share a rank). The ranks are added up; the lowest sum places first. Tied sums share a {{ $segment->forFinalistsOnly() ? 'placement' : 'rank' }}.</p>
        @else
            <p class="note">Average of each judge's total, rounded to 2 decimals. Under each total is that judge's rank of the candidate. In candidate number order; tied candidates share a rank.</p>
        @endif
    @endunless
    @include($judge ? 'admin.results.tables.judge' : 'admin.results.tables.segment', ['print' => true])
@endsection

@section('signatures')
    @include('admin.results.pdf.signatures', ['signers' => $signers])
@endsection
