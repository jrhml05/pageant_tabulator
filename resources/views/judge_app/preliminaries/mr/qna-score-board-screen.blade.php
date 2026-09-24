@extends('judge_app.layouts.app')

@section('title', 'Casual Q&A · Mr. LCUAA')

@section('content')
    @livewire('judge.preliminaries.mr.qna-score-board-component', ['stage' => $stage])
@endsection
