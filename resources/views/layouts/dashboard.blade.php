@extends('layouts.app')

@section('content')
<div class="wrapper">

    {{-- Memanggil Sidebar (pastikan file partials/sidenav.blade.php ada) --}}
    @include('partials.sidenav')

    {{-- Memanggil Topbar (pastikan file partials/topbar.blade.php ada) --}}
    @include('partials.topbar')

    {{-- STRUKTUR DIV YANG BENAR SESUAI TEMPLATE ASLI --}}
    <div class="content-page">
        <div class="content">

            <div class="container-fluid">
            
                {{-- Di sinilah semua konten spesifik halaman (misal: dashboard_hrd) akan ditampilkan --}}
                @yield('dashboard_content')

            </div> </div> {{-- Memanggil Footer (pastikan file partials/footer.blade.php ada) --}}
        @include('partials.footer')

    </div>

    </div>
{{-- Anda bisa memanggil customizer jika digunakan (pastikan file partials/customizer.blade.php ada) --}}
@include('partials.customizer')
@endsection