@extends('layouts.app')

@section('title', 'Logbook Kegiatan Harian')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- DataTables & Buttons CSS (Format Tabel MyBest) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<style>
    :root {
        --primary-blue: #2563eb;
        --primary-blue-hover: #1d4ed8;
        --primary-gradient: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        --primary-purple: #5039eb;
        --card-bg: #ffffff;
        --page-bg: #f8fafc;
        --text-slate-900: #0f172a;
        --text-slate-700: #334155;
        --text-slate-500: #64748b;
        --border-slate-200: #e2e8f0;
    }

    body {
        font-family: 'Poppins', 'Plus Jakarta Sans', sans-serif;
        background-color: var(--page-bg);
        color: var(--text-slate-700);
    }

    .logbook-wrapper {
        max-width: 1300px;
        margin: 0 auto;
        padding: 24px 16px 50px;
    }

    /* 1. Header Banner Gradient */
    .banner-header-gradient {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 28px 36px;
        color: #ffffff;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.25);
        position: relative;
        overflow: hidden;
    }

    .banner-header-gradient::after {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .banner-title {
        font-size: 1.65rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .banner-subtitle {
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 14px;
        max-width: 800px;
        line-height: 1.5;
    }


    /* 2. Stat Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .stat-card-clean {
        background: #ffffff;
        border: 1px solid var(--border-slate-200);
        border-radius: 16px;
        padding: 18px 22px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-card-title {
        font-size: 0.74rem;
        font-weight: 700;
        color: #94a3b8;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .stat-card-num {
        font-size: 1.55rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }

    .stat-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-purple { background: #f5f3ff; color: #6d28d9; }
    .icon-green { background: #ecfdf5; color: #10b981; }

    /* Tombol Tulis Logbook Utama */
    .btn-tulis-logbook {
        background: var(--primary-gradient);
        color: #ffffff;
        border: none;
        border-radius: 50px;
        padding: 10px 24px;
        font-size: 0.92rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 6px 16px -2px rgba(37, 99, 235, 0.35);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-tulis-logbook:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px -2px rgba(37, 99, 235, 0.45);
        color: #ffffff;
    }

    /* 3. Card Rekap Format MyBest */
    .card-rekap-box {
        background: #ffffff;
        border: 1px solid var(--border-slate-200);
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .card-rekap-header {
        background-color: #e9edf5;
        color: #1e293b;
        font-weight: 700;
        font-size: 1.05rem;
        padding: 14px 22px;
        border-bottom: 1.5px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .card-rekap-header-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-rekap-body {
        padding: 22px;
    }

    /* DataTables toolbar buttons - persis MyBest */
    .dt-buttons {
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        flex-wrap: wrap !important;
    }

    .btn-mybest-export {
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        color: #334155 !important;
        font-size: 0.84rem !important;
        font-weight: 500 !important;
        padding: 5px 14px !important;
        border-radius: 4px !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
        transition: all 0.2s ease !important;
    }

    .btn-mybest-export:hover {
        background-color: #f1f5f9 !important;
        border-color: #94a3b8 !important;
        color: #0f172a !important;
    }

    .dataTables_length {
        display: inline-flex !important;
        align-items: center !important;
        margin-left: 10px !important;
        font-size: 0.88rem !important;
        color: #475569 !important;
    }

    .dataTables_length select {
        border: 1px solid #cbd5e1 !important;
        border-radius: 4px !important;
        padding: 5px 26px 5px 10px !important;
        font-size: 0.86rem !important;
        color: #334155 !important;
        outline: none !important;
        margin: 0 6px !important;
    }

    .dataTables_filter {
        font-size: 0.88rem !important;
        color: #475569 !important;
    }

    .dataTables_filter input {
        border: 1px solid #cbd5e1 !important;
        border-radius: 4px !important;
        padding: 5px 12px !important;
        font-size: 0.86rem !important;
        outline: none !important;
        margin-left: 6px !important;
        transition: border-color 0.2s !important;
    }

    .dataTables_filter input:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15) !important;
    }

    /* Tabel Rekap Style MyBest */
    .table-rekap-mybest {
        width: 100% !important;
        border: 1px solid #e2e8f0 !important;
        border-collapse: collapse !important;
        font-size: 0.875rem !important;
    }

    .table-rekap-mybest thead th {
        background-color: #eef2f7 !important;
        color: #334155 !important;
        font-size: 0.76rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        border-bottom: 2px solid #cbd5e1 !important;
        border-top: 1px solid #e2e8f0 !important;
        padding: 12px 14px !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }

    .table-rekap-mybest tbody td {
        padding: 14px 14px !important;
        border-bottom: 1px solid #e2e8f0 !important;
        vertical-align: middle !important;
        color: #334155 !important;
        line-height: 1.5 !important;
    }

    .table-rekap-mybest tbody tr:hover td {
        background-color: #f8fafc !important;
    }

    /* Badge Hadir / Tercatat Persis MyBest */
    .badge-status-hadir {
        background-color: #198754;
        color: #ffffff;
        padding: 6px 14px;
        border-radius: 4px;
        font-size: 0.82rem;
        font-weight: 600;
        display: inline-block;
        letter-spacing: 0.3px;
        text-align: center;
        white-space: nowrap;
    }

    /* Text wrap styling */
    .col-uraian-wrap {
        min-width: 220px;
        max-width: 360px;
        white-space: pre-line;
        word-break: break-word;
    }

    .col-topik-wrap {
        min-width: 180px;
        max-width: 240px;
    }

    .btn-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-icon-edit {
        background: #eff6ff;
        color: #2563eb;
        border-color: #bfdbfe;
    }

    .btn-icon-edit:hover {
        background: #2563eb;
        color: #ffffff;
    }

    .btn-icon-delete {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .btn-icon-delete:hover {
        background: #dc2626;
        color: #ffffff;
    }

    .btn-icon-doc {
        background: #f0fdf4;
        color: #16a34a;
        border-color: #bbf7d0;
    }

    .btn-icon-doc:hover {
        background: #16a34a;
        color: #ffffff;
    }

    /* Print Header */
    @media print {
        body {
            background: #ffffff;
        }
        .banner-header-gradient, .stats-row, .card-rekap-header, .btn-tulis-logbook, .dt-buttons, .dataTables_length, .dataTables_filter, .dataTables_paginate, .dataTables_info, .col-aksi, .navbar, .sidebar {
            display: none !important;
        }
        .logbook-wrapper, .card-rekap-box {
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }
        .print-header-only {
            display: block !important;
            margin-bottom: 20px;
        }
    }

    .print-header-only {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="logbook-wrapper">

    <!-- 1. Header Banner Gradient -->
    <div class="banner-header-gradient">
        <div class="banner-title">
            <i class="fas fa-book-open"></i>
            <span>Logbook Kegiatan Harian Magang</span>
        </div>
        <p class="banner-subtitle mb-0">
            Catat dan rekap setiap kegiatan serta penugasan harian Anda selama periode PKL di Dinas Komunikasi dan Informatika Kabupaten Cilacap.
        </p>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i><strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <i class="fas fa-times-circle me-2"></i><strong>Gagal:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(!$siswa)
        <div class="card text-center py-5 border-0 rounded-4 shadow-sm">
            <div class="card-body">
                <i class="fas fa-user-clock text-warning fa-4x mb-3"></i>
                <h4 class="fw-bold text-dark">Data Magang Belum Ditemukan</h4>
                <p class="text-muted" style="max-width: 500px; margin: 0 auto 20px;">
                    Profil magang Anda belum aktif di sistem SIMGANG. Pastikan pengajuan magang Anda telah diverifikasi oleh Sekretariat terlebih dahulu.
                </p>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-primary px-4 py-2 rounded-pill">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    @else

        <!-- 2. Ringkasan Statistik Logbook -->
        <div class="stats-row">
            <div class="stat-card-clean">
                <div>
                    <div class="stat-card-title">TOTAL HARI MENGISI LOGBOOK</div>
                    <div class="stat-card-num">{{ $stats['total'] }} <span class="small text-muted fw-normal fs-6">Hari</span></div>
                </div>
                <div class="stat-card-icon icon-blue">
                    <i class="fas fa-clipboard-check"></i>
                </div>
            </div>

            <div class="stat-card-clean">
                <div>
                    <div class="stat-card-title">KEGIATAN BULAN INI</div>
                    <div class="stat-card-num">{{ $stats['bulan_ini'] }} <span class="small text-muted fw-normal fs-6">Catatan</span></div>
                </div>
                <div class="stat-card-icon icon-purple">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>

            <div class="stat-card-clean">
                <div>
                    <div class="stat-card-title">STATUS LOGBOOK HARI INI</div>
                    <div class="stat-card-num">
                        @if($stats['sudah_isi_hari_ini'])
                            <span class="text-success fs-5"><i class="fas fa-check-circle me-1"></i> Sudah Mengisi</span>
                        @else
                            <span class="text-warning fs-5"><i class="fas fa-exclamation-circle me-1"></i> Belum Mengisi</span>
                        @endif
                    </div>
                </div>
                <div class="stat-card-icon icon-green">
                    <i class="fas fa-pen-nib"></i>
                </div>
            </div>
        </div>

        <!-- Print Header (Hanya saat cetak) -->
        <div class="print-header-only">
            <h4 class="fw-bold mb-1">REKAP LOGBOOK KEGIATAN HARIAN MAGANG</h4>
            <p class="mb-1"><strong>Nama Siswa:</strong> {{ $siswa->nama_lengkap }} | <strong>NIM/NIS:</strong> {{ $siswa->nim_nis }}</p>
            <p class="mb-3"><strong>Instansi:</strong> Dinas Komunikasi dan Informatika Kabupaten Cilacap | <strong>Bidang:</strong> {{ $siswa->bidang ?? 'Bidang Magang' }}</p>
            <hr>
        </div>

        <!-- 3. Card Rekap Format Tabel MyBest -->
        <div class="card-rekap-box">
            <div class="card-rekap-header">
                <div class="card-rekap-header-title">
                    <i class="fas fa-table text-primary"></i>
                    <span>Rekap Logbook Kegiatan</span>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahLogbook">
                        <i class="fas fa-plus me-1"></i> + Tulis Logbook Baru
                    </button>
                </div>
            </div>

            <div class="card-rekap-body">
                <div class="table-responsive">
                    <table id="tabelRekapLogbook" class="table table-rekap-mybest align-middle">
                        <thead>
                            <tr>
                                <th style="width: 40px;" class="text-center">#</th>
                                <th style="width: 110px;" class="text-center">STATUS</th>
                                <th style="width: 120px;">TANGGAL</th>
                                <th style="width: 190px;">KEGIATAN / TOPIK</th>
                                <th style="width: 120px;">WAKTU</th>
                                <th>RANGKUMAN & URAIAN KEGIATAN</th>
                                <th style="width: 200px;">OUTPUT & KENDALA</th>
                                <th style="width: 70px;" class="text-center">BUKTI</th>
                                <th style="width: 90px;" class="text-center col-aksi">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logbooks as $index => $item)
                                <tr>
                                    <td class="text-center fw-bold text-muted">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-status-hadir">Hadir</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l') }}</small>
                                    </td>
                                    <td class="col-topik-wrap">
                                        <strong class="text-dark">{{ $item->judul_kegiatan }}</strong>
                                        <div class="small text-muted">{{ $siswa->bidang ?? 'Bidang Magang' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1">
                                            <i class="far fa-clock me-1 text-primary"></i>
                                            {{ $item->jam_mulai ? substr($item->jam_mulai, 0, 5) : '08:00' }} - {{ $item->jam_selesai ? substr($item->jam_selesai, 0, 5) : '16:00' }}
                                        </span>
                                    </td>
                                    <td class="col-uraian-wrap">
                                        {{ $item->deskripsi_kegiatan }}
                                    </td>
                                    <td>
                                        @if(!empty($item->hasil_kegiatan))
                                            <div class="small mb-1"><strong class="text-success">Output:</strong> {{ $item->hasil_kegiatan }}</div>
                                        @endif
                                        @if(!empty($item->kendala))
                                            <div class="small text-danger"><strong class="text-danger">Kendala:</strong> {{ $item->kendala }}</div>
                                        @endif
                                        @if(empty($item->hasil_kegiatan) && empty($item->kendala))
                                            <span class="text-muted small fst-italic">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->dokumentasi)
                                            @php
                                                $ext = strtolower(pathinfo($item->dokumentasi, PATHINFO_EXTENSION));
                                            @endphp
                                            @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                                <button type="button" class="btn-action-icon btn-icon-doc" title="Lihat Foto Bukti" onclick="previewImage('{{ $item->dokumentasi_url }}', '{{ addslashes($item->judul_kegiatan) }}')">
                                                    <i class="fas fa-image"></i>
                                                </button>
                                            @else
                                                <a href="{{ $item->dokumentasi_url }}" target="_blank" class="btn-action-icon btn-icon-doc" title="Buka Dokumen PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center col-aksi">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn-action-icon btn-icon-edit" title="Edit Logbook" onclick="openEditModal({{ json_encode($item) }})">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('siswa.logbook.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan logbook tanggal ini?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-icon btn-icon-delete" title="Hapus Logbook">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @endif

</div>

<!-- MODAL 1: Tambah Logbook Kegiatan -->
<div class="modal fade" id="modalTambahLogbook" tabindex="-1" aria-labelledby="modalTambahLogbookLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalTambahLogbookLabel">
                    <i class="fas fa-pen-nib text-primary me-2"></i>Tulis Logbook Kegiatan Harian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('siswa.logbook.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 py-3">
                    <p class="text-muted small mb-4">
                        Isikan rincian kegiatan dan pekerjaan yang Anda kerjakan hari ini selama masa magang di Diskominfo Cilacap.
                    </p>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">TANGGAL KEGIATAN <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold small text-secondary">JAM MULAI</label>
                            <input type="time" name="jam_mulai" class="form-control rounded-3" value="08:00">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold small text-secondary">JAM SELESAI</label>
                            <input type="time" name="jam_selesai" class="form-control rounded-3" value="16:00">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">JUDUL / TOPIK KEGIATAN <span class="text-danger">*</span></label>
                        <input type="text" name="judul_kegiatan" class="form-control rounded-3" placeholder="Contoh: Perancangan modul absensi dan uji coba sistem" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">URAIAN / RANGKUMAN KEGIATAN <span class="text-danger">*</span></label>
                        <textarea name="deskripsi_kegiatan" rows="4" class="form-control rounded-3" placeholder="Uraikan aktivitas atau pekerjaan yang dikerjakan secara rinci..." required></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">HASIL / OUTPUT PEKERJAAN</label>
                            <textarea name="hasil_kegiatan" rows="2" class="form-control rounded-3" placeholder="Output yang diselesaikan (opsional)..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">KENDALA / HAMBATAN</label>
                            <textarea name="kendala" rows="2" class="form-control rounded-3" placeholder="Kendala yang dihadapi jika ada (opsional)..."></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">BUKTI DOKUMENTASI / FOTO (OPSIONAL)</label>
                        <input type="file" name="dokumentasi" class="form-control rounded-3" accept=".jpg,.jpeg,.png,.pdf">
                        <small class="text-muted">Format: JPG, PNG, atau PDF (Maksimal 5MB).</small>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fas fa-save me-1"></i> Simpan Catatan Logbook
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 2: Edit Logbook Kegiatan -->
<div class="modal fade" id="modalEditLogbook" tabindex="-1" aria-labelledby="modalEditLogbookLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalEditLogbookLabel">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Catatan Logbook
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditLogbook" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 py-3">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">TANGGAL KEGIATAN <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold small text-secondary">JAM MULAI</label>
                            <input type="time" name="jam_mulai" id="edit_jam_mulai" class="form-control rounded-3">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold small text-secondary">JAM SELESAI</label>
                            <input type="time" name="jam_selesai" id="edit_jam_selesai" class="form-control rounded-3">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">JUDUL / TOPIK KEGIATAN <span class="text-danger">*</span></label>
                        <input type="text" name="judul_kegiatan" id="edit_judul_kegiatan" class="form-control rounded-3" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">URAIAN / RANGKUMAN KEGIATAN <span class="text-danger">*</span></label>
                        <textarea name="deskripsi_kegiatan" id="edit_deskripsi_kegiatan" rows="4" class="form-control rounded-3" required></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">HASIL / OUTPUT PEKERJAAN</label>
                            <textarea name="hasil_kegiatan" id="edit_hasil_kegiatan" rows="2" class="form-control rounded-3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">KENDALA / HAMBATAN</label>
                            <textarea name="kendala" id="edit_kendala" rows="2" class="form-control rounded-3"></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">GANTI BUKTI DOKUMENTASI (OPSIONAL)</label>
                        <input type="file" name="dokumentasi" class="form-control rounded-3" accept=".jpg,.jpeg,.png,.pdf">
                        <div id="edit_current_doc" class="small text-muted mt-1"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 3: Preview Gambar Dokumentasi -->
<div class="modal fade" id="modalPreviewDoc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <h6 class="modal-title fw-bold" id="previewDocTitle">Foto Dokumentasi Kegiatan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="previewDocImg" src="" alt="Dokumentasi" class="img-fluid rounded-3 shadow-sm" style="max-height: 520px;">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- jQuery, DataTables & DataTables Buttons (Copy, CSV, Excel, PDF, Print persis MyBest) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabelRekapLogbook').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: "<'row mb-3 align-items-center'<'col-md-7 d-flex align-items-center gap-2 flex-wrap'B l><'col-md-5 d-flex justify-content-md-end'f>><'row'<'col-12'tr>><'row mt-3 align-items-center'<'col-md-6'i><'col-md-6 d-flex justify-content-md-end'p>>",
            buttons: [
                {
                    extend: 'copy',
                    text: 'Copy',
                    className: 'btn-mybest-export',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    className: 'btn-mybest-export',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn-mybest-export',
                    title: 'Rekap Logbook Kegiatan Siswa - SIMGANG',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'btn-mybest-export',
                    title: 'Rekap Logbook Kegiatan Siswa - SIMGANG',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn-mybest-export',
                    title: 'Rekap Logbook Kegiatan Siswa - SIMGANG',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
                }
            ],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                zeroRecords: "Tidak ada catatan logbook yang cocok",
                emptyTable: "Belum ada catatan logbook kegiatan",
                paginate: {
                    previous: "Previous",
                    next: "Next"
                }
            },
            order: [[2, 'desc']], // Urutkan default berdasarkan tanggal kegiatan
            columnDefs: [
                { orderable: false, targets: [7, 8] } // Kolom bukti & aksi tidak diurutkan
            ]
        });
    });

    function previewImage(url, title) {
        document.getElementById('previewDocImg').src = url;
        document.getElementById('previewDocTitle').textContent = title || 'Foto Dokumentasi Kegiatan';
        const modal = new bootstrap.Modal(document.getElementById('modalPreviewDoc'));
        modal.show();
    }

    function openEditModal(item) {
        const form = document.getElementById('formEditLogbook');
        form.action = "{{ url('siswa/logbook') }}/" + item.id;

        document.getElementById('edit_tanggal').value = item.tanggal ? item.tanggal.substring(0, 10) : '';
        document.getElementById('edit_jam_mulai').value = item.jam_mulai ? item.jam_mulai.substring(0, 5) : '';
        document.getElementById('edit_jam_selesai').value = item.jam_selesai ? item.jam_selesai.substring(0, 5) : '';
        document.getElementById('edit_judul_kegiatan').value = item.judul_kegiatan || '';
        document.getElementById('edit_deskripsi_kegiatan').value = item.deskripsi_kegiatan || '';
        document.getElementById('edit_hasil_kegiatan').value = item.hasil_kegiatan || '';
        document.getElementById('edit_kendala').value = item.kendala || '';

        const docBox = document.getElementById('edit_current_doc');
        if (item.dokumentasi) {
            docBox.innerHTML = '<span class="text-success"><i class="fas fa-check me-1"></i> File dokumentasi sudah ada. Pilih file baru hanya jika ingin menggantinya.</span>';
        } else {
            docBox.innerHTML = 'Belum ada file dokumentasi yang diunggah.';
        }

        const modal = new bootstrap.Modal(document.getElementById('modalEditLogbook'));
        modal.show();
    }
</script>
@endsection
