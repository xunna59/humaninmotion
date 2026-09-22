@extends('layouts.auth')

@section('title', 'Reset Password | Human In Motion')

@section('content')
    <h1 class="display-campaign text-4xl">SET A NEW PASSWORD.</h1>

    <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="label">EMAIL ADDRESS</label>
            <input type="email" id="email" name="email" value="{{ old('email', $request->email ?? null) }}" required autofocus autocomplete="email"
                   class="field @error('email') field-invalid @enderror">
            @error('email')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="label">NEW PASSWORD</label>
            <input type="password" id="password" name="password" required autocomplete="new-password"
                   class="field @error('password') field-invalid @enderror">
            @error('password')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="label">CONFIRM PASSWORD</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                   class="field">
        </div>

        <button type="submit" class="btn btn-primary btn-block justify-center">RESET PASSWORD</button>
    </form>
@endsection