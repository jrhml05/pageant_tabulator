@extends('judge_app.layouts.app')

@section('title', 'Talent · Mr. LCUAA')

@section('content')
    @livewire('judge.prepageant.mr.talent-score-board-component', ['stage' => $stage])
@endsection
