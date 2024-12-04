@extends('layouts.master')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">

    <h1 class="h3 mb-0 text-gray-800">MR. UEP - TOP 5</h1>

    <div>

    </div>

    <div>

        <!-- <a href="javascript:void(0)" onclick="mr_prelim_rank()" class="d-none d-sm-inline-block btn btn-primary shadow"><i class="fas fa-ranking-star fa-sm text-white-50"></i> RANK CANDIDATES</a>
        {{-- <a href="{{ route('prelimrank') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i class="fas fa-ranking-star fa-sm text-white-50"></i> RANK CANDIDATES</a> --}} -->

        <a href="{{ route('mr_pdftop_5') }}" class="d-none d-sm-inline-block btn btn-primary shadow"><i class="fas fa-print fa-sm text-white-50"></i> PRINT RESULTS</a>

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
                                    <td>{{ strtoupper($rank->candidate->name) }}</td>
                                    <td>{{ strtoupper($rank->prepageant) }}</td>
                                    <td>{{ strtoupper($rank->pageant) }}</td>
                                    <td>{{ strtoupper($rank->to_top_5) }}</td>
                                    
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