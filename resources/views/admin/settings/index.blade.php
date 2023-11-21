@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Settings</h1>
    </div>
{{--
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif --}}
    {{-- <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width:5%; text-align: center">#</th>
                                        <th style="width:25%">JUDGE NAME</th>
                                        <th style="width:20%">EMAIL</th>
                                        <th style="width:10%">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['records'] as $data)
                                        <tr>
                                            <td style="width:5%; text-align: center">
                                                <strong>#{{ $loop->iteration }}</strong></td>
                                            <td>{{ strtoupper($data->name) }}</td>
                                            <td>{{ $data->email }}</td>
                                            <td>
                                                <a href="{{ route('judges.edit', $data->id) }}"  class="btn btn-warning"><i class="fas fa-edit fa"></i> EDIT</a>
                                                <a class="btn btn-danger"><i class="fas fa-trash fa"></i> REMOVE</a>
                                            </td>
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

    </div> --}}
    @livewire('admin.stage-controller-component')
@endsection
