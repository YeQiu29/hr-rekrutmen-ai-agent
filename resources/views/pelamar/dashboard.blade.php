@extends('layouts.dashboard')

@section('title', 'Dashboard Pelamar')

@section('dashboard_content')
<script>
    window.location.href = "{{ route('pelamar.profile') }}";
</script>
@endsection