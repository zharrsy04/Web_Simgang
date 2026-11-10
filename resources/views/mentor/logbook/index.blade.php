@extends('layouts.app')

@section('title', 'Logbook Siswa Bimbingan')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- DataTables & Buttons CSS (Format Tabel MyBest) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<style>
    :root {
        --primary-purple: #5039eb;
        --primary-dark: #3730a3;
        --primary-gradient: linear-gradient(135deg, #5039eb 0%, #3730a3 100%);
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

    .mentor-logbook-wrapper {
        max-width: 1320px;
        margin: 0 auto;
        padding: 24px 16px 50px;
    }

    /* 1. Header Banner */
    .banner-header-gradient {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 28px 36px;
        color: #ffffff;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px -5px rgba(80, 57, 235, 0.25);
        position: relative;
        overflow: hidden;
    }

    .banner-header-gradient::after {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 220px;
        height: 220px;
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
        margin-bottom: 0;
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

    .icon-purple { background: #f5f3ff; color: #6d28d9; }
    .icon-green { background: #ecfdf5; color: #10b981; }
    .icon-blue { background: #eff6ff; color: #2563eb; }

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

    .card-rekap-body {
        padding: 22px;
    }

    /* DataTables toolbar buttons - format MyBest */
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
        border-color: #5039eb !important;
        box-shadow: 0 0 0 2px rgba(80, 57, 235, 0.15) !important;
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

    /* Badge Hadir Persis MyBest */
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

    .col-uraian-wrap {
        min-width: 220px;
        max-width: 360px;
        white-space: pre-line;
        word-break: break-word;
    }

    .student-cell-name {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .student-cell-sub {
        font-size: 0.78rem;
        color: #64748b;
        line-height: 1.35;
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

    .btn-icon-doc {
        background: #f0fdf4;
        color: #16a34a;
        border-color: #bbf7d0;
    }

    .btn-icon-doc:hover {
        background: #16a34a;
        color: #ffffff;
    }

    @media print {
        body { background: #ffffff; }
        .banner-header-gradient, .stats-row, .card-rekap-header, .filter-form-box, .dt-buttons, .dataTables_length, .dataTables_filter, .dataTables_paginate, .dataTables_info, .navbar, .sidebar {
            display: none !important;
        }
        .mentor-logbook-wrapper, .card-rekap-box {
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
<div class="mentor-logbook-wrapper">

    <!-- 1. Header Banner -->
    <div class="banner-header-gradient">
        <div class="banner-title">
            <i class="fas fa-book-reader"></i>
            <span>Logbook Aktivitas Harian Peserta Magang</span>
        </div>
        <p class="banner-subtitle">
            Pantau dan rekap seluruh catatan kegiatan harian yang dikerjakan oleh siswa bimbingan Anda selama masa magang di Diskominfo Cilacap.
        </p>
    </div>

    <!-- 2. Ringkasan Statistik -->
    <div class="stats-row">
        <div class="stat-card-clean">
            <div>
                <div class="stat-card-title">TOTAL SELURUH CATATAN</div>
                <div class="stat-card-num">{{ $stats['total_kegiatan'] }} <span class="small text-muted fw-normal fs-6">Logbook</span></div>
            </div>
            <div class="stat-card-icon icon-purple">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>

        <div class="stat-card-clean">
            <div>
                <div class="stat-card-title">CATATAN HARI INI</div>
                <div class="stat-card-num">{{ $stats['kegiatan_hari_ini'] }} <span class="small text-muted fw-normal fs-6">Kegiatan</span></div>
            </div>
            <div class="stat-card-icon icon-green">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>

        <div class="stat-card-clean">
            <div>
                <div class="stat-card-title">TOTAL SISWA BIMBINGAN</div>
                <div class="stat-card-num">{{ $stats['total_siswa'] }} <span class="small text-muted fw-normal fs-6">Orang</span></div>
            </div>
            <div class="stat-card-icon icon-blue">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
    </div>

    <!-- Filter Bar Cepat -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 filter-form-box">
        <div class="card-body p-3">
            <form action="{{ route('mentor.logbook.index') }}" method="GET" class="row g-2 align-items-center">
                <!-- Filter Siswa -->
                <div class="col-lg-4 col-md-5">
                    <label class="form-label small fw-bold text-muted mb-1">Filter Siswa Bimbingan:</label>
                    <select name="siswa_id" class="form-select form-select-sm rounded-pill">
                        <option value="">Semua Siswa Bimbingan ({{ $stats['total_siswa'] }})</option>
                        @foreach($mentoredStudents as $st)
                            <option value="{{ $st->id_sertifikat }}" {{ $filterStudent == $st->id_sertifikat ? 'selected' : '' }}>
                                {{ $st->nama_lengkap }} ({{ $st->bidang ?? 'Informatika' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Tanggal -->
                <div class="col-lg-3 col-md-4">
                    <label class="form-label small fw-bold text-muted mb-1">Filter Tanggal:</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="far fa-calendar-alt"></i></span>
                        <input type="date" name="tanggal" value="{{ $filterTanggal }}" class="form-control form-control-sm border-start-0" title="Filter Tanggal">
                    </div>
                </div>

                <!-- Action button -->
                <div class="col-lg-3 col-md-3 d-flex align-items-end gap-2 pt-3 pt-md-4">
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">
                        <i class="fas fa-filter me-1"></i> Terapkan
                    </button>
                    @if($filterStudent || $filterTanggal || $search)
                        <a href="{{ route('mentor.logbook.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="fas fa-times me-1"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Print Header -->
    <div class="print-header-only">
        <h4 class="fw-bold mb-1">REKAP LOGBOOK AKTIVITAS HARIAN SISWA BIMBINGAN</h4>
        <p class="mb-1"><strong>Instansi:</strong> Dinas Komunikasi dan Informatika Kabupaten Cilacap</p>
        <p class="mb-3"><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y H:i') }} WIB</p>
        <hr>
    </div>

    <!-- 3. Card Rekap Format Tabel MyBest -->
    <div class="card-rekap-box">
        <div class="card-rekap-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-table text-primary"></i>
                <span>Rekap Catatan Logbook Siswa Bimbingan</span>
            </div>
            <span class="badge bg-light text-dark border px-3 py-1 fw-semibold">
                {{ count($logbooks) }} Total Catatan Ditemukan
            </span>
        </div>

        <div class="card-rekap-body">
            <div class="table-responsive">
                <table id="tabelRekapMentor" class="table table-rekap-mybest align-middle">
                    <thead>
                        <tr>
                            <th style="width: 40px;" class="text-center">#</th>
                            <th style="width: 100px;" class="text-center">STATUS</th>
                            <th style="width: 110px;">TANGGAL</th>
                            <th style="width: 200px;">PESERTA MAGANG</th>
                            <th style="width: 180px;">KEGIATAN / TOPIK</th>
                            <th style="width: 110px;">WAKTU</th>
                            <th>RANGKUMAN & URAIAN AKTIVITAS</th>
                            <th style="width: 180px;">OUTPUT & KENDALA</th>
                            <th style="width: 70px;" class="text-center">BUKTI</th>
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
                                <td>
                                    <div class="student-cell-name">
                                        {{ $item->siswa->nama_lengkap ?? 'Peserta Magang' }}
                                    </div>
                                    <div class="student-cell-sub">
                                        <span>NIM/NIS: {{ $item->siswa->nim_nis ?? '-' }}</span><br>
                                        <span class="text-muted">{{ $item->siswa->asal_sekolah ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-dark">{{ $item->judul_kegiatan }}</strong>
                                    <div class="small text-muted">{{ $item->siswa->bidang ?? 'Bidang Informatika' }}</div>
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
                                            <button type="button" class="btn-action-icon btn-icon-doc" title="Buka Foto Bukti" onclick="previewImage('{{ $item->dokumentasi_url }}', '{{ addslashes($item->judul_kegiatan) }} ({{ addslashes($item->siswa->nama_lengkap ?? '') }})')">
                                                <i class="fas fa-image"></i>
                                            </button>
                                        @else
                                            <a href="{{ $item->dokumentasi_url }}" target="_blank" class="btn-action-icon btn-icon-doc" title="Unduh / Buka Dokumen PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- MODAL PREVIEW GAMBAR -->
<div class="modal fade" id="modalPreviewDoc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <h6 class="modal-title fw-bold" id="previewTitle">Bukti Dokumentasi Kegiatan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="previewImg" src="" alt="Bukti" class="img-fluid rounded-3 shadow-sm" style="max-height: 520px;">
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
        $('#tabelRekapMentor').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: "<'row mb-3 align-items-center'<'col-md-7 d-flex align-items-center gap-2 flex-wrap'B l><'col-md-5 d-flex justify-content-md-end'f>><'row'<'col-12'tr>><'row mt-3 align-items-center'<'col-md-6'i><'col-md-6 d-flex justify-content-md-end'p>>",
            buttons: [
                {
                    extend: 'copy',
                    text: 'Copy',
                    className: 'btn-mybest-export',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }
                },
                {
                    extend: 'csv',
                    text: 'CSV',
                    className: 'btn-mybest-export',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn-mybest-export',
                    title: 'Rekap Logbook Siswa Bimbingan - Diskominfo Cilacap',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'btn-mybest-export',
                    title: 'Rekap Logbook Siswa Bimbingan - Diskominfo Cilacap',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn-mybest-export',
                    title: 'Rekap Logbook Siswa Bimbingan - Diskominfo Cilacap',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7] }
                }
            ],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                zeroRecords: "Tidak ada catatan logbook yang cocok",
                emptyTable: "Belum ada catatan logbook kegiatan siswa",
                paginate: {
                    previous: "Previous",
                    next: "Next"
                }
            },
            order: [[2, 'desc']], // Urutkan default berdasarkan tanggal kegiatan
            columnDefs: [
                { orderable: false, targets: [8] } // Kolom bukti tidak diurutkan
            ]
        });
    });

    function previewImage(url, title) {
        document.getElementById('previewImg').src = url;
        document.getElementById('previewTitle').textContent = title || 'Bukti Dokumentasi Kegiatan';
        const modal = new bootstrap.Modal(document.getElementById('modalPreviewDoc'));
        modal.show();
    }
</script>
@endsection
