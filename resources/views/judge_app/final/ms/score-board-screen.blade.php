@extends('judge_app.layouts.app')

@php
    $board = [1 => 'prepageant', 2 => 'preliminaries', 3 => 'final'][$stage] ?? null;
@endphp

@section('title', 'Ms. LCUAA')

@section('content')
    @if ($board)
        @livewire("judge.{$board}.ms.score-board-component", ['stage' => $stage])
    @else
        <div class="card max-w-xl px-5 py-8">
            <p class="font-medium">This stage has no score board.</p>
            <a href="{{ route('judge.app') }}" class="btn btn-secondary btn-lg mt-5">Back to home</a>
        </div>
    @endif
@endsection
