@extends('layouts.app')

@section('title', 'CV Agent AI')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.hrd') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">CV Agent AI</li>
                </ol>
            </div>
            <h4 class="page-title">CV AGENTYC AI</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="header-title">Proses CV dengan AI Agent</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">Pilih deskripsi pekerjaan yang relevan, dan AI Agent akan menganalisis semua CV pelamar untuk menemukan kandidat yang paling cocok berdasarkan kriteria tersebut.</p>
                <form id="ai-process-form">
                    @csrf
                    <div class="mb-3">
                        <label for="job_description" class="form-label">Pilih Deskripsi Pekerjaan</label>
                        <select class="form-select" id="job_description" name="job_description" required>
                            <option value="">-- Pilih Loker --</option>
                            @foreach ($vacancies as $vacancy)
                                <option value="{{ $vacancy->filename }}">{{ $vacancy->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" id="process-ai-btn">Proses dengan AI Agent</button>
                </form>

                <div id="ai-processing-loader" class="text-center mt-3" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Data sedang diproses oleh AI Agent...</p>
                </div>

                <div id="ai-results" class="mt-4" style="display: none;">
                    <h5 class="mb-3">Hasil Analisis AI:</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Pelamar</th>
                                    <th>Status</th>
                                    <th>Skor Kecocokan</th>
                                    <th>Ringkasan Kekuatan</th>
                                    <th>Poin Perhatian</th>
                                </tr>
                            </thead>
                            <tbody id="ai-results-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let pollingInterval;
        let batchId;

        function stopPolling() {
            clearInterval(pollingInterval);
        }

        function pollBatchStatus() {
            $.ajax({
                url: `/ai/batch/${batchId}`,
                method: 'GET',
                success: function(response) {
                    const progress = response.batch_progress;
                    const percentage = progress.total_jobs > 0 ? Math.round((progress.processed_jobs / progress.total_jobs) * 100) : 0;
                    
                    // Update loader text with progress
                    $('#ai-processing-loader p').text(`Data sedang diproses... (${percentage}%)`);

                    // Update results table
                    $('#ai-results-body').empty();
                    if (response.results && response.results.length > 0) {
                        response.results.forEach(item => {
                            let resultHtml;
                            if (item.status === 'success') {
                                resultHtml = `
                                    <tr>
                                        <td>${item.user.name}</td>
                                        <td><span class="badge bg-success">Sukses</span></td>
                                        <td>${item.result.skor_kecocokan_persen}%</td>
                                        <td>${item.result.ringkasan_kekuatan}</td>
                                        <td>${item.result.poin_perhatian}</td>
                                    </tr>
                                `;
                            } else if (item.status === 'failed') {
                                resultHtml = `
                                    <tr>
                                        <td>${item.user.name}</td>
                                        <td><span class="badge bg-danger">Gagal</span></td>
                                        <td colspan="3">${item.result.error || 'Unknown error'}</td>
                                    </tr>
                                `;
                            } else {
                                 resultHtml = `
                                    <tr>
                                        <td>${item.user.name}</td>
                                        <td><span class="badge bg-warning">Memproses</span></td>
                                        <td colspan="3">Sedang menunggu untuk diproses...</td>
                                    </tr>
                                `;
                            }
                            $('#ai-results-body').append(resultHtml);
                        });
                        $('#ai-results').show();
                    }

                    if (progress.finished) {
                        stopPolling();
                        $('#ai-processing-loader').hide();
                        Swal.fire('Sukses', 'Semua CV telah selesai diproses!', 'success');
                    }
                },
                error: function(xhr) {
                    stopPolling();
                    $('#ai-processing-loader').hide();
                    Swal.fire('Error', 'Gagal mengambil status proses. Silakan refresh halaman.', 'error');
                }
            });
        }

        $('#ai-process-form').on('submit', function(e) {
            e.preventDefault();
            stopPolling(); // Stop any previous polling

            const jobDescriptionName = $('#job_description').val();
            if (!jobDescriptionName) {
                Swal.fire('Peringatan', 'Mohon pilih deskripsi pekerjaan terlebih dahulu.', 'warning');
                return;
            }

            $('#ai-processing-loader p').text('Memulai proses... (0%)');
            $('#ai-processing-loader').show();
            $('#ai-results').hide();
            $('#ai-results-body').empty();

            $.ajax({
                url: "{{ route('ai.process_cv') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    job_description_name: jobDescriptionName
                },
                success: function(response) {
                    if (response.batch_id) {
                        batchId = response.batch_id;
                        $('#ai-results-body').html('<tr><td colspan="5" class="text-center">Memulai proses analisis...</td></tr>');
                        $('#ai-results').show();
                        pollingInterval = setInterval(pollBatchStatus, 3000); // Poll every 3 seconds
                    } else {
                         $('#ai-processing-loader').hide();
                         Swal.fire('Info', response.message || 'Tidak ada pelamar untuk diproses.', 'info');
                    }
                },
                error: function(xhr) {
                    $('#ai-processing-loader').hide();
                    let errorMessage = 'Terjadi kesalahan saat memulai proses.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        });
    });
</script>
@endpush
