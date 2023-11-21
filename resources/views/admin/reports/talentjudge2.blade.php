@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">TALENT SCORES - JUDGE 2</h1>

        <div>

            <a href="{{ route('talent_judge1') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 1 Talent Scores</a>

            <a href="{{ route('talent_judge2') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 2 Talent Scores</a>

            <a href="{{ route('talent_judge3') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 3 Talent Scores</a>

        </div>

        <div>
            <a href="{{ route('talent') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-less-than fa-sm text-white-50"></i> BACK TO OVERALL TALENT RESULTS</a>
            <a href="{{ route('pdftalent_judge2') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-print fa-sm text-white-50"></i> PRINT SCORES JUDGE 2</a>
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
                                        <th style="width:15%">MASTERY & EXECUTION</th>
                                        <th style="width:15%">ORIGINALITY</th>
                                        <th style="width:15%">AUDIENCE IMPACT</th>
                                        <th style="width:5%">TOTAL</th>
                                        <th style="width:5%">RANK</th>

                                        {{-- <th style="width:20%">RESULT</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['brgy'] as $brgy)

                                        <tr>
                                            <td style="width:5%; text-align: center">
                                                <strong>#{{ $loop->iteration }}</strong></td>
                                            <td>{{ strtoupper($brgy->name) }}</td>

                                            @foreach ( $brgy->subscores as $score)

                                                @if ($brgy->id == $score->barangay_id && $score->judge_id == 3)

                                                    <td>{{ $score->mastery_and_execution }}</td>
                                                    <td>{{ $score->originality }}</td>
                                                    <td>{{ $score->audience_impact }}</td>

                                                    <td>{{ $score->mastery_and_execution + $score->originality + $score->audience_impact }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->barangay_id == $score->barangay_id && $rank->judge_id == 3)

                                                            <td>{{ $rank->talent_rank }}</td>

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
