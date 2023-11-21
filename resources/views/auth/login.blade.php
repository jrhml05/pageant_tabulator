@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="user">
        @csrf
        <div class="form-group">
            <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email"
                id="exampleInputEmail" aria-describedby="emailHelp"
                placeholder="Enter Email...">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        </div>
        <div class="form-group">
            <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="Password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox small">
                <input type="checkbox" class="custom-control-input" id="customCheck">
                <label class="custom-control-label" for="customCheck">Remember
                    Me</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-user btn-block">
            Login
        </button>

    </form>
@endsection
