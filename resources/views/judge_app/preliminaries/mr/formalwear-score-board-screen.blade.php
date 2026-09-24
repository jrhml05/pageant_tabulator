@extends('judge_app.layouts.app')

@section('title', 'Formal wear · Mr. LCUAA')

@section('content')
    @livewire('judge.preliminaries.mr.formalwear-score-board-component', ['stage' => $stage])
@endsection
