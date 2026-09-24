@extends('judge_app.layouts.app')

@section('title', 'Swim wear · Ms. LCUAA')

@section('content')
    @livewire('judge.preliminaries.ms.swimwear-score-board-component', ['stage' => $stage])
@endsection
