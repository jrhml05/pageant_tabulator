@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <h1 class="h3 mb-0 text-gray-800">FINAL RESULT</h1>

        <div>

            <a href="{{ route('pdffinal_result') }}" target="_blank" class="d-none d-sm-inline-block btn btn-primary shadow"><i
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
                                        <th style="width:5%; text-align: center">#</th>
                                        <th style="width:20%">BARANGAY</th>
                                        <th style="width:10%">RANK</th>
                                        {{-- <th style="width:20%">RESULT</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $idx = 1;
                                    @endphp

                                    @forelse ($data['final_rank'] as $rank)

                                        @if ($rank->semi_rank <=5)

                                            <tr>
                                                    <td>{{ $idx }}</td>
                                                    <td>{{ strtoupper($rank->barangay->name) }}</td>
                                                    <td>{{ $rank->final_rank }}</td>
                                            </tr>

                                            @php
                                                $idx = $idx + 1;
                                            @endphp

                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="5">
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




    </script>
@endsection
