@extends('layouts.app')

@section('title', 'Manajemen Loker')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.hrd') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Manajemen Loker</li>
                </ol>
            </div>
            <h4 class="page-title">Manajemen Loker</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="header-title">Daftar Lowongan Kerja</h4>
                    <a href="{{ route('loker.create') }}" class="btn btn-primary btn-sm">Tambah Loker</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul Posisi</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vacancies as $vacancy)
                                <tr>
                                    <td>{{ $vacancy->id }}</td>
                                    <td>{{ $vacancy->title }}</td>
                                    <td>
                                        @if ($vacancy->status == 'open')
                                            <span class="badge bg-success">Open</span>
                                        @else
                                            <span class="badge bg-danger">Closed</span>
                                        @endif
                                    </td>
                                    <td>{{ $vacancy->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('loker.edit', $vacancy->id) }}" class="btn btn-soft-primary btn-sm">Edit</a>
                                        <form action="{{ route('loker.destroy', $vacancy->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-soft-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus loker ini?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data lowongan kerja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
