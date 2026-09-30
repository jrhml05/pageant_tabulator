@extends('admin.results.pdf.layout')

@php $showSchool = $divisions->flatten(1)->contains(fn ($c) => filled($c->school)); @endphp

@section('eyebrow', 'For the emcee')
@section('heading', 'Top 5 finalists')

@section('content')
    <p class="note">Call the finalists in this order. The order is random: it is not the ranking and not the candidate numbers.</p>

    {{-- Divisions side by side so the whole announcement fits on one sheet. --}}
    <table class="columns">
        <tr>
            @foreach ($divisions as $division => $finalists)
                <td class="column" style="{{ $loop->first ? 'padding-right: 12pt;' : 'padding-left: 12pt;' }}">
                    <h2>{{ config("pageant.divisions.{$division}") }}</h2>
                    <table class="sheet announce">
                        <thead>
                            <tr>
                                <th style="width: 18%;">Call</th>
                                <th style="width: 30%;">Candidate</th>
                                @if ($showSchool)
                                    <th class="school">College/University</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($finalists as $candidate)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>No. {{ $candidate->number }}</td>
                                    @if ($showSchool)
                                        <td class="school">{{ $candidate->school }}</td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $showSchool ? 3 : 2 }}" class="empty">No finalists saved yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </td>
            @endforeach
        </tr>
    </table>
@endsection

@section('signatures')
    @include('admin.results.pdf.signatures', ['signers' => ['Tabulator']])
@endsection
