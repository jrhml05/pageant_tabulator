@extends('layouts.master')

@section('title', 'Pre-pageant · Judge 2 · Mr. LCUAA')

@section('content')
    <x-report-header division="mr" stage="Pre-pageant" title="Pre-pageant" route="mr_prepageant" judge="2" :print="route('mr_pdfprepageant_judge2')" />
    <x-table-card>
        <table class="data-table data-table-ranked">
            <thead>
                <tr>
                    <th scope="col" style="width:10%">Candidate</th>
                    <th scope="col" style="width:15%">Rave wear 50%</th>
                    <th scope="col" style="width:15%">Talent 50%</th>
                    <th scope="col" style="width:10%">Total 100%</th>
                    <th scope="col" style="width:10%">Rank</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data['candidate'] as $candidate)
                    <tr>
                        <td>{{ strtoupper($candidate->name) }}</td>
                        @foreach ( $candidate->prepageant_score as $score)

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)

                                <td>{{ $score->rave_wear }}</td>
                                <td>{{ $score->talent }}</td>
                                <td>{{ $score->rave_wear + $score->talent }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)
                                        <td>{{ $rank->prepageant }}</td>
                                    @endif

                                @empty
                                    <td></td>
                                @endforelse

                                {{-- <td></td> --}}

                            @endif

                        @endforeach
                        {{-- <td>{{ ROUND(($score_judge1 + $score_judge2 + $score_judge3) / 3, 2) }}</td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="data-table-empty">No candidates in the database. Run <code>php artisan db:seed</code>, then reload this page.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-table-card>
@endsection
