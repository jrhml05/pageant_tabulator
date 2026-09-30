@extends('admin.results.pdf.layout')

@section('eyebrow', $label)
@section('heading', 'Round 1 results')

@section('content')
    <p class="note">Each segment is its judges' average, rounded to 2 decimals; the total adds them up. Sorted by rank; tied candidates share a rank.</p>
    @include('admin.results.tables.round1', ['print' => true])
@endsection

@section('signatures')
    @include('admin.results.pdf.signatures', ['signers' => [...$judges->map(fn ($j) => $j->name)->all(), 'Tabulator']])
@endsection
