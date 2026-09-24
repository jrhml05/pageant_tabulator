@extends('judge_app.layouts.app')

@section('title', 'Rave wear · Ms. LCUAA')

@section('content')
    @livewire('judge.prepageant.ms.ravewear-score-board-component', ['stage' => $stage])
@endsection
