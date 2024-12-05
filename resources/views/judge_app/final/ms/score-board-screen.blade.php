@extends('judge_app.layouts.app')
@section('content')
<style>
    .col-lg-5-col {
        flex: 0 0 20%; /* Take 20% of the row's width */
        max-width: 20%; /* Ensure max width is also 20% */
    }
</style>
    <div class="row pt-5">
        @if ($stage == 1)
            {{-- @livewire('judge.prepageant.mr.score-board-component', [ 'stage' => $stage ]) --}}
            @livewire('judge.prepageant.ms.score-board-component', [ 'stage' => $stage ])

        @elseif ($stage == 2)
            @livewire('judge.preliminaries.ms.score-board-component', [ 'stage' => $stage ])

        @elseif ($stage == 3)
            @livewire('judge.final.ms.score-board-component', [ 'stage' => $stage ])

        @endif
    </div>


@endsection
