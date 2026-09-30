@extends('layouts.master')

@section('title', 'Edit candidate')

@php
    $candidate = $data['candidate'];
    $name = "{$data['label']} No. {$candidate->number}";
@endphp

@section('content')
    <x-page-header :title="'No. '.$candidate->number" :eyebrow="$data['label']" />
    <x-flash />
    @include('admin.candidates.form', ['candidate' => $candidate, 'action' => route('candidates.update', [$data['division'], $candidate->number])])

    <section aria-labelledby="delete-heading" class="card mt-8 max-w-xl p-5 sm:p-6">
        <h2 id="delete-heading" class="font-semibold">Delete candidate</h2>
        <p class="mt-1.5 text-sm text-ink-2">
            @if ($data['scored'])
                Judges have already scored {{ $name }}. Deleting removes those scores too, and can't be undone.
            @else
                No judge has scored {{ $name }} yet.
            @endif
        </p>
        <form action="{{ route('candidates.destroy', [$data['division'], $candidate->number]) }}" method="POST" class="mt-4"
            data-confirm="Delete {{ $name }}{{ $data['scored'] ? ' and all of their scores' : '' }}? This can't be undone.">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fa-solid fa-trash" aria-hidden="true"></i> Delete {{ $name }}
            </button>
        </form>
    </section>
@endsection
