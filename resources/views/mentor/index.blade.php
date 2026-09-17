@extends('layouts.app')

@section('title', 'Data Siswa dan Mahasiswa')


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

    .table thead tr th,
    .table thead th,
    .table-sm thead th {
        background-color: #f3f4f6 !important;
        color: #4b5563 !important;
        border-bottom: 1px solid #e5e7eb !important;
        border-top: none !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px !important;
        white-space: nowrap !important;
        box-shadow: none !important;
        vertical-align: middle !important;
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
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

    .search-form button[type="submit"] {
        transition: all 0.2s ease;
        border: none;
        background-color: #4f46e5;
        color: white;
    }

    .search-form button[type="submit"]:hover {
        background-color: #4338ca;
    }

    .search-form button[type="submit"]:active {
        transform: scale(0.95);
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
    /* Styling untuk Detail Foto Profil */
.profile-picture-display {
    width: 250px;   /* Sesuaikan ukuran lingkaran yang diinginkan */
    height: 250px;  /* Harus sama dengan width untuk lingkaran sempurna */
    margin: 0 auto 20px auto; /* Pusatkan secara horizontal dan beri margin bawah */
    overflow: hidden;
    border-radius: 50%; /* Membuat bentuk lingkaran */
    border: 3px solid #6a0dad; /* Contoh border ungu (warna primary dari screenshot Anda) */
    box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Opsional: sedikit bayangan */
}

.profile-detail-img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Pastikan gambar mengisi area tanpa distorsi */
    /* Pastikan tidak ada border-radius atau border tambahan di sini jika sudah di .profile-picture-display */
}
</style>

<h3 class="dashboard-header">Data Siswa dan Mahasiswa</h3>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Data Siswa / Mahasiswa PKL Keseluruhan</h4>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex gap-2">
                    <a href="{{ route('mentor.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                    <a href="{{ route('penilaian.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-chart-line"></i> Kelola Penilaian
                    </a>
                </div>
                <form action="{{ route('mentor.index') }}" method="GET" class="search-form" id="mentorSearchForm">
                    <div class="input-group">
                        <input name="search" type="text" class="form-control" id="mentorSearchInput" placeholder="Cari Data" value="{{ request('search') }}" autocomplete="off">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr style="background-color: #f3f4f6 !important; border-bottom: 1px solid #e5e7eb !important;">
                            <th class="py-3 ps-3" style="background-color: #f3f4f6 !important; width: 70px; font-size: 0.75rem; font-weight: 600; color: #4b5563 !important; letter-spacing: 0.5px; white-space: nowrap !important; border-bottom: 1px solid #e5e7eb !important;">NO</th>
                            <th class="py-3 ps-3" style="background-color: #f3f4f6 !important; font-size: 0.75rem; font-weight: 600; color: #4b5563 !important; letter-spacing: 0.5px; white-space: nowrap !important; border-bottom: 1px solid #e5e7eb !important;">NAMA</th>
                            <th class="py-3" style="background-color: #f3f4f6 !important; font-size: 0.75rem; font-weight: 600; color: #4b5563 !important; letter-spacing: 0.5px; white-space: nowrap !important; border-bottom: 1px solid #e5e7eb !important;">SEKOLAH / UNIVERSITAS</th>
                            <th class="py-3" style="background-color: #f3f4f6 !important; font-size: 0.75rem; font-weight: 600; color: #4b5563 !important; letter-spacing: 0.5px; white-space: nowrap !important; border-bottom: 1px solid #e5e7eb !important;">BIDANG</th>
                            <th class="py-3" style="background-color: #f3f4f6 !important; width: 140px; font-size: 0.75rem; font-weight: 600; color: #4b5563 !important; letter-spacing: 0.5px; white-space: nowrap !important; border-bottom: 1px solid #e5e7eb !important;">TGL SERTIFIKAT</th>
                            <th class="py-3" style="background-color: #f3f4f6 !important; width: 110px; font-size: 0.75rem; font-weight: 600; color: #4b5563 !important; letter-spacing: 0.5px; white-space: nowrap !important; border-bottom: 1px solid #e5e7eb !important;">PEMBIMBING</th>
                            <th class="py-3" style="background-color: #f3f4f6 !important; width: 110px; font-size: 0.75rem; font-weight: 600; color: #4b5563 !important; letter-spacing: 0.5px; white-space: nowrap !important; border-bottom: 1px solid #e5e7eb !important;">KADIS</th>
                            <th class="text-center py-3" style="background-color: #f3f4f6 !important; width: 170px; font-size: 0.75rem; font-weight: 600; color: #4b5563 !important; letter-spacing: 0.5px; white-space: nowrap !important; border-bottom: 1px solid #e5e7eb !important;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sertifikats as $sertifikat)
                        <tr style="border-bottom: 1px solid #f1f5f9; background-color: #ffffff;">
                            <td class="py-3 ps-3 text-center" style="color: #334155; font-weight: 500; font-size: 0.95rem;">{{ ($sertifikats->currentPage() - 1) * $sertifikats->perPage() + $loop->iteration }}</td>
                            <td class="py-3 ps-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; background-color: #f1f5f9; min-width: 36px;">
                                        <i class="fas fa-graduation-cap" style="font-size: 0.95rem; color: #475569;"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold" style="color: #4338ca; font-size: 0.95rem;">{{ $sertifikat->nama_lengkap }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 text-center" style="color: #334155; font-size: 0.95rem;">{{ $sertifikat->asal_sekolah }}</td>
                            <td class="py-3 text-center" style="color: #334155; font-size: 0.95rem;">{{ $sertifikat->bidang }}</td>
                            <td class="py-3 text-center" style="color: #334155; font-size: 0.95rem;">{{ optional($sertifikat->tgl_sertifikat)->format('d-m-Y') }}</td>
                            <td class="py-3 text-center">
                                <span class="badge" style="background-color: #dcfce7 !important; color: #15803d !important; font-weight: 500; font-size: 0.75rem; padding: 5px 12px; border-radius: 6px;">
                                    {{ $sertifikat->aprove_pembimbing ? 'Sudah' : 'Belum' }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge" style="background-color: #dcfce7 !important; color: #15803d !important; font-weight: 500; font-size: 0.75rem; padding: 5px 12px; border-radius: 6px;">
                                    {{ $sertifikat->approve_kadis ? 'Sudah' : 'Belum' }}
                                </span>
                            </td>
                            <td class="text-center py-3">
                                <div class="action d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm text-white shadow-none" data-bs-toggle="modal" data-bs-target="#detailModal{{ $sertifikat->id_sertifikat }}" title="Detail" style="background-color: #2b7fff; border-color: #2b7fff; border-radius: 6px; padding: 6px 11px;">
                                        <i class="fas fa-list-ul"></i>
                                    </button>
                                    <a href="{{ route('mentor.edit', ['id_sertifikat' => $sertifikat->id_sertifikat]) }}" class="btn btn-sm text-white shadow-none" title="Edit" style="background-color: #4f46e5; border-color: #4f46e5; border-radius: 6px; padding: 6px 11px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('penilaian.create', ['id_sertifikat' => $sertifikat->id_sertifikat]) }}" class="btn btn-sm text-white shadow-none" title="Tambah Penilaian" style="background-color: #f59e0b; border-color: #f59e0b; border-radius: 6px; padding: 6px 11px;">
                                        <i class="fas fa-star"></i>
                                    </a>
                                    <a href="{{ route('sertifikat.cetak-gabungan-v5', ['id_sertifikat' => $sertifikat->id_sertifikat]) }}" target="_blank" class="btn btn-sm text-white shadow-none" title="Cetak" style="background-color: #059669; border-color: #059669; border-radius: 6px; padding: 6px 11px;">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <form action="{{ route('mentor.destroy', $sertifikat->id_sertifikat) }}" method="POST" class="d-inline delete-form"
                                          data-siswa-nama="{{ $sertifikat->nama_lengkap }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm text-white shadow-none" title="Hapus" style="background-color: #ef4444; border-color: #ef4444; border-radius: 6px; padding: 6px 11px;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal untuk detail -->
                        <div class="modal fade" id="detailModal{{ $sertifikat->id_sertifikat }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $sertifikat->id_sertifikat }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel{{ $sertifikat->id_sertifikat }}">Detail Sertifikat</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                        
                                    <div class="card mb-3">
    <div class="card-header">
        <h5>Data Pribadi</h5>
    </div>
    <div class="card-body">
        <div class="row">
            {{-- Bagian untuk Foto Profil --}}
            <div class="col-12 text-center mb-4">
                <div class="profile-picture-display">
                    @if($sertifikat->user && $sertifikat->user->currentProfilePicture)
                        {{-- Jika ada foto profil aktif yang terhubung via relasi User --}}
                        <img src="{{ asset('storage/' . $sertifikat->user->currentProfilePicture->file_path) }}" alt="Foto Profil {{ $sertifikat->nama_lengkap }}" class="img-fluid rounded-circle profile-detail-img">
                    @elseif($sertifikat->foto_profil_url)
                        {{-- Fallback jika URL foto profil langsung disimpan di SertifikatPKL --}}
                        <img src="{{ asset('storage/' . $sertifikat->foto_profil_url) }}" alt="Foto Profil {{ $sertifikat->nama_lengkap }}" class="img-fluid rounded-circle profile-detail-img">
                    @else
                        {{-- Placeholder jika tidak ada foto profil --}}
                        <img src="{{ asset('images/avatar.png') }}" alt="Foto Profil Default" class="img-fluid rounded-circle profile-detail-img">
                    @endif
                </div>
            </div>
            {{-- Akhir Bagian Foto Profil --}}

            <div class="col-12 col-md-6 mb-3">
                <p class="mb-1"><strong>Nama Lengkap:</strong> <span class="text-muted">{{ $sertifikat->nama_lengkap }}</span></p>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <p class="mb-1"><strong>Asal Sekolah:</strong> <span class="text-muted">{{ $sertifikat->asal_sekolah }}</span></p>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <p class="mb-1"><strong>NIM/NIS:</strong> <span class="text-muted">{{ $sertifikat->nim_nis }}</span></p>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <p class="mb-1"><strong>Tempat Lahir:</strong> <span class="text-muted">{{ $sertifikat->tempat_lahir }}</span></p>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <p class="mb-1"><strong>Tanggal Lahir:</strong> <span class="text-muted">{{ $sertifikat->tgl_lahir->format('d-m-Y') }}</span></p>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <p class="mb-1"><strong>Jurusan:</strong> <span class="text-muted">{{ $sertifikat->jurusan }}</span></p>
            </div>
        </div>
    </div>
</div>

                                        <!-- Data Sertifikat -->
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h5>Data Sertifikat</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 col-md-4 mb-3">
                                                        <p class="mb-1"><strong>Tanggal Mulai:</strong> <span class="text-muted">{{ $sertifikat->tgl_mulai->format('d-m-Y') }}</span></p>
                                                    </div>
                                                    <div class="col-12 col-md-4 mb-3">
                                                        <p class="mb-1"><strong>Tanggal Selesai:</strong> <span class="text-muted">{{ $sertifikat->tgl_selesai->format('d-m-Y') }}</span></p>
                                                    </div>
                                                    <div class="col-12 col-md-4 mb-3">
                                                        <p class="mb-1"><strong>Tanggal Sertifikat:</strong> <span class="text-muted">{{ $sertifikat->tgl_sertifikat->format('d-m-Y') }}</span></p>
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <p class="mb-1"><strong>No. Sertifikat:</strong> <span class="text-muted">{{ $sertifikat->no_sertifikat }}</span></p>
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
                                                        <p class="mb-1"><strong>Nama Pembimbing:</strong> <span class="text-muted">{{ $sertifikat->nm_pembimbing }}</span></p>
                                                    </div>
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <p class="mb-1"><strong>NIK Pembimbing:</strong> <span class="text-muted">{{ $sertifikat->nik_pembimbing }}</span></p>
                                                    </div>
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <p class="mb-1"><strong>Jabatan Pembimbing:</strong> <span class="text-muted">{{ $sertifikat->jabatan_pembimbing }}</span></p>
                                                    </div>
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <p class="mb-1"><strong>Bidang:</strong> <span class="text-muted">{{ $sertifikat->bidang }}</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Data pegawai -->
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h5>Data Pegawai</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <p class="mb-1"><strong>Nama Pegawai:</strong> <span class="text-muted">{{ $sertifikat->nm_kadis }}</span></p>
                                                    </div>
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <p class="mb-1"><strong>NIP Pegawai:</strong> <span class="text-muted">{{ $sertifikat->nip_kadis }}</span></p>
                                                    </div>
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <p class="mb-1"><strong>Jabatan Pegawai:</strong> <span class="text-muted">{{ $sertifikat->jabatan_pegawai }}</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Status Persetujuan -->
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h5>Status Persetujuan</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 col-md-4 mb-3">
                                                        <p class="mb-1"><strong>Approve Pembimbing:</strong></p>
                                                        <span class="badge {{ $sertifikat->aprove_pembimbing ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $sertifikat->aprove_pembimbing ? 'Sudah Disetujui' : 'Belum Disetujui' }}
                                                        </span>
                                                    </div>
                                                    <div class="col-12 col-md-4 mb-3">
                                                        <p class="mb-1"><strong>Approve Kadis:</strong></p>
                                                        <span class="badge {{ $sertifikat->approve_kadis ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $sertifikat->approve_kadis ? 'Sudah Disetujui' : 'Belum Disetujui' }}
                                                        </span>
                                                    </div>
                                                    <div class="col-12 col-md-4 mb-3">
                                                        <p class="mb-1"><strong>Status Diambil:</strong></p>
                                                        <span class="badge {{ $sertifikat->sts_diambil ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $sertifikat->sts_diambil ? 'Sudah Diambil' : 'Belum Diambil' }}
                                                        </span>
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
                    </tbody>
                </table>
                <div class="pagination-container">
                    {{ $sertifikats->onEachSide(1)->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto submit form saat input berubah atau enter ditekan
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('mentorSearchInput');
    const searchForm = document.getElementById('mentorSearchForm');
    
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
    
    // ===== SIMGANG: Konfirmasi Hapus Data Siswa PKL =====
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const namaSiswa = form.dataset.siswaNama || 'siswa ini';
            showConfirm(
                'Data "' + namaSiswa + '" akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.',
                function() {
                    const btn = form.querySelector('button[type=submit]');
                    if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; }
                    form.submit();
                },
                'Hapus Data Siswa',
                'Ya, Hapus',
                'Batal'
            );
        });
    });
});

function hitungPredikatModal(input, ketId) {
    var val = parseFloat(input.value);
    var ketSelect = document.getElementById(ketId);
    if (isNaN(val)) {
        if (ketSelect) ketSelect.value = '';
        return;
    }
    if (val < 0) {
        val = 0;
        input.value = 0;
    } else if (val > 100) {
        val = 100;
        input.value = 100;
    }
    if (ketSelect) {
        if (val >= 85) ketSelect.value = 'A';
        else if (val >= 80) ketSelect.value = 'AB';
        else if (val >= 75) ketSelect.value = 'B';
        else if (val >= 70) ketSelect.value = 'BC';
        else if (val >= 65) ketSelect.value = 'C';
        else if (val >= 55) ketSelect.value = 'D';
        else ketSelect.value = 'E';
    }
}
</script>

@endsection