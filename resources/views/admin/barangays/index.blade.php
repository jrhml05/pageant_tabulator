@extends('layouts.master')

@section('content')
 <!-- Page Heading -->
 <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Barangays</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-primary shadow"><i
            class="fas fa-plus fa-sm text-white-50"></i> ADD NEW</a>
</div>


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
                                    <th style="width:25%">BARANGAY</th>
                                    <th style="width:20%">VOTERS</th>
                                    <th style="width:5%">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data['records'] as $data)
                                    <tr>
                                        <td style="width:5%; text-align: center"><strong>#{{ $loop->iteration }}</strong></td>
                                        <td>{{ strtoupper($data->name) }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
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
