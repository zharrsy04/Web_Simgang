@extends('layouts.app')

@section('title', 'Daftar Siswa Aktif')

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
        font-size: 1.8rem; /* Sesuaikan ukuran font */
        font-weight: 700; /* Lebih tebal */
        color: #ffffff; /* Warna teks lebih gelap */
        margin-bottom: 2rem; /* Tambah margin bawah */
        text-align: left; /* Align ke kiri sesuai gambar */
        margin-top: 2rem;
        letter-spacing: -0.5px; /* Sedikit rapatkan huruf */
    }
    
    /* Action buttons container */
    .action {
        display: flex;
        gap: 0.35rem;
        justify-content: center;
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

    /* Text styling */
    .text-muted {
        color: #6b7280;
    }

    .text-nowrap {
        white-space: nowrap;
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
    
    .btn-warning { 
        background-color: #f59e0b;
        border-color: #f59e0b;
        color: white;
    }
    
    .btn-success { 
        background-color: #10b981;
        border-color: #10b981;
    }
    
    .btn-danger { 
        background-color: #ef4444;
        border-color: #ef4444;
    }

    .btn-info { 
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    .btn-secondary { 
        background-color: #6b7280;
        border-color: #6b7280;
    }

    /* Search form */
    .search-form {
        background-color: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
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

    /* Badge styling */
    .badge {
        font-weight: 500;
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        font-size: 0.7rem;
        letter-spacing: 0.3px;
    }

    .bg-success {
        background-color: #d1fae5 !important;
        color: #065f46 !important;
    }

    .bg-danger {
        background-color: #fee2e2 !important;
        color: #b91c1c !important;
    }

    /* Modal styling */
    .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
        color: white;
        border-bottom: none;
        padding: 1.25rem;
    }

    .modal-header .btn-close {
        color: white;
        opacity: 1;
    }

    .modal-title {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1rem;
    }

    /* Form controls */
    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.65rem 1rem;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-label {
        font-weight: 500;
        color: #4b5563;
        margin-bottom: 0.5rem;
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

    /* Modal inner cards */
    .modal .card {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 1rem;
    }

    .modal .card-header {
        background: #f3f4f6;
        color: #4b5563;
        font-size: 0.9rem;
        padding: 0.75rem 1rem;
    }

    .modal .card-header h5 {
        margin: 0;
        font-weight: 600;
    }

    /* Alert styling */
    .alert {
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: none;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
    }
</style>

<h3 class="dashboard-header">Data Siswa dan Mahasiswa Aktif</h3>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Data Siswa / Mahasiswa PKL Aktif</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end align-items-center mb-4">
                <form action="{{ route('dashboardsiswa.siswaaktif') }}" method="GET" class="search-form">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input name="search" type="search" class="form-control" placeholder="Cari Data" value="{{ request('search') }}">
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table align-middle" style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr style="background-color: #f8f9fa; border-bottom: 1px solid #edf2f7;">
                            <th class="text-center text-muted py-3" style="width: 60px; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px;">NO</th>
                            <th class="text-muted py-3 ps-3" style="font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px;">NAMA</th>
                            <th class="text-muted py-3" style="font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px;">SEKOLAH / UNIVERSITAS</th>
                            <th class="text-muted py-3" style="font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px;">BIDANG</th>
                            <th class="text-center text-muted py-3" style="width: 110px; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px;">TGL MULAI</th>
                            <th class="text-center text-muted py-3" style="width: 110px; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px;">TGL SELESAI</th>
                            <th class="text-center text-muted py-3" style="width: 140px; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswaAktif as $siswa)
                        <tr style="border-bottom: 1px solid #f1f5f9; background-color: #ffffff;">
                            <td class="text-center py-3" style="color: #334155; font-weight: 500; font-size: 0.95rem;">
                                {{ ($siswaAktif->currentPage() - 1) * $siswaAktif->perPage() + $loop->iteration }}
                            </td>
                            <td class="py-3 ps-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; background-color: #f1f5f9; min-width: 36px;">
                                        <i class="fas fa-graduation-cap" style="font-size: 0.95rem; color: #475569;"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold" style="color: #4338ca; font-size: 0.95rem;">{{ $siswa->nama_lengkap }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3" style="color: #334155; font-size: 0.95rem;">{{ $siswa->asal_sekolah }}</td>
                            <td class="py-3" style="color: #334155; font-size: 0.95rem;">{{ $siswa->bidang }}</td>
                            <td class="text-center py-3" style="color: #334155; font-size: 0.95rem;">{{ optional($siswa->tgl_mulai)->format('d-m-Y') }}</td>
                            <td class="text-center py-3" style="color: #334155; font-size: 0.95rem;">{{ optional($siswa->tgl_selesai)->format('d-m-Y') }}</td>
                            <td class="text-center py-3">
                                <div class="action d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm text-white shadow-none" data-bs-toggle="modal" data-bs-target="#detailModal{{ $siswa->id_sertifikat }}" title="Detail" style="background-color: #2b7fff; border-color: #2b7fff; border-radius: 6px; padding: 6px 11px;">
                                        <i class="fas fa-list-ul"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Tidak ada data peserta magang aktif.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="pagination-container">
                    {{ $siswaAktif->onEachSide(1)->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($siswaAktif as $siswa)
<!-- Modal untuk detail -->
<div class="modal fade" id="detailModal{{ $siswa->id_sertifikat }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $siswa->id_sertifikat }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel{{ $siswa->id_sertifikat }}">Detail Siswa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                
                <!-- Data Pribadi -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Data Pribadi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <p class="mb-1"><strong>Nama Lengkap:</strong> <span class="text-muted">{{ $siswa->nama_lengkap }}</span></p>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <p class="mb-1"><strong>Asal Sekolah:</strong> <span class="text-muted">{{ $siswa->asal_sekolah }}</span></p>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <p class="mb-1"><strong>NIM/NIS:</strong> <span class="text-muted">{{ $siswa->nim_nis }}</span></p>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <p class="mb-1"><strong>Tempat Lahir:</strong> <span class="text-muted">{{ $siswa->tempat_lahir ?? '-' }}</span></p>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <p class="mb-1"><strong>Tanggal Lahir:</strong> <span class="text-muted">{{ $siswa->tgl_lahir ? $siswa->tgl_lahir->format('d-m-Y') : '-' }}</span></p>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <p class="mb-1"><strong>Jurusan:</strong> <span class="text-muted">{{ $siswa->jurusan ?? '-' }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data PKL -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Data PKL</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <p class="mb-1"><strong>Tanggal Mulai:</strong> <span class="text-muted">{{ $siswa->tgl_mulai->format('d-m-Y') }}</span></p>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <p class="mb-1"><strong>Tanggal Selesai:</strong> <span class="text-muted">{{ $siswa->tgl_selesai->format('d-m-Y') }}</span></p>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <p class="mb-1"><strong>Bidang:</strong> <span class="text-muted">{{ $siswa->bidang }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Pembimbing -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Data Pembimbing</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <p class="mb-1"><strong>Nama Pembimbing:</strong> <span class="text-muted">{{ $siswa->nm_pembimbing ?? '-' }}</span></p>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <p class="mb-1"><strong>NIP Pembimbing:</strong> <span class="text-muted">{{ $siswa->nik_pembimbing ?? '-' }}</span></p>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <p class="mb-1"><strong>Jabatan Pembimbing:</strong> <span class="text-muted">{{ $siswa->jabatan_pembimbing ?? '-' }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Status PKL</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <p class="mb-1"><strong>Status Sertifikat:</strong></p>
                                @if($siswa->sts_diambil)
                                    <span class="badge bg-success">Sudah Menerima Sertifikat</span>
                                @else
                                    <span class="badge bg-danger">Belum Menerima Sertifikat</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection