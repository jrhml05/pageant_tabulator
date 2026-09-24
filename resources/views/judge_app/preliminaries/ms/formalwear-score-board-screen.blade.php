@extends('judge_app.layouts.app')

@section('title', 'Formal wear · Ms. LCUAA')

@section('content')
    @livewire('judge.preliminaries.ms.formalwear-score-board-component', ['stage' => $stage])
@endsection
