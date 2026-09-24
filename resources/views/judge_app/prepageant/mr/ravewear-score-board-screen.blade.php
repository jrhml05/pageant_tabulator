@extends('judge_app.layouts.app')

@section('title', 'Rave wear · Mr. LCUAA')

@section('content')
    @livewire('judge.prepageant.mr.ravewear-score-board-component', ['stage' => $stage])
@endsection
