@extends('layouts.auth')

@section('title', 'Forgot Password | Human In Motion')

@section('content')
    <h1 class="display-campaign text-4xl">RESET YOUR PASSWORD.</h1>
    <p class="mt-2 text-sm text-graphite">Enter your email and we'll send you a reset link.</p>

    @if (session('status'))
        <div class="mt-6 border border-ok/40 bg-ok/10 text-ok px-4 py-3 text-sm">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-4">
        @csrf

        <div>
            <label for="email" class="label">EMAIL ADDRESS</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                   class="field @error('email') field-invalid @enderror">
            @error('email')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block justify-center">SEND RESET LINK</button>
    </form>

    <p class="mt-8 text-sm text-graphite text-center">
        <a href="{{ route('login') }}" class="text-ink font-semibold hover:underline underline-offset-4">Back to login</a>
    </p>
@endsection