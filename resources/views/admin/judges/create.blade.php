@extends('layouts.master')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">CREATE NEW JUDGE</h1>

    </div>


    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">

                <form action="{{ route('judges.store') }}" method="POST">
                    @csrf

                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input name="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input name="email" type="text" class="form-control form-control-lg @error('email') is-invalid @enderror">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input name="password" type="text" class="form-control form-control-lg @error('password') is-invalid @enderror">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">

                            <button type="submit" class="btn btn-primary float-right"><i
                                    class="fas fa-save fa text-white-50"></i> SAVE</button>
                            <a href="{{ route('judges.index') }}" class="btn btn-secondary float-right mr-2"><i
                                    class="fas fa fa-arrow-left text-white-50"></i> BACK</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
