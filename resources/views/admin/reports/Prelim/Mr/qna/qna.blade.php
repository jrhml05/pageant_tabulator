@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">MR. UEP - CASUAL Q&A RESULT</h1>

        <div>
            <a href="{{ route('mr_qna_judge1') }}" class="d-none d-sm-inline-block btn btn-primary shadow">
                <i class="fas fa-eye fa-sm text-white-50"></i> JUDGE 1 Scores
            </a>
            <a href="{{ route('mr_qna_judge2') }}" class="d-none d-sm-inline-block btn btn-primary shadow">
                <i class="fas fa-eye fa-sm text-white-50"></i> JUDGE 2 Scores
            </a>
            <a href="{{ route('mr_qna_judge3') }}" class="d-none d-sm-inline-block btn btn-primary shadow">
                <i class="fas fa-eye fa-sm text-white-50"></i> JUDGE 3 Scores
            </a>
        </div>

        <div>
            <a href="javascript:void(0)" onclick="mr_qna_rank()" class="d-none d-sm-inline-block btn btn-primary shadow">
                <i class="fas fa-ranking-star fa-sm text-white-50"></i> RANK CANDIDATES
            </a>
            <a href="{{ route('mr_pdfqna') }}" class="d-none d-sm-inline-block btn btn-primary shadow">
                <i class="fas fa-print fa-sm text-white-50"></i> PRINT RESULTS
            </a>
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
                                        <th style="width:12%">CANDIDATE #</th>
                                        <th style="width:10%">JUDGE 1</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:10%">JUDGE 2</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:10%">JUDGE 3</th>
                                        <th style="width:5%">RANK</th>
                                        <th style="width:10%">FINAL RANK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['candidate'] as $candidate)
                                        <tr>
                                            <td>{{ strtoupper($candidate->name) }}</td>
                                            @foreach ($candidate->qna_score as $score)
                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 2)
                                                    @php
                                                        $score_judge1 = $score->relevance + $score->delivery + $score->content + $score->audience_impact;
                                                    @endphp
                                                    <td>{{ $score_judge1 }}</td>
                                                    @forelse ($data['rank'] as $rank)
                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 2)
                                                            <td>{{ $rank->qna }}</td>
                                                        @endif
                                                    @empty
                                                        <td></td>
                                                    @endforelse
                                                @endif

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 3)
                                                    @php
                                                        $score_judge2 = $score->relevance + $score->delivery + $score->content + $score->audience_impact;
                                                    @endphp
                                                    <td>{{ $score_judge2 }}</td>
                                                    @forelse ($data['rank'] as $rank)
                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 3)
                                                            <td>{{ $rank->qna }}</td>
                                                        @endif
                                                    @empty
                                                        <td></td>
                                                    @endforelse
                                                @endif

                                                @if ($candidate->id == $score->candidate_id && $score->judge_id == 4)
                                                    @php
                                                        $score_judge3 = $score->relevance + $score->delivery + $score->content + $score->audience_impact;
                                                    @endphp
                                                    <td>{{ $score_judge3 }}</td>
                                                    @forelse ($data['rank'] as $rank)
                                                        @if ($rank->candidate_id == $score->candidate_id && $rank->judge_id == 4)
                                                            <td>{{ $rank->qna }}</td>
                                                        @endif
                                                    @empty
                                                        <td></td>
                                                    @endforelse
                                                @endif
                                            @endforeach

                                            @forelse ($data['final_rank'] as $final_rank)
                                                @if ($final_rank->candidate_id == $candidate->id)
                                                    <td style="width:5%; text-align: center">{{ $final_rank->qna }}</td>
                                                @endif
                                            @empty
                                                <td></td>
                                            @endforelse
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
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
        function mr_qna_rank() {
            alert("qna rank");
            $.ajax({
                type: "GET",
                url: "/mr_qna_rank",
                success: function (response) {
                    location.reload();
                },
                error: function (response) {
                    alert("no qna rank");
                    console.log(response);
                }
            });
        }
    </script>
@endsection
