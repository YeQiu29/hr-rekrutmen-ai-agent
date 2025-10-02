@extends('layouts.app')

@section('title', 'Dashboard HRD')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">HRD</li>
                </ol>
            </div>
            <h4 class="page-title">Dashboard HRD</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    {{-- Kolom Daftar Pelamar --}}
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title">Daftar Pelamar</h4>
                <div class="w-25">
                    <input type="text" id="search-applicant" class="form-control" placeholder="Cari nama pelamar...">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-centered table-sm table-nowrap table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama Pelamar</th>
                                <th>Posisi Dilamar</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Pendidikan</th>
                                <th>Pengalaman</th>
                                <th>CV</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="applicant-table-body">
                            @forelse($applicants as $applicant)
                            <tr>
                                <td>{{ $applicant->name }}</td>
                                <td>{{ $applicant->applicantProfile->position_applied ?? '-' }}</td>
                                <td>{{ $applicant->email }}</td>
                                <td>{{ $applicant->applicantProfile->phone_number ?? '-' }}</td>
                                <td>{{ Str::limit($applicant->applicantProfile->education ?? '-', 50) }}</td>
                                <td>{{ Str::limit($applicant->applicantProfile->experience ?? '-', 50) }}</td>
                                <td>
                                    @if($applicant->applicantProfile && $applicant->applicantProfile->cv_path)
                                    <a href="{{ Storage::url($applicant->applicantProfile->cv_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat CV</a>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="hstack gap-1 justify-content-center">
                                        <a href="javascript:void(0);" class="btn btn-soft-primary btn-icon btn-sm rounded-circle" title="Detail"><i class="ri-eye-line"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada pelamar yang terdaftar.</td>
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

@push('scripts')
<script>
    $(document).ready(function(){
        $("#search-applicant").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#applicant-table-body tr").filter(function() {
                $(this).toggle($(this).find('td:first').text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endpush
