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
        Reports show the first three judge accounts as Judge 1, 2 and 3, in the order they were created.
    </p>

    <x-flash />

    <x-table-card>
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">Judge</th>
                    <th scope="col" class="text-left!">Name</th>
                    <th scope="col" class="text-left!">Email</th>
                    <th scope="col"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data['records'] as $judge)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-left!">{{ $judge->name }}</td>
                        <td class="text-left!">{{ $judge->email }}</td>
                        <td class="text-right!">
                            <a href="{{ route('judges.edit', $judge->id) }}" class="btn btn-ghost">
                                <i class="fa-solid fa-pen" aria-hidden="true"></i> Edit
                                <span class="sr-only">{{ $judge->name }}</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="data-table-empty">No judges yet. Add one so they can sign in and score.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-table-card>
@endsection
