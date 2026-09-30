@extends('layouts.master')

@section('title', 'Judges')

@section('content')
    <x-page-header title="Judges">
        <x-slot:actions>
            <a href="{{ route('judges.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add judge
            </a>
        </x-slot:actions>
    </x-page-header>

    <p class="-mt-3 mb-5 max-w-prose text-sm text-ink-2">
        Results number each panel's judges as Judge 1, 2, 3 in the order their accounts were added.
    </p>

    <x-flash />

    @if ($data['unassigned']->isNotEmpty())
        <div role="alert" class="mb-5 rounded-md bg-danger-soft px-4 py-3 text-sm font-medium text-danger">
            Not on a panel, so they can't score anything:
            @foreach ($data['unassigned'] as $judge)
                <a href="{{ route('judges.edit', $judge->id) }}" class="underline underline-offset-4">{{ $judge->name }}</a>{{ $loop->last ? '.' : ',' }}
            @endforeach
        </div>
    @endif

    @foreach (config('pageant.panels') as $panel => $panelLabel)
        <section aria-labelledby="{{ $panel }}-heading" class="mb-8">
            <h2 id="{{ $panel }}-heading" class="mb-3 text-lg font-semibold">
                {{ $panelLabel }} panel <span class="font-normal text-ink-2">({{ $data['panels'][$panel]->count() }})</span>
            </h2>
            <x-table-card>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Seat</th>
                            <th scope="col" class="text-left!">Name</th>
                            <th scope="col" class="text-left!">Username</th>
                            <th scope="col"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data['panels'][$panel] as $judge)
                            <tr>
                                <td>Judge {{ $loop->iteration }}</td>
                                <td class="text-left!">{{ $judge->name }}</td>
                                <td class="text-left!">{{ $judge->username }}</td>
                                <td class="text-right!">
                                    <a href="{{ route('judges.edit', $judge->id) }}" class="btn btn-ghost">
                                        <i class="fa-solid fa-pen" aria-hidden="true"></i> Edit
                                        <span class="sr-only">{{ $judge->name }}</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="data-table-empty">No judges on this panel yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-table-card>
        </section>
    @endforeach
@endsection
