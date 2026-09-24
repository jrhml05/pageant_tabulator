@extends('judge_app.layouts.app')

@section('title', 'Swim wear · Mr. LCUAA')

@section('content')
    @livewire('judge.preliminaries.mr.swimwear-score-board-component', ['stage' => $stage])
@endsection
