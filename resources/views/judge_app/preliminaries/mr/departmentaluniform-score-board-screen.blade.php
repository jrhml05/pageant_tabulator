@extends('judge_app.layouts.app')

@section('title', 'Departmental uniform · Mr. LCUAA')

@section('content')
    @livewire('judge.preliminaries.mr.departmentaluniform-score-board-component', ['stage' => $stage])
@endsection
