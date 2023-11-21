@extends('judge_app.layouts.app')
@section('content')

    @livewire('judge.talent-score-board-component', [ 'stage' => $stage ])

@endsection
