@extends('layouts.master')

@section('title', 'Final · Ms. LCUAA')

@section('content')
    <x-report-header division="ms" stage="Final" title="Final" route="ms_final" :print="route('ms_pdffinal')" rank="/ms_final_rank">
        <button type="button" class="btn btn-secondary" data-action-url="/ms_final_score_seeder"
            data-busy-label="Creating…" data-error-label="Creating score sheets"
            data-confirm="This deletes every final score already entered and creates blank sheets for the current finalists. Continue?">
            <i class="fa-solid fa-table-list" aria-hidden="true"></i>
            <span data-label>Create final score sheets</span>
        </button>
    </x-report-header>
    @if ($data['candidate']->every(fn ($candidate) => $candidate->final_score->isEmpty()))
        <div class="card max-w-2xl px-5 py-6">
            <p class="font-medium">No final score sheets yet.</p>
            <p class="mt-1 text-sm text-ink-2">
                On the Preliminaries results, press <span class="font-medium text-ink">Mark top 5 as finalists</span>.
                Then press <span class="font-medium text-ink">Create final score sheets</span> above so judges can score the final.
            </p>
        </div>
    @else
    <x-table-card>
        <table class="data-table data-table-ranked">
            <thead>
                <tr>
                    <th scope="col" style="width:10%">Candidate</th>
                    <th scope="col" style="width:10%">Judge 1</th>
                    <th scope="col" style="width:5%">Rank</th>
                    <th scope="col" style="width:10%">Judge 2</th>
                    <th scope="col" style="width:5%">Rank</th>
                    <th scope="col" style="width:10%">Judge 3</th>
                    <th scope="col" style="width:5%">Rank</th>
                    <th scope="col" style="width:20%">Final rank</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data['candidate'] as $candidate)
                    <tr>
                        <td>{{ strtoupper($candidate->name) }}</td>
                        @foreach ( $candidate->final_score as $score)

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)

                                @php
                                    $score_judge1 = $score->wit + $score->projection + $score->stage_presence + $score->overall_impact;
                                @endphp

                                <td>{{ $score_judge1 }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)
                                        <td>{{ $rank->final }}</td>
                                    @endif

                                @empty
                                    <td></td>
                                @endforelse

                                {{-- <td></td> --}}

                            @endif

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)

                                @php
                                    $score_judge2 = $score->wit + $score->projection + $score->stage_presence + $score->overall_impact;
                                @endphp

                                <td>{{ $score_judge2 }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)
                                        <td>{{ $rank->final }}</td>
                                    @endif

                                @empty
                                    <td></td>
                                @endforelse

                            @endif

                            @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)

                                @php
                                    $score_judge3 = $score->wit + $score->projection + $score->stage_presence + $score->overall_impact;
                                @endphp

                                <td>{{ $score_judge3 }}</td>

                                @forelse   ($data['rank'] as $rank)

                                    @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)
                                        <td>{{ $rank->final }}</td>
                                    @endif

                                @empty
                                    <td></td>
                                @endforelse

                            @endif



                        @endforeach


                        @forelse   ($data['final_rank'] as $final_rank)


                            @isset($score)
                                @if ($final_rank->candidate_id == $score->candidate_id )

                                    <td style="width:5%; text-align: center">{{ $final_rank->final }}</td>

                                @endif
                            @endisset

                        @empty

                            <td></td>

                        @endforelse

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="data-table-empty">No candidates in the database. Run <code>php artisan db:seed</code>, then reload this page.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-table-card>
    @endif
@endsection
