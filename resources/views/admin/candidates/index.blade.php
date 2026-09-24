@extends('layouts.master')

@section('title', 'Candidates')

@section('content')
    <x-page-header title="Candidates" />

    <p class="-mt-3 mb-6 max-w-prose text-sm text-ink-2">
        Photos come from <code class="text-ink">public/assets/img/ms</code> and <code class="text-ink">public/assets/img/mr</code>, named by candidate number.
    </p>

    @foreach (['ms' => 'Ms. LCUAA', 'mr' => 'Mr. LCUAA'] as $division => $label)
        <section aria-labelledby="{{ $division }}-heading" class="mb-10">
            <h2 id="{{ $division }}-heading" class="mb-3 text-lg font-semibold">
                {{ $label }} <span class="font-normal text-ink-2">({{ $data[$division]->count() }})</span>
            </h2>

            @if ($data[$division]->isEmpty())
                <div class="card px-5 py-8 text-center text-ink-2">No {{ $label }} candidates. Run <code>php artisan db:seed --class={{ $division === 'mr' ? 'MrCandidateSeeder' : 'MsCandidateSeeder' }}</code> to add them.</div>
            @else
                <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6">
                    @foreach ($data[$division] as $candidate)
                        <li class="card overflow-hidden">
                            <x-candidate-photo :division="$division" :number="$candidate->id" />
                            <p class="px-3 py-2.5 font-semibold tabular-nums">No. {{ $candidate->name }}</p>
                            @if ($candidate->department)
                                <p class="-mt-1.5 px-3 pb-2.5 text-sm text-ink-2">{{ $candidate->department }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    @endforeach
@endsection
