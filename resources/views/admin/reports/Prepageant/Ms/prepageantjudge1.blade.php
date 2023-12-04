@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <h1 class="h3 mb-0 text-gray-800">MS. UEP - PRE-PAGEANT SCORES (JUDGE 1)</h1>

        <div>

            <a href="{{ route('ms_prepageant_judge1') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 1 Scores</a>

            <a href="{{ route('ms_prepageant_judge2') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 2 Scores</a>

            <a href="{{ route('ms_prepageant_judge3') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 3 Scores</a>

        </div>

        <div>
            <a href="{{ route('ms_prepageant') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-less-than fa-sm text-white-50"></i> BACK TO OVERALL PREPAGEANT RESULTS</a>
            <a href="{{ route('ms_pdfprepageant_judge1') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-print fa-sm text-white-50"></i> PRINT JUDGE 1 SCORES</a>
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
                                        <th style="width:15%">PRODUCTION NUMBER 30%</th>
                                        <th style="width:15%">SPORTS WEAR 30%</th>
                                        <th style="width:15%">TALENT 40%</th>
                                        <th style="width:10%">TOTAL 100%</th>
                                        <th style="width:10%">RANK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['candidate'] as $candidate)
                                        <tr>
                                            <td>{{ strtoupper($candidate->id) }}</td>
                                            @foreach ( $candidate->prepageant_score as $score)

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)

                                                    <td>{{ $score->production_number }}</td>
                                                    <td>{{ $score->sports_wear }}</td>
                                                    <td>{{ $score->talent }}</td>
                                                    <td>{{ $score->production_number + $score->sports_wear + $score->talent }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)
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
