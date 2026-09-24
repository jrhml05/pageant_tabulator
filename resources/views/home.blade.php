@extends('layouts.master')

@section('title', 'Scoring status')

@section('content')
    {{-- The tabulator's question on this screen: which categories can I rank yet? Lock status leads; counts are context. --}}
    <x-page-header title="Scoring status" />

    <p class="-mt-3 mb-6 max-w-prose text-sm text-ink-2">
        A judge's sheet is locked once they press <span class="font-medium text-ink">Lock in scores</span>.
        Rank a category when every judge has locked it: open it from the table below.
        <span class="mt-2 block">
            <a href="{{ route('candidates.index') }}" class="font-medium text-ink underline-offset-4 hover:underline">{{ $data['ms_count'] }} Ms. and {{ $data['mr_count'] }} Mr. candidates</a>
            ·
            <a href="{{ route('judges.index') }}" class="font-medium text-ink underline-offset-4 hover:underline">{{ $data['judges']->count() }} {{ Str::plural('judge', $data['judges']->count()) }}</a>
        </span>
    </p>

    @if ($data['judges']->isEmpty())
        <div class="card px-5 py-8 text-center text-ink-2">
            No judge accounts yet. <a href="{{ route('judges.create') }}" class="font-medium text-accent underline underline-offset-4">Add a judge</a> to start tracking locks.
        </div>
    @else
        <div class="flex flex-col gap-4">
            @forelse ($data['stages'] as $stage)
                <details class="card group" @if ($stage['active']) open @endif>
                    <summary class="flex min-h-14 cursor-pointer list-none items-center justify-between gap-3 px-5 [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center gap-3">
                            <span class="font-semibold">{{ $stage['name'] }}</span>
                            @if ($stage['active'])
                                <span class="rounded bg-accent-soft px-2 py-0.5 text-xs font-medium text-accent-soft-ink">Open to judges</span>
                            @endif
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs text-ink-2 transition-transform group-open:rotate-180" aria-hidden="true"></i>
                    </summary>

                    <div class="overflow-x-auto border-t border-line">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th scope="col">Category</th>
                                    <th scope="col" class="text-left!">Ms. LCUAA</th>
                                    <th scope="col" class="text-left!">Mr. LCUAA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stage['categories'] as $category)
                                    <tr>
                                        <td>{{ $category['label'] }}</td>
                                        @foreach (['ms', 'mr'] as $division)
                                            @php
                                                $locked = $category[$division];
                                                $waiting = $data['judges']->whereNotIn('id', $locked->pluck('id'));
                                                $done = $waiting->isEmpty();
                                            @endphp
                                            <td class="text-left!">
                                                <a href="{{ route("{$division}_{$category['report']}") }}" class="group/cell -mx-1 block rounded px-1 py-0.5 hover:bg-surface">
                                                    <span class="inline-flex items-center gap-2 font-medium {{ $done ? 'text-success-ink' : '' }}">
                                                        <i class="fa-solid {{ $done ? 'fa-lock' : 'fa-lock-open text-ink-2' }}" aria-hidden="true"></i>
                                                        {{ $done ? 'All locked, ready to rank' : $locked->count() . ' of ' . $data['judges']->count() . ' locked' }}
                                                    </span>
                                                    <span class="block text-xs text-ink-2 group-hover/cell:underline">
                                                        {{ $done ? 'Open results' : 'Waiting on ' . $waiting->pluck('name')->join(', ', ' and ') }}
                                                    </span>
                                                </a>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
            @empty
                <div class="card px-5 py-8 text-center text-ink-2">No stages set up. Run <code>php artisan db:seed --class=StageSeeder</code> to create them.</div>
            @endforelse
        </div>
    @endif
@endsection
