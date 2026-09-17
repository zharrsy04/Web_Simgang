<!DOCTYPE html>
<html lang="id" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
@extends('layouts.app')

@section('title', 'Penilaian')

@section('content')
<style>
    /* Reset container padding for full-width design */
    .container {
        padding-right: 0;
        padding-left: 0;
        max-width: 1240px;
    }

    /* Modern dashboard header */
    .dashboard-header {
        font-size: 1.8rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 2rem;
        text-align: left;
        margin-top: 2rem;
        letter-spacing: -0.5px;
    }

    /* Action buttons container */
    .action {
        display: flex;
        gap: 0.35rem;
        justify-content: center;
        flex-wrap: nowrap;
        flex-direction: row;
    }

    /* Table styling */
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 1rem;
        background-color: transparent;
        box-shadow: none;
    }

    .table-sm {
        font-size: 0.9rem;
    }

    .table-sm th,
    .table-sm td {
        padding: 0.75rem;
        vertical-align: middle;
        border: none;
        background-color: #fff;
    }

    .table-sm th {
        font-weight: 500;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    .table-sm tr {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }

    .table-sm tr:hover {
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .table-sm thead tr {
        background-color: #f3f4f6;
        box-shadow: none;
    }

    .table-sm thead th {
        background-color: #f3f4f6;
        color: #4b5563;
        border-bottom: 1px solid #e5e7eb;
        text-align: center;
    }

    /* Responsive table */
    @media (max-width: 768px) {
        table.table-sm {
            font-size: 0.8rem;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.5rem;
        }

        .btn-sm {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        .action {
            gap: 0.25rem;
            flex-wrap: nowrap;
        }

        .pagination-sm .page-link {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        .pagination-sm .page-item {
            margin: 0 1px;
        }
    }

    /* Card styling */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
        color: white;
        font-weight: 600;
        text-align: center;
        padding: 1.25rem;
        border-bottom: none;
        letter-spacing: 0.5px;
    }

    .card-header h4 {
        margin: 0;
        font-size: 1.25rem;
    }

    .card-body {
        padding: 1.5rem;
        background-color: #fff;
    }

    /* Button styling */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.5rem 0.75rem;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .btn-sm {
        padding: 0.35rem 0.65rem;
        font-size: 0.8rem;
        min-width: 32px;
        white-space: nowrap;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn i {
        font-size: 0.8rem;
    }

    .btn-primary {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }

    .btn-success {
        background-color: #10b981;
        border-color: #10b981;
    }

    .btn-danger {
        background-color: #ef4444;
        border-color: #ef4444;
    }

    .btn-warning {
        background-color: #f59e0b;
        border-color: #f59e0b;
        color: white;
    }

    .btn-info {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    /* Loading state for buttons */
    .btn-loading {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Search form */
    .search-form {
        background-color: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        max-width: 300px;
    }

    .search-form:focus-within {
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .search-form input {
        border: none;
        box-shadow: none;
        padding: 0.75rem 1rem;
    }

    .search-form .input-group-text {
        background-color: transparent;
        border: none;
        color: #6b7280;
        padding: 0.75rem;
        transition: all 0.2s ease;
    }

    .search-form button[type="submit"] {
        transition: all 0.2s ease;
    }

    .search-form button[type="submit"]:hover {
        color: #4f46e5;
        background-color: #f3f4f6 !important;
    }

    .search-form button[type="submit"]:active {
        transform: scale(0.95);
    }

    /* Alert styling */
    .alert {
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: none;
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .alert-error {
        background-color: #fee2e2;
        color: #b91c1c;
    }

    .alert-warning {
        background-color: #fef3c7;
        color: #92400e;
    }

    .alert i {
        font-size: 1.25rem;
    }

    .alert-dismissible {
        padding-right: 3rem;
    }

    .alert .btn-close {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        padding: 0.5rem;
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        opacity: 0.7;
    }

    .alert .btn-close:hover {
        opacity: 1;
    }

    /* Empty state styling */
    .empty-state {
        padding: 3rem 0;
        text-align: center;
        color: #94a3b8;
    }

    .empty-state i {
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    /* Pagination styling */
    .pagination {
        justify-content: center;
        margin-top: 1.5rem;
    }

    .page-item {
        margin: 0 0.2rem;
    }

    .page-link {
        border: none;
        border-radius: 8px;
        color: #4f46e5;
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .page-link:hover {
        background-color: #eff6ff;
        color: #4338ca;
    }

    .page-item.active .page-link {
        background-color: #4f46e5;
        color: white;
    }

    /* Certificate number styling */
    .cert-number {
        font-family: monospace;
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 600;
    }

    /* Student name styling */
    .student-name {
        font-weight: 500;
        color: #1f2937;
        margin-top: 0.25rem;
    }

    /* Certificate info styling */
    .cert-info {
        line-height: 1.3;
    }

    /* Modal styling improvements */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 1.5rem;
        background-color: #f9fafb;
        border-radius: 12px 12px 0 0;
    }

    .modal-title {
        font-weight: 600;
        color: #1f2937;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        background-color: #f9fafb;
        border-radius: 0 0 12px 12px;
    }

    /* Form improvements */
    .form-control {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 0.75rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
    }

    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-text {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }

    /* Progress bar for file upload */
    .upload-progress {
        display: none;
        margin-top: 1rem;
    }

    .progress {
        height: 4px;
        border-radius: 2px;
        background-color: #e5e7eb;
        overflow: hidden;
    }

    .progress-bar {
        background-color: #4f46e5;
        transition: width 0.3s ease;
    }

    /* Status badges */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-badge.warning {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-badge.danger {
        background-color: #fee2e2;
        color: #b91c1c;
    }

    /* Tooltip styling */
    .tooltip {
        font-size: 0.8rem;
    }

    .tooltip-inner {
        background-color: #1f2937;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
    }

    /* File input styling */
    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .file-input-wrapper input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-input-label {
        display: block;
        padding: 0.75rem;
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: #f9fafb;
    }

    .file-input-label:hover {
        border-color: #4f46e5;
        background-color: #eff6ff;
    }

    .file-input-label.drag-over {
        border-color: #4f46e5;
        background-color: #eff6ff;
    }

    .file-selected {
        border-color: #10b981;
        background-color: #d1fae5;
    }
    
    /* NEW: Custom width for PDF Viewer Modal */
    /* Ganti modal-xl di HTML dengan modal-custom-width */
    .modal-custom-width {
        max-width: 99% !important; /* Membuat modal 99% dari lebar viewport */
        width: auto !important; /* Memungkinkan lebar menyesuaikan konten */
    }

    /* NEW: Ensure iframe fills the modal body */
    #pdfViewerModal .modal-body {
        padding: 0; /* Hapus padding agar iframe bisa full */
        height: 90vh; /* Berikan tinggi yang cukup untuk viewer */
        display: flex; /* Gunakan flexbox untuk memastikan iframe mengisi ruang */
        justify-content: center;
        align-items: center;
    }

    #pdfViewerModal .modal-body iframe {
        width: 100%; /* Pastikan iframe mengisi lebar penuh modal body */
        height: 100%; /* Pastikan iframe mengisi tinggi penuh modal body */
    }
</style>



<h3 class="dashboard-header">Sistem Penilaian</h3>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Daftar Sertifikat</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">Total: {{ $sertifikats->total() }} sertifikat</span>
                </div>
                <form action="{{ route('penilaian.index') }}" method="GET" class="search-form" id="searchForm">
                    <div class="input-group">
                        <input name="search" 
                               type="text" 
                               class="form-control" 
                               id="searchInput"
                               placeholder="Cari No. Sertifikat" 
                               value="{{ request('search') }}" 
                               autocomplete="off">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible mb-4" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error alert-dismissible mb-4" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        {{ session('warning') }}
                    </div>
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NO. SERTIFIKAT</th>
                            <th>NAMA LENGKAP</th>
                            <th>NIM / NIS</th>
                            <th>BIDANG</th>
                            <th>JURUSAN</th>
                            <th>STATUS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sertifikats as $sertifikat)
                            <tr>
                                <td>{{ ($sertifikats->currentPage() - 1) * $sertifikats->perPage() + $loop->iteration }}</td>
                                <td>
                                    <div class="cert-info">
                                        <div class="cert-number">{{ $sertifikat->no_sertifikat }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="student-name">{{ $sertifikat->nama_lengkap }}</div>
                                </td>
                                <td>
                                    <div class="student-name text-center">{{ $sertifikat->nim_nis }}</div>
                                </td>
                                <td>{{ $sertifikat->bidang }}</td>
                                <td>{{ $sertifikat->jurusan ?? '-' }}</td>
                                <td>
                                    @if($sertifikat->penilaians && $sertifikat->penilaians->count() > 0)
                                        <span class="status-badge success">Selesai</span>
                                    @else
                                        <span class="status-badge warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action">
                                        <a href="{{ route('siswa.shownilai', $sertifikat->id_sertifikat) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Detail"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-certificate fa-3x mb-3"></i>
                                        <p>Belum ada data sertifikat tersedia</p>
                                        @if(request('search'))
                                            <p class="text-muted">Coba dengan kata kunci yang berbeda</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $sertifikats->appends(request()->query())->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
            </div>
        </div>
    </div>
</div>

{{-- Modal Form Upload PDF --}}
<div class="modal fade" id="uploadPdfModal" aria-labelledby="uploadPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadPdfModalLabel">
                    <i class="fas fa-upload me-2"></i>
                    Upload PDF Sertifikat untuk <span id="modalStudentName" class="text-primary"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pdf.upload.individual') }}" method="POST" enctype="multipart/form-data" id="uploadPdfForm">
                @csrf
                <div class="modal-body">
                    
                    {{-- Hidden input untuk id_sertifikat --}}
                    <input type="hidden" name="id_sertifikat" id="modalSertifikatId">
                    
                    {{-- Kontainer baru untuk alert yang dibuat oleh JavaScript --}}
                    <div id="form-alerts-container">
                        {{-- Bagian ini akan diisi oleh JS --}}
                    </div>

                    {{-- Display ID Sertifikat (Read-only untuk user) --}}
                    <div class="mb-3">
                        <label for="display_id_sertifikat" class="form-label">
                            <i class="fas fa-id-card me-1"></i>
                            ID Sertifikat
                        </label>
                        <input type="text"
                               class="form-control"
                               id="display_id_sertifikat"
                               readonly
                               placeholder="ID Sertifikat akan terisi otomatis"
                               style="background-color: #f8f9fa;">
                        <div class="form-text">ID sertifikat dipilih secara otomatis berdasarkan siswa yang dipilih</div>
                    </div>
                    
                    {{-- Judul PDF --}}
                    <div class="mb-3">
                        <label for="pdf_title" class="form-label">
                            <i class="fas fa-heading me-1"></i>
                            Judul PDF <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control"
                               id="pdf_title" 
                               name="title" 
                               required
                               placeholder="Masukkan judul PDF"
                               maxlength="255">
                        <div class="form-text">Maksimal 255 karakter <span id="pdf_title_char_count" class="text-muted">0/255</span></div>
                    </div>
                    
                    {{-- Deskripsi (Opsional) --}}
                    <div class="mb-3">
                        <label for="pdf_description" class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Deskripsi (Opsional)
                        </label>
                        <textarea class="form-control" 
                                 id="pdf_description" 
                                 name="description" 
                                 rows="3"
                                 placeholder="Masukkan deskripsi PDF"
                                 maxlength="1000"></textarea>
                        <div class="form-text">Maksimal 1000 karakter <span id="pdf_description_char_count" class="text-muted">0/1000</span></div>
                    </div>
                    
                    {{-- Pilih File PDF --}}
                    <div class="mb-3">
                        <label for="pdf_file" class="form-label">
                            <i class="fas fa-file-pdf me-1"></i>
                            Pilih File PDF <span class="text-danger">*</span>
                        </label>
                        <div class="file-input-wrapper">
                            <input type="file" 
                                   class="form-control" 
                                   id="pdf_file" 
                                   name="pdf_file" 
                                   accept="application/pdf" 
                                   required>
                            <label for="pdf_file" class="file-input-label">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2 text-muted"></i>
                                <p class="mb-0">Klik untuk memilih file atau drag & drop</p>
                                <small class="text-muted">Format: PDF, Maksimal: 5MB</small>
                            </label>
                        </div>
                        <div class="form-text" id="file-info">
                            Maksimal 5MB, format PDF.
                        </div>
                    </div>
                    
                    <div class="upload-progress" style="display: none;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Uploading...</span>
                            <span class="text-muted" id="progress-text">0%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" id="progress-bar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                        <i class="fas fa-upload me-1"></i>
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal untuk Menampilkan PDF --}}
<div class="modal fade" id="pdfViewerModal" aria-labelledby="pdfViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom-width">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfViewerModalLabel">
                    <i class="fas fa-file-pdf me-2"></i>
                    Lihat PDF: <span id="pdfViewerTitle" class="text-primary"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" style="height: 80vh;">
                <iframe id="pdfViewerFrame" src="" frameborder="0" width="100%" height="100%"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a id="pdfDownloadLink" href="#" class="btn btn-primary" download target="_blank">
                    <i class="fas fa-download me-1"></i> Unduh PDF
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    
    // Fungsi untuk menampilkan alert di dalam modal
    function showFormAlert(message, type) {
        let alertClass = '';
        let iconClass = '';
        if (type === 'success') {
            alertClass = 'alert-success';
            iconClass = 'fas fa-check-circle';
        } else if (type === 'danger') {
            alertClass = 'alert-danger';
            iconClass = 'fas fa-exclamation-circle';
        } else if (type === 'warning') {
            alertClass = 'alert-warning';
            iconClass = 'fas fa-exclamation-triangle';
        }

        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        $('#form-alerts-container').html(alertHtml);
    }

    // Fungsi untuk reset form upload
    function resetUploadForm() {
        $('#uploadPdfForm')[0].reset();
        $('#file-info').html('Maksimal 5MB, format PDF.');
        $('#pdf_title_char_count').text('0/255');
        $('#pdf_description_char_count').text('0/1000');
        $('.upload-progress').hide();
        $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload me-1"></i> Upload');
        $('#form-alerts-container').empty();
    }

    // Fungsi untuk membersihkan PDF viewer
    function clearPdfViewer() {
        $('#pdfViewerFrame').removeAttr('src');
        $('#pdfViewerTitle').text('');
        $('#pdfDownloadLink').attr('href', '#');
    }

    // Fungsi untuk membersihkan backdrop modal yang stuck
    function forceCleanupModals() {
        // Remove all modal backdrops
        $('.modal-backdrop').remove();
        // Remove modal-open class from body
        $('body').removeClass('modal-open');
        // Remove inline styles from body
        $('body').removeAttr('style');
    }

    // Event listener untuk tombol "Upload PDF" 
    $(document).on('click', '.upload-pdf-btn', function() {
        // Pastikan tidak ada backdrop yang tertinggal
        forceCleanupModals();
        
        // Reset semua
        resetUploadForm();
        clearPdfViewer();
        
        // Isi data siswa ke modal
        const idSertifikat = $(this).data('id-sertifikat');
        const namaSiswa = $(this).data('nama-siswa');

        $('#modalSertifikatId').val(idSertifikat);
        $('#modalStudentName').text(namaSiswa);
        $('#display_id_sertifikat').val(idSertifikat);
    });

    // Character counter untuk judul
    $('#pdf_title').on('input', function() {
        const currentLength = $(this).val().length;
        $('#pdf_title_char_count').text(`${currentLength}/255`);
    });

    // Character counter untuk deskripsi
    $('#pdf_description').on('input', function() {
        const currentLength = $(this).val().length;
        $('#pdf_description_char_count').text(`${currentLength}/1000`);
    });

    // File input change handler
    $('#pdf_file').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        if (fileName) {
            $('#file-info').html(`<i class="fas fa-file-pdf me-1 text-success"></i> File terpilih: <strong>${fileName}</strong>`);
        } else {
            $('#file-info').html('Maksimal 5MB, format PDF.');
        }
    });

    // Reset form ketika modal upload ditutup
    $('#uploadPdfModal').on('hidden.bs.modal', function () {
        resetUploadForm();
        forceCleanupModals(); // Cleanup backdrop yang mungkin tertinggal
    });

    // Bersihkan PDF viewer ketika modal PDF ditutup
    $('#pdfViewerModal').on('hidden.bs.modal', function () {
        clearPdfViewer();
        forceCleanupModals(); // Cleanup backdrop yang mungkin tertinggal
    });

    // Form submit handler
    $('#uploadPdfForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous alerts
        $('#form-alerts-container').empty();

        const formData = new FormData(this);

        // Simple frontend validation
        if (!formData.get('id_sertifikat')) {
            showFormAlert('ID Sertifikat tidak ditemukan. Mohon ulangi proses upload.', 'danger');
            return;
        }
        if (!formData.get('title')) {
            showFormAlert('Judul PDF harus diisi.', 'danger');
            return;
        }
        if (!formData.get('pdf_file') || formData.get('pdf_file').size === 0) {
            showFormAlert('File PDF harus dipilih.', 'danger');
            return;
        }

        // Show progress and disable button
        $('.upload-progress').show();
        $('#progress-bar').css('width', '0%');
        $('#progress-text').text('0%');
        $('#uploadBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Mengunggah...');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        $('#progress-bar').css('width', percentComplete + '%');
                        $('#progress-text').text(percentComplete + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    showFormAlert(response.message, 'success');
                    
                    // Reset form untuk upload berikutnya
                    $('#pdf_file').val('');
                    $('#pdf_title').val('');
                    $('#pdf_description').val('');
                    $('#pdf_title_char_count').text('0/255');
                    $('#pdf_description_char_count').text('0/1000');
                    $('#file-info').html('Maksimal 5MB, format PDF.');

                    // Set data untuk PDF viewer
                    const pdfUrl = response.data.pdf_url;
                    const pdfTitle = response.data.pdf_title;

                    $('#pdfViewerTitle').text(pdfTitle);
                    $('#pdfViewerFrame').attr('src', pdfUrl);
                    $('#pdfDownloadLink').attr('href', pdfUrl);
                    
                    // Tunggu 2 detik, tutup modal upload, buka modal viewer
                    setTimeout(function() {
                        $('#uploadPdfModal').modal('hide');
                        
                        // Force cleanup backdrop before opening new modal
                        setTimeout(function() {
                            forceCleanupModals();
                            $('#pdfViewerModal').modal('show');
                        }, 500); // Delay 500ms untuk memastikan modal upload tertutup
                    }, 2000);

                } else {
                    // Handle validation errors
                    let errorMessage = response.message || 'Terjadi kesalahan saat mengunggah file.';
                    if (response.errors) {
                        errorMessage += '<ul class="mb-0 ps-3">';
                        $.each(response.errors, function(key, value) {
                            errorMessage += `<li>${value[0]}</li>`;
                        });
                        errorMessage += '</ul>';
                    }
                    showFormAlert(errorMessage, 'danger');
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Terjadi kesalahan tidak terduga. Silakan coba lagi.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                    if (xhr.responseJSON.errors) {
                        errorMessage += '<ul class="mb-0 ps-3">';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errorMessage += `<li>${value[0]}</li>`;
                        });
                        errorMessage += '</ul>';
                        showFormAlert(errorMessage, 'warning');
                        return;
                    }
                } else if (xhr.status) {
                    errorMessage = `Error ${xhr.status}: ${xhr.statusText}`;
                }
                
                showFormAlert(errorMessage, 'danger');
            },
            complete: function() {
                // Reset button state
                $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload me-1"></i> Upload');
                
                // Hide progress bar setelah delay singkat
                setTimeout(function() {
                    $('.upload-progress').fadeOut();
                }, 1000);
            }
        });
    });
});

// Auto submit form saat input berubah atau enter ditekan
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    
    if (searchInput && searchForm) {
        // Submit form saat enter ditekan
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchForm.submit();
            }
        });
        
        // Auto submit ketika input dikosongkan (backspace/delete semua text)
        let typingTimer;
        const doneTypingInterval = 500; // 500ms delay after user stops typing
        
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            
            // Jika input kosong, langsung submit untuk reset
            if (this.value.trim() === '') {
                searchForm.submit();
            } else {
                // Jika masih ada isi, tunggu user selesai ngetik baru submit
                typingTimer = setTimeout(function() {
                    searchForm.submit();
                }, doneTypingInterval);
            }
        });
    }
});
</script>
@endsection