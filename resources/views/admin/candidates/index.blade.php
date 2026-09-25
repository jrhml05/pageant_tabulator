@extends('layouts.master')

@section('title', 'Candidates')

@section('content')
    <x-page-header title="Candidates" />

    <p class="-mt-3 mb-6 max-w-prose text-sm text-ink-2">
        Judges see each candidate by number and photo. Adding a candidate also creates their empty score sheets for every judge.
    </p>

    <x-flash />

    @foreach (['ms' => 'Ms. LCUAA', 'mr' => 'Mr. LCUAA'] as $division => $label)
        <section aria-labelledby="{{ $division }}-heading" class="mb-10">
            <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                <h2 id="{{ $division }}-heading" class="text-lg font-semibold">
                    {{ $label }} <span class="font-normal text-ink-2">({{ $data[$division]->count() }})</span>
                </h2>
                <a href="{{ route('candidates.create', $division) }}" class="btn btn-secondary">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Add {{ $division === 'mr' ? 'Mr.' : 'Ms.' }} candidate
                </a>
            </div>

            @if ($data[$division]->isEmpty())
                <div class="card px-5 py-8 text-center text-ink-2">No {{ $label }} candidates yet.</div>
            @else
                <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6">
                    @foreach ($data[$division] as $candidate)
                        <li class="card overflow-hidden">
                            <x-candidate-photo :division="$division" :number="$candidate->id" />
                            <div class="flex items-center justify-between gap-1 py-1 pr-1 pl-3">
                                <p class="font-semibold tabular-nums">No. {{ $candidate->name }}</p>
                                <a href="{{ route('candidates.edit', [$division, $candidate->id]) }}" class="btn btn-ghost px-2.5">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                    <span class="sr-only">Edit {{ $label }} No. {{ $candidate->id }}</span>
                                </a>
                            </div>
                            @if ($candidate->department)
                                <p class="-mt-1 px-3 pb-2.5 text-sm text-ink-2">{{ $candidate->department }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    @endforeach
@endsection
