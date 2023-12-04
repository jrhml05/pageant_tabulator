@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">MS. UEP - PRODUCTION NUMBER RESULT</h1>

        <div>

            <a href="{{ route('ms_prod_num_judge1') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 1 Production Number Scores</a>

            <a href="{{ route('ms_prod_num_judge2') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 2 Production Number Scores</a>

            <a href="{{ route('ms_prod_num_judge3') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 3 Production Number Scores</a>

        </div>

        <div>

            <a href="javascript:void(0)" onclick="ms_prod_num_rank()" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fa-solid fa-ranking-star fa-sm text-white-50"></i> RANK CANDIDATES</a>

            {{-- <a  class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fa-solid fa-ranking-star fa-sm text-white-50"></i> RANK CANDIDATES</a> --}}

            <a href="{{ route('ms_pdfprod_num') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-print fa-sm text-white-50"></i> PRINT RESULTS</a>
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
                                        <th style="width:15%">CANDIDATE #</th>
                                        <th style="width:15%">JUDGE 1</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:15%">JUDGE 2</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:15%">JUDGE 3</th>
                                        <th style="width:5%">RANK</th>
                                        {{-- <th style="width:20%">RESULT</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['candidate'] as $candidate)

                                        <tr>
                                            <td>{{ strtoupper($candidate->id) }}</td>

                                            @foreach ( $candidate->prod_num_score as $score)

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)

                                                    @php
                                                        $score_judge1 = $score->mastery + $score->poise + $score->stage_presence;
                                                    @endphp

                                                    <td>{{ $score_judge1 }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)

                                                            <td>{{ $rank->prod_num }}</td>

                                                        @endif

                                                    @empty

                                                        <td></td>

                                                    @endforelse

                                                @endif

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)

                                                    @php
                                                        $score_judge2 = $score->mastery + $score->poise + $score->stage_presence;
                                                    @endphp

                                                    <td>{{ $score_judge2 }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)

                                                            <td>{{ $rank->prod_num }}</td>

                                                        @endif

                                                    @empty

                                                        <td></td>

                                                    @endforelse

                                                @endif

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)

                                                    @php
                                                        $score_judge3 = $score->mastery + $score->poise + $score->stage_presence;
                                                    @endphp

                                                    <td>{{ $score_judge3 }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)

                                                            <td>{{ $rank->prod_num }}</td>

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

    <script>

        function ms_prod_num_rank()
        {
            alert("talent rank");

            $.ajax({
                type: "GET",
                url: "/ms_prod_num_rank",
                success: function (response) {
                    location.reload();
                },
                error: function ()
                {
                    alert("no talent rank");
                }
            });
        }

    </script>

@endsection
