@extends('judge_app.layouts.app')

@section('title', 'National costume · Ms. LCUAA')

@section('content')
    @livewire('judge.preliminaries.ms.nationalcostume-score-board-component', ['stage' => $stage])
@endsection
