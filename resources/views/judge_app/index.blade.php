@extends('judge_app.layouts.app')

@section('title', 'Home')

@section('content')
    @php
        $stage = \App\Models\Stage::where('is_active', 1)->orderBy('id')->first();
        $boardRoutes = [1 => '', 2 => '.prelim', 3 => '.final'];
    @endphp

    <h1 class="text-2xl font-semibold tracking-tight">Welcome, {{ Auth::user()->name }}</h1>

    @if (! $stage || ! isset($boardRoutes[$stage->id]))
        <div class="card mt-6 max-w-xl px-5 py-8">
            <p class="font-medium">No stage is open for scoring yet.</p>
            <p class="mt-1 text-ink-2">The tabulator opens a stage when the segment starts. Reload this page then.</p>
            <a href="{{ route('judge.app') }}" class="btn btn-secondary btn-lg mt-5">Reload</a>
        </div>
    @else
        <p class="mt-1 text-ink-2">{{ $stage->stage_name }} is open. Pick the division you are scoring.</p>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            @foreach (['ms' => ['Ms. LCUAA', \App\Models\Ms_candidate::class], 'mr' => ['Mr. LCUAA', \App\Models\Mr_candidate::class]] as $d => [$label, $model])
                @php $count = $stage->id == 3 ? $model::where('is_active', 1)->count() : $model::count(); @endphp
                <a href="{{ route("judge.app.{$d}{$boardRoutes[$stage->id]}.score", $stage->id) }}"
                    class="card group flex items-center justify-between gap-4 p-6 transition-colors hover:border-accent">
                    <span>
                        <span class="block text-xl font-semibold">{{ $label }}</span>
                        <span class="text-ink-2">{{ $count }} {{ $stage->id == 3 ? 'finalists' : 'candidates' }}</span>
                    </span>
                    <i class="fa-solid fa-chevron-right text-ink-2 group-hover:text-accent" aria-hidden="true"></i>
                </a>
            @endforeach
        </div>
    @endif
@endsection
