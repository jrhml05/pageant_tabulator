@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">MR. UEP - CASUAL Q&A SCORES (JUDGE 1)</h1>

        <div>

            <a href="{{ route('mr_qna_judge1') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 1 Casual Q&A Scores</a>

            <a href="{{ route('mr_qna_judge2') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 2 Casual Q&A Scores</a>

            <a href="{{ route('mr_qna_judge3') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 3 Casual Q&A Scores</a>

        </div>

        <div>
            <a href="{{ route('mr_qna') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-less-than fa-sm text-white-50"></i> BACK TO OVERALL CASUAL Q&A RESULTS</a>
            <a href="{{ route('mr_pdfqnajudge1') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-print fa-sm text-white-50"></i> PRINT SCORES JUDGE 1</a>
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
                                        <th style="width:15%">RELEVANCE 40%</th>
                                        <th style="width:15%">DELIVERY/CONFIDENCE 20%</th>
                                        <th style="width:15%">CONTENT OF ANSWER 30%</th>
                                        <th style="width:15%">AUDIENCE IMPACT 10%</th>
                                        <th style="width:10%">TOTAL 100%</th>
                                        <th style="width:10%">RANK</th>

                                        {{-- <th style="width:20%">RESULT</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['candidate'] as $candidate)

                                        <tr>
                                            <td>{{ strtoupper($candidate->name) }}</td>

                                            @foreach ( $candidate->qna_score as $score)

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)

                                                    <td>{{ $score->relevance }}</td>
                                                    <td>{{ $score->delivery }}</td>
                                                    <td>{{ $score->content }}</td>
                                                    <td>{{ $score->audience_impact }}</td>

                                                    <td>{{ $score->relevance + $score->delivery + $score->content + $score->audience_impact }}</td>

                                                    @forelse   ($data['rank'] as $rank)

                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)

                                                            <td>{{ $rank->qna }}</td>

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
