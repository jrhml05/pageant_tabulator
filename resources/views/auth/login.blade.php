@extends('layouts.app')

@section('content')
    <x-flash />

    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5" novalidate>
        @csrf

        <div>
            <label for="username" class="label">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                autocomplete="username" autocapitalize="none" spellcheck="false" class="input mt-1.5"
                @error('username') aria-invalid="true" aria-describedby="username-error" @enderror>
            @error('username')
                <p id="username-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="label">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                class="input mt-1.5" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
            @error('password')
                <p id="password-error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-full">Sign in</button>
    </form>
@endsection
