@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">MS. UEP - DEPARTMENTAL UNIFORM SCORES (JUDGE 3)</h1>

        <div>

            <a href="{{ route('ms_departmental_uniform_judge1') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 1 Departmental Uniform Scores</a>

            <a href="{{ route('ms_departmental_uniform_judge2') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 2 Departmental Uniform Scores</a>

            <a href="{{ route('ms_departmental_uniform_judge3') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 3 Departmental Uniform Scores</a>

        </div>

        <div>
            <a href="{{ route('ms_departmental_uniform') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-less-than fa-sm text-white-50"></i> BACK TO OVERALL DEPARTMENTAL UNIFORM RESULTS</a>
            <a href="{{ route('ms_pdfdepartmental_uniformjudge3') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-print fa-sm text-white-50"></i> PRINT SCORES JUDGE 3</a>
        </div>

    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width:10%">CANDIDATE</th>
                                        <th style="width:15%">PRESENTATION & NEATNESS 40%</th>
                                        <th style="width:15%">FIGURE 30%</th>
                                        <th style="width:15%">BEAUTY & POISE 20%</th>
                                        <th style="width:15%">OVERALL IMPACT 10%</th>
                                        <th style="width:10%">TOTAL 100%</th>
                                        <th style="width:10%">RANK</th>

                                        {{-- <th style="width:20%">RESULT</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['candidate'] as $candidate)

                                        <tr>
                                            <td>{{ strtoupper($candidate->id) }}</td>

                                            @foreach ( $candidate->departmental_uniform_score as $score)

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)

                                                    <td>{{ $score->presentation }}</td>
                                                    <td>{{ $score->figure }}</td>
                                                    <td>{{ $score->beauty_poise }}</td>
                                                    <td>{{ $score->overall_impact }}</td>

                                                    <td>{{ $score->presentation + $score->figure + $score->beauty_poise + $score->overall_impact }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)

                                                            <td>{{ $rank->dept_uniform }}</td>

                                                        @endif

                                                    @empty

                                                        <td></td>

                                                    @endforelse

                                                @endif

                                            @endforeach
                                            {{-- <td>{{ ROUND(($score_judge1 + $score_judge2 + $score_judge3) / 3, 2) }}</td> --}}
                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="6">
                                                <center>No Data Found</center>
                                            </td>
                                        </tr>

                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
