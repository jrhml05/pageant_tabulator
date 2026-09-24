@extends('layouts.master')

@section('title', 'Stages')

@section('content')
    <x-page-header title="Stages" />
    <p class="-mt-3 mb-6 max-w-prose text-sm text-ink-2">
        Open the stage the judges should score now. Their tablets show the first open stage when they load the scoring app.
    </p>
    @livewire('admin.stage-controller-component')
@endsection
