@extends('judge_app.layouts.app')
@section('content')

    <div class="row mt-4">
        @livewire('judge.prepageant.ms.talent-score-board-component', [ 'stage' => $stage ])
    </div>
@endsection
