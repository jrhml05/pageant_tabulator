@extends('admin.results.pdf.layout')

@section('eyebrow', $label)
@section('heading', 'Round 1 results')

@section('content')
    <p class="note">
        @include('admin.results.round1-method') candidates still tied share a rank.
    </p>
    @include('admin.results.tables.round1', ['print' => true])
@endsection

@section('signatures')
    @include('admin.results.pdf.signatures', ['signers' => [...$judges->map(fn ($j) => $j->name)->all(), 'Tabulator']])
@endsection
