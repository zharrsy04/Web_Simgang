@extends('layouts.app')

@section('title', 'Daftar Portofolio Siswa')

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
        flex-wrap: wrap;
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

   /* ==================================== */
/* CSS untuk Paginasi                   */
/* ==================================== */

.pagination {
    display: flex;
    justify-content: center; /* Memusatkan paginasi secara horizontal */
    align-items: center;
    list-style: none;
    margin-top: 1.5rem; /* Memberikan jarak dari konten di atasnya */
    padding: 0;
    gap: 0.25rem; /* Jarak antar tombol paginasi */
}

/* Gaya dasar untuk setiap item paginasi (nomor, "Previous", "Next") */
.page-item {
    display: inline-block;
}

.page-link {
    padding: 0.75rem 1rem; /* Padding untuk tombol agar mudah diklik */
    font-size: 1rem;
    color: var(--text-primary);
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 0.5rem; /* Sudut membulat */
    text-decoration: none;
    transition: all 0.2s ease; /* Transisi halus untuk efek hover */
    font-weight: 500;
}

/* Efek saat tombol paginasi di-hover */
.page-link:hover {
    color: var(--primary-light);
    background-color: var(--primary-light);
    border-color: var(--primary);
}
.page-link:hover .page-text {
    color: white;
}

/* Gaya untuk tombol paginasi yang sedang aktif */
.page-item.active .page-link {
    background-color: var(--primary);
    border-color: var(--primary);
    color: white;
    cursor: default; /* Nonaktifkan kursor klik */
}

/* Gaya untuk tombol yang dinonaktifkan (disabled) */
.page-item.disabled .page-link {
    color: var(--text-secondary);
    background-color: #e9ecef;
    border-color: var(--border-color);
    cursor: not-allowed; /* Ubah kursor menjadi tidak diizinkan */
    opacity: 0.6;
}

/* Gaya untuk ikon di dalam tombol (seperti '<' dan '>') */
.page-link i {
    font-size: 0.8rem;
    color: var(--text-primary);
}

.page-link:hover i {
    color: white;
}

.page-item.active .page-link i {
    color: white;
}

.page-item.disabled .page-link i {
    color: var(--text-secondary);
}

/* Gaya tambahan untuk teks "Previous" dan "Next" jika ada */
.page-text {
    color: var(--text-primary);
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

        /* Jarak antara filter dan tabel */
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length {
        margin-bottom: 20px;
    }

    /* Agar search filter dan dropdown rapi di satu baris */
    .dataTables_wrapper .row {
        display: flex;
        align-items: center;
    }

    /* Warna gradasi header modal */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
    }

    /* Hover effect untuk tombol file */
    #modalFileLink:hover {
        background-color: #3b82f6;
        color: #fff;
        border-color: #3b82f6;
    }

    /* Timeline style */
    .timeline-section .list-group-item {
        position: relative;
        padding-left: 2.5rem;
    }

    .timeline-section .list-group-item::before {
        content: '';
        position: absolute;
        top: 0.9rem;
        left: 1rem;
        width: 10px;
        height: 10px;
        background-color: #3b82f6;
        border-radius: 50%;
    }

    .timeline-section .list-group-item:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 1.5rem;
        left: 1.45rem;
        width: 2px;
        height: calc(100% - 1.5rem);
        background-color: #e5e7eb;
    }

    /* Spinner loading */
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
    
</style>

    <h3 class="dashboard-header">Portofolio Siswa</h3>
{{-- Pesan sukses --}}
@if(session('success'))
    <div class="alert alert-success d-flex align-items-center">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-header">
        <h4>Daftar Portofolio</h4>
    </div>
    <div class="card-body">
        @if($portofolios->isEmpty())
            <p class="text-center text-muted">
                <i class="bi bi-info-circle me-1"></i> Belum ada portofolio dari siswa.
            </p>
        @else
            <div class="table-responsive">
                <table class="table table-sm" id="sertifikatTable" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">NIM/NIS</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Nama Portofolio</th>
                            <th class="text-center">Bidang</th>
                            <th class="text-center">Deskripsi</th>
                            <th class="text-center">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($portofolios as $index => $portofolio)
                            <tr class="clickable-row" data-id="{{ $portofolio->id }}" style="cursor:pointer;">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-justify">{{ $portofolio->user->username ?? '-' }}</td>
                                <td class="text-justify">{{ $portofolio->sertifikatPKL->nama_lengkap ?? '-' }}</td>
                                <td class="text-justify">{{ $portofolio->nama }}</td>
                                <td class="text-justify">{{ $portofolio->bidang }}</td>
                                <td class="text-justify">{{ $portofolio->deskripsi }}</td>
                                <td class="text-center">
                                    @if($portofolio->file)
                                        <a href="{{ asset('storage/'.$portofolio->file) }}" 
                                        target="_blank" 
                                        class="btn btn-sm btn-outline-primary"
                                        onclick="event.stopPropagation();">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">Tidak ada file</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Modal Detail Portofolio -->
<div class="modal fade" id="detailPortofolioModal" tabindex="-1" aria-labelledby="detailPortofolioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Header -->
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title fw-bold text-white" id="detailPortofolioLabel">
                    <i class="bi bi-journal-text me-2"></i> Detail Portofolio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4">
                <!-- Loading state -->
                <div id="modalLoading" class="text-center my-5">
                    <div class="spinner-border text-primary mb-3" role="status"></div>
                    <p class="text-muted">Memuat detail portofolio...</p>
                </div>

                <!-- Detail Content -->
                <div id="modalDetailContent" style="display:none;">
                    <div class="row mb-4">
                        <!-- Info Siswa -->
                        <div class="col-md-6 mb-3">
                            <div class="p-3 bg-light rounded shadow-sm h-100">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-person-badge me-2"></i> Informasi Siswa
                                </h6>
                                <p class="mb-1"><strong>NIM/NIS:</strong> <span id="modalNis" class="text-dark"></span></p>
                                <p class="mb-1"><strong>Nama Siswa:</strong> <span id="modalNama" class="text-dark"></span></p>
                                <p class="mb-0"><strong>Bidang:</strong> <span id="modalBidang" class="text-dark"></span></p>
                            </div>
                        </div>

                        <!-- Info Portofolio -->
                        <div class="col-md-6 mb-3">
                            <div class="p-3 bg-light rounded shadow-sm h-100">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-folder2-open me-2"></i> Informasi Portofolio
                                </h6>
                                <p class="mb-1"><strong>Nama Portofolio:</strong></p>
                                <p class="fw-semibold text-dark" id="modalNamaPorto"></p>

                                <p class="mb-1"><strong>Deskripsi:</strong></p>
                                <p class="text-muted small" id="modalDeskripsi"></p>

                                <p class="mb-0"><strong>File:</strong> 
                                    <a href="#" id="modalFileLink" target="_blank" class="btn btn-sm btn-outline-primary ms-1">
                                        <i class="bi bi-eye"></i> Lihat File
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Aktivitas -->
                    <div class="timeline-section mt-4">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-clock-history me-2"></i> Timeline Aktivitas
                        </h6>
                        <ul class="list-group list-group-flush shadow-sm rounded" id="modalTimeline">
                            <li class="list-group-item text-center text-muted py-3">
                                <i class="bi bi-hourglass-split me-1"></i> Memuat data...
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css" />

<script>
$(document).ready(function() {
    // Inisialisasi DataTable dengan konfigurasi lengkap
    $('#sertifikatTable').DataTable({
        processing: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            processing: '<div class="loading-spinner"></div>Memuat data...',
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            },
            emptyTable: "Tidak ada data yang tersedia",
            zeroRecords: "Tidak ditemukan data yang sesuai"
        },
        dom:
            '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        drawCallback: function(settings) {
            // Inisialisasi tooltip setiap kali tabel di-render ulang
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    // Klik baris tabel untuk buka modal detail
    $('#sertifikatTable').on('click', '.clickable-row', function() {
        var portoId = $(this).data('id');

        // Tampilkan modal dan loading
        $('#detailPortofolioModal').modal('show');
        $('#modalLoading').show();
        $('#modalDetailContent').hide();

        // AJAX ambil data detail
        $.ajax({
            url: '/mentor/portofolio/detail/' + portoId,
            type: 'GET',
            success: function(response) {
                $('#modalNis').text(response.nis);
                $('#modalNama').text(response.nama_siswa);
                $('#modalNamaPorto').text(response.nama_porto);
                $('#modalBidang').text(response.bidang);
                $('#modalDeskripsi').text(response.deskripsi);

                // --- Timeline Aktivitas ---
                if (response.created_at || response.updated_at) {
                    let timelineHtml = '';

                    if (response.created_at) {
                        let createdDate = new Date(response.created_at);
                        let formattedCreated = createdDate.toLocaleDateString('id-ID', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        timelineHtml += `
                            <li class="list-group-item">
                                <strong>Portofolio dibuat</strong><br>
                                <small class="text-muted">${formattedCreated}</small>
                            </li>
                        `;
                    }

                    if (response.updated_at && response.updated_at !== response.created_at) {
                        let updatedDate = new Date(response.updated_at);
                        let formattedUpdated = updatedDate.toLocaleDateString('id-ID', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        timelineHtml += `
                            <li class="list-group-item">
                                <strong>Terakhir diupdate</strong><br>
                                <small class="text-muted">${formattedUpdated}</small>
                            </li>
                        `;
                    }

                    $('#modalTimeline').html(timelineHtml);
                } else {
                    $('#modalTimeline').html('<li class="list-group-item text-muted">Belum ada riwayat.</li>');
                }


                // File portofolio
                if (response.file) {
                    $('#modalFileLink').attr('href', response.file).show();
                } else {
                    $('#modalFileLink').hide();
                }

                $('#modalLoading').hide();
                $('#modalDetailContent').fadeIn();
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                $('#modalLoading').html('<p class="text-danger">Gagal memuat data: ' + error + '</p>');
            }
        });
    });
});

</script>
@endsection