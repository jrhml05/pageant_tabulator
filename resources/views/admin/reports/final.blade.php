@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <h1 class="h3 mb-0 text-gray-800">FINALS SCORES</h1>

        <div>

            <a href="{{ route('final_judge1') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 1 Scores</a>

            <a href="{{ route('final_judge2') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 2 Scores</a>

            <a href="{{ route('final_judge3') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 3 Scores</a>
            <a href="{{ route('final_judge4') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 4 Scores</a>
            <a href="{{ route('final_judge5') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 5 Scores</a>

        </div>

        <div>

            <a href="javascript:void(0)" onclick="finalrank()" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-ranking-star fa-sm text-white-50"></i> RANK CANDIDATES</a>

            <a href="{{ route('pdffinal') }}" target="_blank" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-print fa-sm text-white-50"></i> PRINT SCORES</a>

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
                                        <th style="width:5%; text-align: center">#</th>
                                        <th style="width:15%">BARANGAY</th>
                                        <th style="width:7%">JUDGE 1</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:7%">JUDGE 2</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:7%">JUDGE 3</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:7%">JUDGE 4</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:7%">JUDGE 5</th>
                                        <th style="width:5%">RANK</th>
                                        {{-- <th style="width:20%">RESULT</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['brgy'] as $brgy)

                                        @if($brgy->is_active == 1)
                                            {{-- {{ $brgy->finalrankings->barangay_id }} --}}

                                            <tr>
                                                <td style="width:5%; text-align: center">
                                                    {{-- <strong>#{{ $loop->iteration }}</strong> --}}
                                                    <strong>#{{ $brgy->id }}</strong>
                                                </td>
                                                <td>{{ strtoupper($brgy->name) }}</td>
                                                @foreach ( $brgy->finalscores as $score)

                                                    @if ($brgy->id == $score->barangay_id && $score->judge_id == 2)

                                                        @php
                                                            $score_judge1 = $score->beauty + $score->intelligence;
                                                        @endphp

                                                        <td>{{ $score_judge1 }}</td>

                                                        @forelse   ($data['rank'] as $rank)

                                                            @if ($rank->barangay_id == $score->barangay_id && $rank->judge_id == 2)
                                                                <td>{{ $rank->final_rank }}</td>
                                                            @endif

                                                        @empty
                                                            <td></td>
                                                        @endforelse

                                                        {{-- <td></td> --}}

                                                    @endif

                                                    @if ($brgy->id == $score->barangay_id && $score->judge_id == 3)

                                                        @php
                                                            $score_judge1 = $score->beauty + $score->intelligence;
                                                        @endphp

                                                        <td>{{ $score_judge1 }}</td>

                                                        @forelse   ($data['rank'] as $rank)

                                                            @if ($rank->barangay_id == $score->barangay_id && $rank->judge_id == 3)
                                                                <td>{{ $rank->final_rank }}</td>
                                                            @endif

                                                        @empty
                                                            <td></td>
                                                        @endforelse

                                                        {{-- <td></td> --}}

                                                    @endif

                                                    @if ($brgy->id == $score->barangay_id && $score->judge_id == 4)

                                                        @php
                                                            $score_judge1 = $score->beauty + $score->intelligence;
                                                        @endphp

                                                        <td>{{ $score_judge1 }}</td>

                                                        @forelse   ($data['rank'] as $rank)

                                                            @if ($rank->barangay_id == $score->barangay_id && $rank->judge_id == 4)
                                                                <td>{{ $rank->final_rank }}</td>
                                                            @endif

                                                        @empty
                                                            <td></td>
                                                        @endforelse

                                                        {{-- <td></td> --}}

                                                    @endif

                                                    @if ($brgy->id == $score->barangay_id && $score->judge_id == 5)

                                                        @php
                                                            $score_judge1 = $score->beauty + $score->intelligence;
                                                        @endphp

                                                        <td>{{ $score_judge1 }}</td>

                                                        @forelse   ($data['rank'] as $rank)

                                                            @if ($rank->barangay_id == $score->barangay_id && $rank->judge_id == 5)
                                                                <td>{{ $rank->final_rank }}</td>
                                                            @endif

                                                        @empty
                                                            <td></td>
                                                        @endforelse

                                                        {{-- <td></td> --}}

                                                    @endif

                                                    @if ($brgy->id == $score->barangay_id && $score->judge_id == 6)

                                                        @php
                                                            $score_judge1 = $score->beauty + $score->intelligence;
                                                        @endphp

                                                        <td>{{ $score_judge1 }}</td>

                                                        @forelse   ($data['rank'] as $rank)

                                                            @if ($rank->barangay_id == $score->barangay_id && $rank->judge_id == 6)
                                                                <td>{{ $rank->final_rank }}</td>
                                                            @endif

                                                        @empty
                                                            <td></td>
                                                        @endforelse

                                                        {{-- <td></td> --}}

                                                    @endif

                                                @endforeach
                                                {{-- <td>{{ ROUND(($score_judge1 + $score_judge2 + $score_judge3) / 3, 2) }}</td> --}}
                                            </tr>

                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="12">
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

        function finalrank()
        {
            alert("final rank");

            $.ajax({
                type: "GET",
                url: "/finalrank",
                success: function (response) {
                    location.reload();
                },
                error: function (response)
                {
                    alert("no final rank");
                    console.log(response);
                }
            });
        }


    </script>
@endsection
