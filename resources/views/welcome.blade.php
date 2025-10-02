@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Boron</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="card">
    <div class="card-body">
        <p>Ini adalah halaman contoh yang menggunakan layout utama dari template.</p>
        <p>Sekarang Anda dapat mulai membuat halaman baru dengan me-extend `layouts.app` dan mengisi bagian `@section('content')`.</p>
    </div>
</div>
@endsection