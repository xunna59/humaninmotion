@extends('layouts.auth')

@section('title', 'Login | Human In Motion')

@section('content')
    <h1 class="display-campaign text-4xl">WELCOME BACK.</h1>
    <p class="mt-2 text-sm text-graphite">Log in to your Human In Motion account.</p>

    <form method="POST" action="{{ route('login.attempt') }}" class="mt-8 space-y-4">
        @csrf

        <div>
            <label for="email" class="label">EMAIL ADDRESS</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                   class="field @error('email') field-invalid @enderror">
            @error('email')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="label">PASSWORD</label>
                <a href="{{ route('password.request') }}" class="text-[11px] uppercase tracking-wider text-brass hover:underline">Forgot?</a>
            </div>
            <input type="password" id="password" name="password" required autocomplete="current-password"
                   class="field @error('password') field-invalid @enderror">
            @error('password')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-3 cursor-pointer select-none">
            <input type="checkbox" name="remember" value="1" class="peer sr-only">
            <span class="w-5 h-5 border border-ink/30 flex items-center justify-center text-transparent peer-checked:bg-ink peer-checked:border-ink peer-checked:text-bone text-[12px] leading-none">&check;</span>
            <span class="text-sm">Remember me</span>
        </label>

        <button type="submit" class="btn btn-primary btn-block justify-center">LOG IN</button>
    </form>

    <p class="mt-8 text-sm text-graphite text-center">
        New to Human In Motion?
        <a href="{{ route('register') }}" class="text-ink font-semibold hover:underline underline-offset-4">Create an account</a>
    </p>
@endsection