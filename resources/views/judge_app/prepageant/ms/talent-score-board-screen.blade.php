@extends('judge_app.layouts.app')

@section('title', 'Talent · Ms. LCUAA')

@section('content')
    @livewire('judge.prepageant.ms.talent-score-board-component', ['stage' => $stage])
@endsection
