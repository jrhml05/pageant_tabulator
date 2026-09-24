@extends('judge_app.layouts.app')

@section('title', 'National costume · Mr. LCUAA')

@section('content')
    @livewire('judge.preliminaries.mr.nationalcostume-score-board-component', ['stage' => $stage])
@endsection
