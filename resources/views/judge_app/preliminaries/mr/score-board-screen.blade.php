@extends('judge_app.layouts.app')
@section('content')
    <div class="row pt-5">
        @if ($stage == 1)
            {{-- @livewire('judge.prepageant.mr.score-board-component', [ 'stage' => $stage ]) --}}
            @livewire('judge.prepageant.mr.score-board-component', [ 'stage' => $stage ])

        @elseif ($stage == 2)
            @livewire('judge.preliminaries.mr.score-board-component', [ 'stage' => $stage ])

        @elseif ($stage == 3)
            @livewire('judge.semifinal.score-board-component', [ 'stage' => $stage ])

        @elseif ($stage == 4)
            @livewire('judge.final.score-board-component', [ 'stage' => $stage ])

        @endif
    </div>


@endsection
