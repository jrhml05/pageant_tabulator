@extends('judge_app.layouts.app')

@section('title', 'Departmental uniform · Ms. LCUAA')

@section('content')
    @livewire('judge.preliminaries.ms.departmentaluniform-score-board-component', ['stage' => $stage])
@endsection
