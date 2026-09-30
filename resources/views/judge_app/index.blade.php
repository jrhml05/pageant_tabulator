@extends('judge_app.layouts.app')

@section('title', 'Waiting')
@section('waiting', 'true')

@section('content')
    <div class="mx-auto mt-10 max-w-xl text-center">
        @if (! $judge->panel)
            <h1 class="text-2xl font-semibold tracking-tight">You're not on a judging panel yet</h1>
            <p class="mt-2 text-ink-2">Ask the tabulator to assign you to the pre-pageant or pageant night panel, then reload this page.</p>
            <a href="{{ route('judge.app') }}" class="btn btn-secondary btn-lg mt-6">Reload</a>
        @else
            <i class="fa-solid fa-hourglass-half text-3xl text-ink-2" aria-hidden="true"></i>
            <h1 class="mt-4 text-2xl font-semibold tracking-tight">Nothing to score right now</h1>
            <p class="mt-2 text-ink-2">
                This screen opens the score sheet by itself as soon as the tabulator starts a segment. Keep it open.
            </p>

            <ul class="card mt-8 divide-y divide-line text-left">
                @foreach ($segments as $segment)
                    <li class="flex min-h-12 items-center justify-between gap-3 px-4 py-2">
                        <span class="font-medium">{{ $segment->label }}</span>
                        <span class="shrink-0 text-sm text-ink-2">
                            @if ($locked->contains($segment->key))
                                <i class="fa-solid fa-lock" aria-hidden="true"></i> Locked in
                            @else
                                Not started
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
