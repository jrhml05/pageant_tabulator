@extends('judge_app.layouts.app')
@section('content')

    <div class="row pt-5">
        @livewire('judge.preliminaries.mr.casualwear-score-board-component', [ 'stage' => $stage ])
    </div>
@endsection
