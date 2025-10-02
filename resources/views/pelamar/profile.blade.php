@extends('layouts.app')

@section('title', 'Profil Pelamar')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profil</li>
                </ol>
            </div>
            <h4 class="page-title">Profil Pelamar</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">Lengkapi Data Diri dan Unggah CV Anda</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pelamar.profile.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $profile->address ?? '') }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $profile->phone_number ?? '') }}">
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="education" class="form-label">Pendidikan Terakhir</label>
                        <textarea class="form-control @error('education') is-invalid @enderror" id="education" name="education" rows="3">{{ old('education', $profile->education ?? '') }}</textarea>
                        @error('education')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="experience" class="form-label">Pengalaman Kerja</label>
                        <textarea class="form-control @error('experience') is-invalid @enderror" id="experience" name="experience" rows="5">{{ old('experience', $profile->experience ?? '') }}</textarea>
                        @error('experience')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="position_applied" class="form-label">Posisi yang Dilamar</label>
                        <select class="form-select @error('position_applied') is-invalid @enderror" id="position_applied" name="position_applied">
                            <option value="">-- Pilih Posisi --</option>
                            @foreach ($vacancies as $vacancy)
                                <option value="{{ $vacancy->title }}" {{ old('position_applied', $profile->position_applied ?? '') == $vacancy->title ? 'selected' : '' }}>
                                    {{ $vacancy->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('position_applied')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="cv_file" class="form-label">Unggah CV (PDF/DOCX, maks 2MB)</label>
                        <input type="file" class="form-control @error('cv_file') is-invalid @enderror" id="cv_file" name="cv_file" accept=".pdf,.doc,.docx">
                        @error('cv_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($profile && $profile->cv_path)
                            <small class="form-text text-muted">CV saat ini: <a href="{{ Storage::url($profile->cv_path) }}" target="_blank">Lihat CV</a></small>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Profil</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection