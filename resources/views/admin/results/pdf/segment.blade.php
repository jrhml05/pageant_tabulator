@extends('admin.results.pdf.layout')

@use('App\Scoring\TieBreak')
@php
    $segment = $result['segment'];
    $signers = $judge
        ? [$judge->name]
        : [...$result['judges']->pluck('name')->all(), 'Tabulator'];
@endphp

@section('eyebrow', "{$label} · {$segment->roundLabel()}")
@section('heading', $judge ? "{$segment->label}: Judge {$seat}" : "{$segment->label} results")

@section('content')
    @if (! $judge && $segment->isRanked())
        <p class="note">@include('admin.results.ranked-method') In candidate number order.</p>
    @elseif (! $judge)
        <p class="note">The judges' totals for each {{ $segment->forFinalistsOnly() ? 'finalist' : 'candidate' }} are added up and divided by the number of judges. The highest average, rounded to 2 decimals, places first; equal averages share a {{ $segment->forFinalistsOnly() ? 'placement' : 'rank' }}. In candidate number order.</p>
    @endif
    @unless ($judge)
        @foreach (collect($result['ties'])->whereNotNull('order') as $tie)
            <p class="note"><strong>Tie settled by the board of judges:</strong> {{ TieBreak::note($segment, $tie) }}</p>
        @endforeach
    @endunless
    @include($judge ? 'admin.results.tables.judge' : 'admin.results.tables.segment', ['print' => true])
@endsection

@section('signatures')
    @include('admin.results.pdf.signatures', ['signers' => $signers])
@endsection
