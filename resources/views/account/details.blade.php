@extends('layouts.site')

@section('title', $title)

@section('content')
    <div class="container-site py-8 lg:py-12 max-w-4xl">
        <header class="mb-8">
            <p class="eyebrow text-brass mb-2">MY ACCOUNT</p>
            <h1 class="display-campaign text-4xl lg:text-6xl">DETAILS</h1>
        </header>

        @include('account._nav')

        @if (session('success'))
            <div class="mt-6 border border-ok/40 bg-ok/10 text-ok px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif

        <div class="mt-8 max-w-lg">
            <form action="{{ route('account.details.update') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="label">FULL NAME</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="field">
                    @error('name')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="label">EMAIL ADDRESS</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="field">
                    @error('email')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="label">PHONE (OPTIONAL)</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" class="field">
                </div>

                <button type="submit" class="btn btn-primary btn-sm">SAVE DETAILS</button>
            </form>
        </div>
    </div>
@endsection