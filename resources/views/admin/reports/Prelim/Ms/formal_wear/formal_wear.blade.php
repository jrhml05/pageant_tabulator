@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <h1 class="h3 mb-0 text-gray-800">MS. UEP - LONG GOWN RESULT</h1>

        <div>

            <a href="{{ route('ms_formal_wear_judge1') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 1 Scores</a>

            <a href="{{ route('ms_formal_wear_judge2') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 2 Scores</a>

            <a href="{{ route('ms_formal_wear_judge3') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 3 Scores</a>

            <a href="{{ route('ms_formal_wear_judge4') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 4 Scores</a>

            <a href="{{ route('ms_formal_wear_judge5') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 5 Scores</a>

        </div>

        <div>

            <a href="javascript:void(0)" onclick="prelimrank()" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-ranking-star fa-sm text-white-50"></i> RANK CANDIDATES</a>
            {{-- <a href="{{ route('prelimrank') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-ranking-star fa-sm text-white-50"></i> RANK CANDIDATES</a> --}}

            <a href="{{ route('ms_pdfformal_wear') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
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
                                        <th style="width:10%">CANDIDATE #</th>
                                        <th style="width:10%">JUDGE 1</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:10%">JUDGE 2</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:10%">JUDGE 3</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:10%">JUDGE 4</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:10%">JUDGE 5</th>
                                        <th style="width:5%">RANK</th>
                                        {{-- <th style="width:20%">RESULT</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['candidate'] as $candidate)
                                        <tr>
                                            <td>{{ strtoupper($candidate->id) }}</td>
                                            @foreach ( $candidate->formal_wear_score as $score)

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)

                                                    @php
                                                        $score_judge1 = $score->elegance + $score->presence + $score->projection + $score->poise;
                                                    @endphp

                                                    <td>{{ $score_judge1 }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)
                                                            <td>{{ $rank->prelim_rank }}</td>
                                                        @endif

                                                    @empty
                                                        <td></td>
                                                    @endforelse

                                                    {{-- <td></td> --}}

                                                @endif

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)

                                                    @php
                                                        $score_judge2 = $score->elegance + $score->presence + $score->projection + $score->poise;
                                                    @endphp

                                                    <td>{{ $score_judge2 }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)
                                                            <td>{{ $rank->prelim_rank }}</td>
                                                        @endif

                                                    @empty
                                                        <td></td>
                                                    @endforelse

                                                @endif

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)

                                                    @php
                                                        $score_judge3 = $score->elegance + $score->presence + $score->projection + $score->poise;
                                                    @endphp

                                                    <td>{{ $score_judge3 }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)
                                                            <td>{{ $rank->prelim_rank }}</td>
                                                        @endif

                                                    @empty
                                                        <td></td>
                                                    @endforelse

                                                @endif

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 5)

                                                    @php
                                                        $score_judge3 = $score->elegance + $score->presence + $score->projection + $score->poise;
                                                    @endphp

                                                    <td>{{ $score_judge3 }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 5)
                                                            <td>{{ $rank->prelim_rank }}</td>
                                                        @endif

                                                    @empty
                                                        <td></td>
                                                    @endforelse

                                                @endif

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 6)

                                                    @php
                                                        $score_judge3 = $score->elegance + $score->presence + $score->projection + $score->poise;
                                                    @endphp

                                                    <td>{{ $score_judge3 }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 6)
                                                            <td>{{ $rank->prelim_rank }}</td>
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

        function prelimrank()
        {
            alert("prelim rank");

            $.ajax({
                type: "GET",
                url: "/prelimrank",
                success: function (response) {
                    location.reload();
                },
                error: function (response)
                {
                    alert("no prelim rank");
                    console.log(response);
                }
            });
        }


    </script>
@endsection
