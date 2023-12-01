@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">MR. UEP - FORMAL WEAR SCORES (JUDGE 4)</h1>

        <div>

            <a href="{{ route('mr_formal_wear_judge1') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 1 Formal Wear Scores</a>

            <a href="{{ route('mr_formal_wear_judge2') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 2 Formal Wear Scores</a>

            <a href="{{ route('mr_formal_wear_judge3') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 3 Formal Wear Scores</a>

            <a href="{{ route('mr_formal_wear_judge4') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 4 Formal Wear Scores</a>

            <a href="{{ route('mr_formal_wear_judge5') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 5 Formal Wear Scores</a>

        </div>

        <div>
            <a href="{{ route('mr_formal_wear') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-less-than fa-sm text-white-50"></i> BACK TO OVERALL FORMAL WEAR RESULTS</a>
            <a href="{{ route('mr_pdfformal_wearjudge4') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-print fa-sm text-white-50"></i> PRINT SCORES JUDGE 4</a>
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
                                        <th style="width:15%">ELEGANCE 25%</th>
                                        <th style="width:15%">STAGE PRESENCE 25%</th>
                                        <th style="width:15%">PROJECTION 25%</th>
                                        <th style="width:15%">POISE 25%</th>
                                        <th style="width:10%">TOTAL 100%</th>
                                        <th style="width:10%">RANK</th>

                                        {{-- <th style="width:20%">RESULT</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['candidate'] as $candidate)

                                        <tr>
                                            <td>{{ strtoupper($candidate->id) }}</td>

                                            @foreach ( $candidate->formal_wear_score as $score)

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 5)

                                                    <td>{{ $score->elegance }}</td>
                                                    <td>{{ $score->presence }}</td>
                                                    <td>{{ $score->projection }}</td>
                                                    <td>{{ $score->poise }}</td>

                                                    <td>{{ $score->elegance + $score->presence + $score->projection + $score->poise }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 5)

                                                            <td>{{ $rank->casual_wear }}</td>

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
