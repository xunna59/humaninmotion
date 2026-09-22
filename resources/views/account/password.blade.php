@extends('layouts.site')

@section('title', $title)

@section('content')
    <div class="container-site py-8 lg:py-12 max-w-4xl">
        <header class="mb-8">
            <p class="eyebrow text-brass mb-2">MY ACCOUNT</p>
            <h1 class="display-campaign text-4xl lg:text-6xl">PASSWORD</h1>
        </header>

        @include('account._nav')

        @if (session('success'))
            <div class="mt-6 border border-ok/40 bg-ok/10 text-ok px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif

        <div class="mt-8 max-w-lg">
            <form action="{{ route('account.password.update') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="current_password" class="label">CURRENT PASSWORD</label>
                    <input id="current_password" name="current_password" type="password" required autocomplete="current-password"
                           class="field @error('current_password') field-invalid @enderror">
                    @error('current_password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="label">NEW PASSWORD</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="field @error('password') field-invalid @enderror">
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label">CONFIRM NEW PASSWORD</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                           class="field">
                </div>

                <button type="submit" class="btn btn-primary btn-sm">UPDATE PASSWORD</button>
            </form>
        </div>
    </div>
@endsection