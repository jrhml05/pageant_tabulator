@extends('judge_app.layouts.app')

@section('title', 'Casual Q&A · Ms. LCUAA')

@section('content')
    @livewire('judge.preliminaries.ms.qna-score-board-component', ['stage' => $stage])
@endsection
