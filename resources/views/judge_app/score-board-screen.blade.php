@extends('judge_app.layouts.app')
@section('content')
    <div class="row mt-4">
        @if ($stage == 1)
            @livewire('judge.score-board-component', [ 'stage' => $stage ])

        @elseif ($stage == 2)
            @livewire('judge.preliminaries.score-board-component', [ 'stage' => $stage ])

        @elseif ($stage == 3)
            @livewire('judge.semifinal.score-board-component', [ 'stage' => $stage ])

        @elseif ($stage == 4)
            @livewire('judge.final.score-board-component', [ 'stage' => $stage ])

        @endif
    </div>


@endsection
