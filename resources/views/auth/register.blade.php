@extends('layouts.guest')

@section('title', 'Register')

@section('content')

<h4 class="fw-semibold mb-2">Buat Akun Baru</h4>
<p class="text-muted mb-4">Belum punya akun? Buat akun Anda, hanya butuh kurang dari satu menit.</p>

<form action="{{ route('register') }}" method="POST" class="text-start mb-3">
    @csrf

    <div class="mb-3">
        <label class="form-label" for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap Anda" required value="{{ old('name') }}">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email Anda" required value="{{ old('email') }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password Anda" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi password Anda" required>
    </div>

    <div class="d-grid">
        <button class="btn btn-primary" type="submit">Daftar</button>
    </div>
</form>

<p class="text-muted fs-14 mb-4">Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold text-dark ms-1">Login</a></p>

@endsection
