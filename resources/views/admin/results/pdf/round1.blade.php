@extends('admin.results.pdf.layout')

@section('eyebrow', $label)
@section('heading', 'Round 1 results')

@section('content')
    <p class="note">
        Each judge ranks the candidates by their total across the panel's segments. The ranks are averaged over the
        panel's judges and weighted
        ({{ $result['panels']->map(fn ($panel) => mb_strtolower($panel['label']).' '.$panel['weight'].'%')->join(', ') }}),
        and the lowest total ranks first. A tie on the total goes to the better average rank in {{ $result['tie_break']->map(fn ($key) => $result['criteria'][$key]['segment']->short)->join(', then ') }};
        candidates still tied share a rank.
    </p>
    @include('admin.results.tables.round1', ['print' => true])
@endsection

@section('signatures')
    @include('admin.results.pdf.signatures', ['signers' => [...$judges->map(fn ($j) => $j->name)->all(), 'Tabulator']])
@endsection
