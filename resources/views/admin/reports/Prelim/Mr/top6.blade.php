@extends('layouts.master')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">

    <h1 class="h3 mb-0 text-gray-800">MR. UEP - TOP 6</h1>

    <div>

        <!-- <a href="{{ route('mr_prelim_judge1') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 1 Scores</a>

            <a href="{{ route('mr_prelim_judge2') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 2 Scores</a>

            <a href="{{ route('mr_prelim_judge3') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 3 Scores</a>

            <a href="{{ route('mr_prelim_judge4') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 4 Scores</a>

            <a href="{{ route('mr_prelim_judge5') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i
                class="fas fa-eye fa-sm text-white-50"></i> JUDGE 5 Scores</a> -->

    </div>

    <div>

        <!-- <a href="javascript:void(0)" onclick="mr_prelim_rank()" class="d-none d-sm-inline-block btn btn-primary shadow"><i class="fas fa-ranking-star fa-sm text-white-50"></i> RANK CANDIDATES</a>
        {{-- <a href="{{ route('prelimrank') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i class="fas fa-ranking-star fa-sm text-white-50"></i> RANK CANDIDATES</a> --}} -->

        <a href="{{ route('mr_pdftop_6') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i class="fas fa-print fa-sm text-white-50"></i> PRINT RESULTS</a>

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
                                    <th style="width:10%">PREPAGEANT RANK</th>
                                    <th style="width:10%">PRELIMINIARIES RANK</th>
                                    <th style="width:15%">OVERALL RANK</th>
                                    {{-- <th style="width:20%">RESULT</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data['final_rank'] as $rank)
                                <tr>
                                    <td>{{ strtoupper($rank->candidate_id) }}</td>
                                    <td>{{ strtoupper($rank->prepageant) }}</td>
                                    <td>{{ strtoupper($rank->prelim) }}</td>
                                    <td>{{ strtoupper($rank->to_final) }}</td>
                                    
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
    function mr_prelim_rank() {
        alert("prelim rank");

        $.ajax({
            type: "GET",
            url: "/mr_prelim_rank",
            success: function(response) {
                location.reload();
            },
            error: function(response) {
                alert("no prelim rank");
                console.log(response);
            }
        });
    }
</script>
@endsection