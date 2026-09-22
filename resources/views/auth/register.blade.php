@extends('layouts.auth')

@section('title', 'Register | Human In Motion')

@section('content')
    <h1 class="display-campaign text-4xl">JOIN THE MOVE.</h1>
    <p class="mt-2 text-sm text-graphite">Create an account for faster checkout, order tracking and exclusive drops.</p>

    <form method="POST" action="{{ route('register.attempt') }}" class="mt-8 space-y-4">
        @csrf

        <div>
            <label for="name" class="label">FULL NAME</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="field @error('name') field-invalid @enderror">
            @error('name')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="label">EMAIL ADDRESS</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                   class="field @error('email') field-invalid @enderror">
            @error('email')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="label">PHONE (OPTIONAL)</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                   class="field">
        </div>

        <div>
            <label for="password" class="label">PASSWORD</label>
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

        <button type="submit" class="btn btn-primary btn-block justify-center">CREATE ACCOUNT</button>
    </form>

    <p class="mt-8 text-sm text-graphite text-center">
        Already have an account?
        <a href="{{ route('login') }}" class="text-ink font-semibold hover:underline underline-offset-4">Log in</a>
    </p>
@endsection