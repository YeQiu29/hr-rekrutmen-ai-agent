@extends('layouts.guest')

@section('title', 'Login ' . ucfirst($userType))

@section('content')

<h4 class="fw-semibold mb-2">Login Akun {{ ucfirst($userType) }}</h4>

<p class="text-muted mb-4">Masukkan email dan password Anda.</p>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('login') }}" method="POST" class="text-start mb-3">
    @csrf

    <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email Anda" required value="{{ old('email') }}">
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password Anda" required>
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="d-flex justify-content-between mb-3">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="checkbox-signin" name="remember">
            <label class="form-check-label" for="checkbox-signin">Ingat saya</label>
        </div>
        {{-- <a href="#" class="text-muted border-bottom border-dashed">Lupa Password</a> --}}
    </div>

    <div class="d-grid">
        <button class="btn btn-primary" type="submit">Login</button>
    </div>
</form>

@if ($userType === 'pelamar')
<p class="text-danger fs-14 my-3">Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold text-dark ms-1">Daftar!</a></p>
@endif

@endsection
