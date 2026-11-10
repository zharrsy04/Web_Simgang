@extends('layouts.app')

@section('title', 'Absensi Harian')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-blue: #2563eb;
        --primary-blue-hover: #1d4ed8;
        --primary-gradient: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        --primary-purple: #5039eb;
        --success-green: #10b981;
        --warning-amber: #f59e0b;
        --danger-red: #ef4444;
        --card-bg: #ffffff;
        --page-bg: #f8fafc;
        --text-slate-900: #0f172a;
        --text-slate-700: #334155;
        --text-slate-500: #64748b;
        --text-slate-400: #94a3b8;
        --border-slate-200: #e2e8f0;
        --border-dashed: #cbd5e1;
    }

    body {
        font-family: 'Poppins', 'Plus Jakarta Sans', sans-serif;
        background-color: var(--page-bg);
        color: var(--text-slate-700);
    }

    .absensi-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px 16px 40px;
    }

    /* 1. Header Banner Gradient */
    .banner-header-gradient {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 32px 36px;
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
        font-size: 1.75rem;
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
        margin-bottom: 24px;
        max-width: 780px;
        line-height: 1.5;
    }

    .live-clock-card {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.28);
        border-radius: 14px;
        padding: 12px 24px;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .live-clock-time {
        font-size: 1.65rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: 0.8px;
        line-height: 1.2;
    }

    .live-clock-date {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.95);
        margin-top: 3px;
        font-weight: 500;
    }

    /* 2. Informasi Siswa & Pembimbing Card */
    .card-info-siswa {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid var(--border-slate-200);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        padding: 26px 30px;
        margin-bottom: 24px;
    }

    .card-info-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-slate-900);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-info-title i {
        color: #4f46e5;
        font-size: 1.25rem;
    }

    .info-grid-container {
        background: #f8fafc;
        border-radius: 14px;
        border: 1px solid #f1f5f9;
        padding: 24px 28px;
    }

    .info-cell-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #94a3b8;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .info-cell-val {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        word-break: break-word;
    }



    /* 3. Action / Status Kehadiran Card (Dashed Border) */
    .card-attendance-action {
        background: #ffffff;
        border: 2px dashed var(--border-dashed);
        border-radius: 20px;
        padding: 38px 28px;
        text-align: center;
        margin-bottom: 26px;
        position: relative;
    }

    .attendance-header-label {
        font-size: 0.84rem;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    /* Status Pills */
    .status-pill-lg {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px 26px;
        border-radius: 50px;
        font-size: 0.92rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-bottom: 14px;
        text-transform: uppercase;
    }

    .status-pill-belum {
        background-color: #f59e0b;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.28);
    }

    .status-pill-hadir {
        background-color: #10b981;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.28);
    }

    .status-pill-sakit {
        background-color: #06b6d4;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(6, 182, 212, 0.28);
    }

    .status-pill-izin {
        background-color: #8b5cf6;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(139, 92, 246, 0.28);
    }

    .attendance-helper-text {
        color: var(--text-slate-500);
        font-size: 0.92rem;
        margin-bottom: 22px;
    }

    /* Option Radio Pills (Hadir, Sakit, Izin) */
    .option-section-title {
        font-size: 0.82rem;
        font-weight: 800;
        color: #475569;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .status-options-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .btn-status-option {
        border-radius: 50px;
        padding: 9px 26px;
        font-size: 0.92rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1.5px solid var(--border-slate-200);
        background: #ffffff;
        color: #64748b;
        outline: none;
    }

    .btn-status-option:hover {
        border-color: #cbd5e1;
        color: #1e293b;
    }

    .btn-status-option.active-hadir {
        border: 2px solid #10b981;
        color: #059669;
        background: #ecfdf5;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);
    }

    .btn-status-option.active-sakit {
        border: 2px solid #06b6d4;
        color: #0891b2;
        background: #ecfeff;
        box-shadow: 0 2px 8px rgba(6, 182, 212, 0.15);
    }

    .btn-status-option.active-izin {
        border: 2px solid #8b5cf6;
        color: #7c3aed;
        background: #f5f3ff;
        box-shadow: 0 2px 8px rgba(139, 92, 246, 0.15);
    }

    /* GPS Status Box */
    .gps-status-box {
        border-radius: 12px;
        padding: 10px 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 0.88rem;
        font-weight: 600;
        margin-bottom: 14px;
        max-width: 620px;
        width: 100%;
        transition: all 0.3s ease;
        line-height: 1.4;
    }

    .gps-status-loading {
        background-color: #eff6ff;
        border: 1.5px solid #bfdbfe;
        color: #2563eb;
    }

    .gps-status-denied {
        background-color: #fef2f2;
        border: 1.5px solid #fecaca;
        color: #dc2626;
    }

    .gps-status-success {
        background-color: #f0fdf4;
        border: 1.5px solid #bbf7d0;
        color: #16a34a;
    }

    .gps-status-warning {
        background-color: #fff7ed;
        border: 1.5px solid #fed7aa;
        color: #ea580c;
    }

    .gps-retry-btn {
        background: none;
        border: none;
        color: inherit;
        font-weight: 700;
        text-decoration: underline;
        cursor: pointer;
        padding: 0;
        margin-left: 6px;
        font-size: 0.85rem;
    }

    /* Policy Notice */
    .policy-notice {
        font-size: 0.86rem;
        color: #64748b;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .policy-notice i {
        color: #2563eb;
    }

    /* Input Notes Pill */
    .notes-input-container {
        display: flex;
        justify-content: center;
        margin-bottom: 16px;
    }

    .form-control-notes {
        max-width: 580px;
        width: 100%;
        border-radius: 50px;
        border: 1.5px solid #cbd5e1;
        padding: 12px 24px;
        text-align: center;
        font-size: 0.92rem;
        font-family: inherit;
        background: #ffffff;
        color: #1e293b;
        outline: none;
        transition: all 0.2s ease;
    }

    .form-control-notes:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .form-control-notes::placeholder {
        color: #94a3b8;
    }

    /* Alert Late / On Time */
    .alert-late-box {
        background-color: #fffbeb;
        border: 1.5px solid #fef3c7;
        color: #92400e;
        border-radius: 12px;
        padding: 12px 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 0.88rem;
        margin-bottom: 22px;
        max-width: 680px;
        width: 100%;
    }

    .alert-ontime-box {
        background-color: #f0fdf4;
        border: 1.5px solid #bbf7d0;
        color: #166534;
        border-radius: 12px;
        padding: 12px 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 0.88rem;
        margin-bottom: 22px;
        max-width: 680px;
        width: 100%;
    }

    /* Action Buttons */
    .btn-absen-masuk {
        background: var(--primary-gradient);
        color: #ffffff;
        font-size: 1.05rem;
        font-weight: 700;
        padding: 14px 44px;
        border-radius: 14px;
        border: none;
        box-shadow: 0 8px 20px -3px rgba(37, 99, 235, 0.4);
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
        min-width: 200px;
    }

    .btn-absen-masuk:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -3px rgba(37, 99, 235, 0.5);
        color: #ffffff;
    }

    .btn-absen-masuk:disabled {
        background: #cbd5e1;
        color: #94a3b8;
        box-shadow: none;
        cursor: not-allowed;
    }

    /* Summary Details Box (When already checked in) */
    .attendance-summary-box {
        background: #f8fafc;
        border: 1px solid var(--border-slate-200);
        border-radius: 16px;
        padding: 24px 30px;
        max-width: 620px;
        margin: 0 auto 24px;
        text-align: left;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 16px;
    }

    .summary-item-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .summary-item-val {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
    }

    /* 4. Table Riwayat Card */
    .card-history {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid var(--border-slate-200);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        padding: 26px 30px;
    }

    .card-history-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-slate-900);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-history-title i {
        color: #2563eb;
    }

    .table-history {
        width: 100%;
        border-collapse: collapse;
    }

    .table-history th {
        background-color: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 18px;
        border-bottom: 1.5px solid var(--border-slate-200);
        white-space: nowrap;
    }

    .table-history td {
        padding: 16px 18px;
        border-bottom: 1px solid var(--border-slate-200);
        font-size: 0.9rem;
        color: #334155;
        vertical-align: middle;
    }

    .table-history tr:hover td {
        background-color: #fbfcfe;
    }

    .badge-status-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .badge-status-chip-hadir {
        background-color: #d1fae5;
        color: #065f46;
    }

    .badge-status-chip-sakit {
        background-color: #cffafe;
        color: #0e7490;
    }

    .badge-status-chip-izin {
        background-color: #ede9fe;
        color: #6d28d9;
    }

    .badge-status-chip-late {
        background-color: #fef3c7;
        color: #b45309;
        font-size: 0.72rem;
        padding: 2px 8px;
        border-radius: 12px;
        margin-left: 6px;
    }

    .badge-status-chip-ontime {
        background-color: #dcfce7;
        color: #15803d;
        font-size: 0.72rem;
        padding: 2px 8px;
        border-radius: 12px;
        margin-left: 6px;
    }

    /* Alerts */
    .alert-banner {
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.92rem;
    }

    .alert-banner-success {
        background-color: #ecfdf5;
        color: #065f46;
        border-left: 4px solid #10b981;
    }

    .alert-banner-warning {
        background-color: #fffbeb;
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }

    .alert-banner-danger {
        background-color: #fef2f2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    @media (max-width: 768px) {
        .banner-header-gradient {
            padding: 24px 20px;
        }
        .banner-title {
            font-size: 1.45rem;
        }
        .live-clock-time {
            font-size: 1.4rem;
        }
        .card-info-siswa, .card-attendance-action, .card-history {
            padding: 20px 16px;
        }
        .info-grid-container {
            padding: 18px 16px;
        }
    }
</style>
@endsection

@section('content')
<div class="absensi-wrapper">
    <!-- Header Banner Blue Gradient -->
    <div class="banner-header-gradient">
        <div class="banner-title">
            <i class="fas fa-calendar-check"></i>
            <span>Absensi Harian Siswa Magang</span>
        </div>
        <p class="banner-subtitle">
            Catat kehadiran harian Anda selama periode PKL di Dinas Komunikasi dan Informatika Kabupaten Cilacap.
        </p>
        <div class="live-clock-card">
            <div class="live-clock-time" id="realtimeClockTime">--:--:-- WIB</div>
            <div class="live-clock-date" id="realtimeClockDate">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, j F Y') }}</div>
        </div>
    </div>

    <!-- Alert Feedback -->
    @if(session('success'))
        <div class="alert-banner alert-banner-success">
            <i class="fas fa-check-circle fa-lg text-success"></i>
            <div><strong>Berhasil!</strong> {{ session('success') }}</div>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert-banner alert-banner-warning">
            <i class="fas fa-exclamation-triangle fa-lg text-warning"></i>
            <div><strong>Perhatian:</strong> {{ session('warning') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-banner alert-banner-danger">
            <i class="fas fa-times-circle fa-lg text-danger"></i>
            <div><strong>Gagal:</strong> {{ session('error') }}</div>
        </div>
    @endif

    @if(!$siswa)
        <div class="card-info-siswa text-center py-5">
            <i class="fas fa-user-clock text-warning" style="font-size: 3.5rem; margin-bottom: 16px;"></i>
            <h4 class="fw-bold text-dark">Data Magang Belum Ditemukan</h4>
            <p class="text-muted" style="max-width: 500px; margin: 0 auto 20px;">
                Profil magang Anda belum aktif atau belum diproses oleh pihak Sekretariat. Silakan pastikan pengajuan magang Anda telah disetujui terlebih dahulu.
            </p>
            <a href="{{ route('siswa.dashboard') }}" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    @else
        <!-- Card 1: Informasi Siswa -->
        <div class="card-info-siswa">
            <div class="card-info-title">
                <i class="fas fa-id-card"></i>
                <span>Informasi Siswa</span>
            </div>
            <div class="info-grid-container">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="info-cell-label">NAMA SISWA</div>
                        <div class="info-cell-val">{{ $siswa->nama_lengkap ?? Auth::user()->nama }}</div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="info-cell-label">NIM / NIS</div>
                        <div class="info-cell-val">{{ $siswa->nim_nis ?? Auth::user()->username }}</div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="info-cell-label">ASAL LEMBAGA / KAMPUS</div>
                        <div class="info-cell-val">{{ $siswa->asal_sekolah ?? '-' }}</div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="info-cell-label">BIDANG MAGANG</div>
                        <div class="info-cell-val">{{ $siswa->bidang ?? 'Bidang Informatika' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Status Kehadiran Hari Ini (Dashed Outline Box) -->
        <div class="card-attendance-action">
            <div class="attendance-header-label">
                STATUS KEHADIRAN HARI INI (<span id="headerTodayUpper">{{ strtoupper(\Carbon\Carbon::parse($today)->translatedFormat('l, j F Y')) }}</span>)
            </div>

            @if(!$attendanceToday)
                <!-- KONDISI 1: BELUM ABSEN (Sesuai Screenshot 2) -->
                <div class="status-pill-lg status-pill-belum">
                    <i class="fas fa-hourglass-half"></i> BELUM ABSEN
                </div>

                <p class="attendance-helper-text">
                    Silakan pilih status kehadiran dan lakukan absensi harian Anda.
                </p>

                <form action="{{ route('siswa.absensi.store') }}" method="POST" id="formAbsensi" onsubmit="return handleAbsenSubmit(event);">
                    @csrf
                    <!-- Hidden inputs -->
                    <input type="hidden" name="status" id="selectedStatusInput" value="Hadir">
                    <input type="hidden" name="latitude" id="latitudeInput">
                    <input type="hidden" name="longitude" id="longitudeInput">

                    <!-- Pilih Status Kehadiran: Hadir, Sakit, Izin -->
                    <div class="option-section-title">PILIH STATUS KEHADIRAN:</div>
                    <div class="status-options-wrapper">
                        <button type="button" class="btn-status-option active-hadir" id="btnOptHadir" onclick="changeStatus('Hadir')">
                            <i class="fas fa-check-circle"></i> Hadir
                        </button>
                        <button type="button" class="btn-status-option" id="btnOptSakit" onclick="changeStatus('Sakit')">
                            <i class="fas fa-plus-square"></i> Sakit
                        </button>
                        <button type="button" class="btn-status-option" id="btnOptIzin" onclick="changeStatus('Izin')">
                            <i class="fas fa-envelope"></i> Izin
                        </button>
                    </div>

                    <!-- GPS Status Pill / Banner -->
                    <div class="d-flex flex-column align-items-center justify-content-center mb-3">
                        <div class="gps-status-box gps-status-loading" id="gpsStatusBox">
                            <i class="fas fa-globe me-1"></i>
                            <i class="fas fa-spinner fa-spin me-1" id="gpsStateIcon"></i>
                            <span id="gpsStateText">Mendeteksi koordinat lokasi (GPS)...</span>
                        </div>
                        <div class="d-flex gap-2 justify-content-center flex-wrap mt-1" id="gpsActionButtons">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="gpsRetryBtn" style="font-size: 0.8rem;" onclick="triggerGpsDetection(true)">
                                <i class="fas fa-sync-alt me-1"></i> Coba Deteksi Ulang
                            </button>
                        </div>
                    </div>

                    <!-- Info Note Kantor Diskominfo Cilacap -->
                    <div class="policy-notice" id="policyNotice">
                        <i class="fas fa-info-circle"></i>
                        <span>Status <strong>Hadir</strong> dicatat berdasarkan radius kantor Dinas Komunikasi dan Informatika Kabupaten Cilacap (Maks. {{ $cfg['radius'] }} meter).</span>
                    </div>

                    <!-- Notes input (pill shaped) -->
                    <div class="notes-input-container">
                        <input type="text" name="notes" id="notesInput" class="form-control-notes" 
                            placeholder="Keterangan opsional (misal: Kegiatan/tugas hari ini atau alasan izin/sakit)">
                    </div>

                    <!-- Late or On-time Alert Banner -->
                    <div class="d-flex justify-content-center">
                        <div class="alert-late-box" id="lateWarningBox" style="{{ $isPastLateTolerance ? 'display: inline-flex;' : 'display: none;' }}">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <span><strong>Perhatian:</strong> Absen masuk saat ini tercatat <strong>TERLAMBAT</strong> (melebihi batas toleransi {{ substr($cfg['late_time'], 0, 5) }} WIB).</span>
                        </div>
                        <div class="alert-ontime-box" id="onTimeNoticeBox" style="{{ !$isPastLateTolerance ? 'display: inline-flex;' : 'display: none;' }}">
                            <i class="fas fa-clock me-1"></i>
                            <span>Absen masuk saat ini tercatat <strong>TEPAT WAKTU</strong> (Batas toleransi sampai {{ substr($cfg['late_time'], 0, 5) }} WIB).</span>
                        </div>
                    </div>

                    <!-- Submit Button Absen Masuk -->
                    <div class="mt-2">
                        <button type="submit" class="btn-absen-masuk" id="btnSubmitAbsen">
                            <i class="fas fa-sign-in-alt"></i> Absen Masuk
                        </button>
                    </div>
                </form>

            @else
                <!-- KONDISI 2: SUDAH ABSEN -->
                @if($attendanceToday->status === 'Hadir')
                    <div class="status-pill-lg status-pill-hadir">
                        <i class="fas fa-check-circle"></i> SUDAH ABSEN MASUK
                    </div>
                @elseif($attendanceToday->status === 'Sakit')
                    <div class="status-pill-lg status-pill-sakit">
                        <i class="fas fa-plus-square"></i> STATUS SAKIT
                    </div>
                @else
                    <div class="status-pill-lg status-pill-izin">
                        <i class="fas fa-envelope"></i> STATUS IZIN
                    </div>
                @endif

                <p class="attendance-helper-text mb-4">
                    Data absensi harian Anda telah tersimpan di sistem SIMGANG.
                </p>

                <!-- Summary Details Grid -->
                <div class="attendance-summary-box">
                    <div class="summary-grid">
                        <div>
                            <div class="summary-item-label">TANGGAL</div>
                            <div class="summary-item-val">{{ \Carbon\Carbon::parse($attendanceToday->date)->translatedFormat('d F Y') }}</div>
                        </div>
                        <div>
                            <div class="summary-item-label">JAM MASUK</div>
                            <div class="summary-item-val text-primary">
                                {{ $attendanceToday->time ? substr($attendanceToday->time, 0, 5) . ' WIB' : '-' }}
                                @if($attendanceToday->is_late)
                                    <span class="badge-status-chip-late">Terlambat</span>
                                @else
                                    <span class="badge-status-chip-ontime">Tepat Waktu</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="summary-item-label">LOKASI TERCATAT</div>
                            <div class="summary-item-val" style="font-size: 0.9rem;">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                {{ $attendanceToday->location ?? 'Diskominfo Cilacap' }}
                            </div>
                        </div>
                    </div>

                    @if(!empty($attendanceToday->notes))
                        <div class="mt-3 pt-3" style="border-top: 1px dashed var(--border-slate-200);">
                            <div class="summary-item-label">CATATAN / KETERANGAN:</div>
                            <div class="text-secondary small fst-italic mt-1">"{{ $attendanceToday->notes }}"</div>
                        </div>
                    @endif
                </div>

                <div class="mt-2">
                    <button type="button" class="btn-absen-masuk" disabled>
                        <i class="fas fa-check-double"></i> Absensi Hari Ini Selesai
                    </button>
                </div>
            @endif
        </div>

        <!-- Card 3: Riwayat Absensi Terakhir -->
        <div class="card-history">
            <div class="card-history-title">
                <i class="fas fa-history"></i>
                <span>Riwayat Absensi Terakhir</span>
            </div>

            @if($recentAttendances->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 text-secondary opacity-50"></i>
                    <p class="mb-0">Belum ada riwayat absensi yang tercatat pada akun Anda.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table-history">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Status</th>
                                <th>Lokasi / Jarak</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAttendances as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold">
                                        {{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d F Y') }}
                                    </td>
                                    <td>
                                        <i class="far fa-clock text-muted me-1"></i>
                                        <strong>{{ $item->time ? substr($item->time, 0, 5) . ' WIB' : '-' }}</strong>
                                        @if($item->status === 'Hadir')
                                            @if($item->is_late)
                                                <span class="badge-status-chip-late">Terlambat</span>
                                            @else
                                                <span class="badge-status-chip-ontime">Tepat Waktu</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == 'Hadir')
                                            <span class="badge-status-chip badge-status-chip-hadir">
                                                <i class="fas fa-check-circle"></i> Hadir
                                            </span>
                                        @elseif($item->status == 'Izin')
                                            <span class="badge-status-chip badge-status-chip-izin">
                                                <i class="fas fa-envelope-open"></i> Izin
                                            </span>
                                        @elseif($item->status == 'Sakit')
                                            <span class="badge-status-chip badge-status-chip-sakit">
                                                <i class="fas fa-plus-square"></i> Sakit
                                            </span>
                                        @else
                                            <span class="badge-status-chip badge-status-chip-late">
                                                <i class="fas fa-times-circle"></i> {{ $item->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">
                                        {{ $item->location ?? 'Diskominfo Cilacap' }}
                                    </td>
                                    <td class="small text-muted">
                                        {{ $item->notes ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Konfigurasi Kantor Diskominfo Kabupaten Cilacap
    const OFFICE_LAT = {{ $cfg['latitude'] }};
    const OFFICE_LNG = {{ $cfg['longitude'] }};
    const OFFICE_RADIUS = {{ $cfg['radius'] }}; // 100 Meter
    const STRICT_GPS = {{ $cfg['strict_gps'] ? 'true' : 'false' }};
    const LATE_TIME = "{{ $cfg['late_time'] }}"; // 07:40:00

    // State Variables
    let currentStatus = 'Hadir';
    let userLat = null;
    let userLng = null;
    let userDistance = null;
    let gpsStatus = 'loading'; // loading | success | out_of_range | denied | insecure_origin | unavailable | timeout | unsupported
    let isSubmitting = false;

    // 1. Digital Realtime Clock & Dynamic Late Checker
    function updateLiveClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeStr = `${hours}:${minutes}:${seconds}`;

        const clockEl = document.getElementById('realtimeClockTime');
        if (clockEl) {
            clockEl.textContent = `${timeStr} WIB`;
        }

        // Cek keterlambatan secara dinamis jika formulir absensi belum disubmit
        const lateBox = document.getElementById('lateWarningBox');
        const onTimeBox = document.getElementById('onTimeNoticeBox');
        if (lateBox && onTimeBox) {
            if (timeStr > LATE_TIME) {
                lateBox.style.display = 'inline-flex';
                onTimeBox.style.display = 'none';
            } else {
                lateBox.style.display = 'none';
                onTimeBox.style.display = 'inline-flex';
            }
        }
    }
    setInterval(updateLiveClock, 1000);
    updateLiveClock();

    // 2. Haversine Distance Formula (Meter)
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Radius Bumi dalam meter
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return Math.round(R * c);
    }

    // 3. UI Update untuk GPS Status Box
    function updateGpsUI() {
        const box = document.getElementById('gpsStatusBox');
        const icon = document.getElementById('gpsStateIcon');
        const text = document.getElementById('gpsStateText');
        const retryBtn = document.getElementById('gpsRetryBtn');
        const submitBtn = document.getElementById('btnSubmitAbsen');
        if (!box) return;

        // Reset classes
        box.className = 'gps-status-box';

        if (gpsStatus === 'loading') {
            box.classList.add('gps-status-loading');
            icon.className = 'fas fa-spinner fa-spin me-1';
            text.innerHTML = 'Mendeteksi koordinat lokasi (GPS) Anda...';
            if (submitBtn && currentStatus === 'Hadir') {
                submitBtn.disabled = STRICT_GPS;
            }
        } 
        else if (gpsStatus === 'insecure_origin') {
            box.classList.add('gps-status-warning');
            icon.className = 'fas fa-shield-alt text-warning me-1';
            text.innerHTML = 'Browser membatasi sensor GPS pada domain HTTP. Mohon izinkan lokasi pada setelan browser Anda.';
            if (submitBtn && currentStatus === 'Hadir') {
                submitBtn.disabled = STRICT_GPS;
            }
        }
        else if (gpsStatus === 'denied') {
            box.classList.add('gps-status-denied');
            icon.className = 'fas fa-times-circle text-danger me-1';
            text.innerHTML = 'Izin lokasi (GPS) belum aktif atau diblokir browser. ' + 
                (STRICT_GPS ? 'Izinkan lokasi di pengaturan browser untuk absen Hadir.' : 'Absensi tetap diizinkan.');
            if (submitBtn && currentStatus === 'Hadir') {
                submitBtn.disabled = STRICT_GPS;
            }
        } 
        else if (gpsStatus === 'unavailable' || gpsStatus === 'timeout' || gpsStatus === 'unsupported') {
            box.classList.add('gps-status-warning');
            icon.className = 'fas fa-exclamation-triangle text-warning me-1';
            text.innerHTML = 'Sinyal lokasi perangkat tidak terdeteksi. ' + 
                (STRICT_GPS ? 'Pastikan fitur lokasi Windows / HP aktif.' : 'Absensi tetap dapat dilakukan.');
            if (submitBtn && currentStatus === 'Hadir') {
                submitBtn.disabled = STRICT_GPS;
            }
        }
        else if (gpsStatus === 'out_of_range') {
            box.classList.add('gps-status-warning');
            icon.className = 'fas fa-map-marker-alt text-warning me-1';
            if (STRICT_GPS) {
                text.innerHTML = `Di luar radius: Anda berada <strong>${userDistance} meter</strong> dari kantor Diskominfo Cilacap (Maks. ${OFFICE_RADIUS}m).`;
            } else {
                text.innerHTML = `Lokasi terdeteksi: <strong>${userDistance} meter</strong> dari kantor Diskominfo Cilacap (Di luar radius, absensi tetap diizinkan).`;
            }
            if (submitBtn && currentStatus === 'Hadir') {
                submitBtn.disabled = STRICT_GPS;
            }
        } 
        else if (gpsStatus === 'success') {
            box.classList.add('gps-status-success');
            icon.className = 'fas fa-check-circle text-success me-1';
            text.innerHTML = `Lokasi terdeteksi: <strong>${userDistance} meter</strong> dari kantor Diskominfo Cilacap (Dalam area kantor).`;
            if (submitBtn) submitBtn.disabled = false;
        }

        // Jika status Sakit atau Izin, tombol absen selalu aktif (karena tidak diwajibkan di kantor)
        if (submitBtn && (currentStatus === 'Sakit' || currentStatus === 'Izin')) {
            submitBtn.disabled = false;
        }
    }

    // 5. Deteksi Geolocation Browser dengan Fallback Bertingkat
    function triggerGpsDetection(manual = false) {
        const isSecure = window.isSecureContext || 
                         window.location.hostname === 'localhost' || 
                         window.location.hostname === '127.0.0.1';

        if (!navigator.geolocation) {
            gpsStatus = 'unsupported';
            updateGpsUI();
            return;
        }

        gpsStatus = 'loading';
        updateGpsUI();

        // Coba dengan low-accuracy terlebih dahulu (lebih cepat & tidak timeout pada laptop/PC tanpa chip GPS hardware)
        const lowAccuracyOptions = {
            enableHighAccuracy: false,
            timeout: 8000,
            maximumAge: 300000 // Izinkan cache posisi hingga 5 menit
        };

        const highAccuracyOptions = {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 60000
        };

        function onPositionSuccess(position) {
            userLat = position.coords.latitude;
            userLng = position.coords.longitude;

            const latInput = document.getElementById('latitudeInput');
            const lngInput = document.getElementById('longitudeInput');
            if (latInput) latInput.value = userLat;
            if (lngInput) lngInput.value = userLng;

            // Hitung jarak ke Diskominfo Cilacap
            userDistance = calculateDistance(userLat, userLng, OFFICE_LAT, OFFICE_LNG);

            if (userDistance <= OFFICE_RADIUS) {
                gpsStatus = 'success';
            } else {
                gpsStatus = 'out_of_range';
            }

            updateGpsUI();
        }

        function onPositionError(error) {
            console.warn('Geolocation error:', error.code, error.message);

            if (error.code === 1) { // PERMISSION_DENIED
                if (!isSecure) {
                    gpsStatus = 'insecure_origin';
                } else {
                    gpsStatus = 'denied';
                }
                updateGpsUI();
            } else if (error.code === 2 || error.code === 3) {
                // Jika percobaan pertama gagal / timeout, coba sekali lagi dengan high accuracy
                if (!manual) {
                    navigator.geolocation.getCurrentPosition(
                        onPositionSuccess,
                        function(err2) {
                            gpsStatus = (err2.code === 3) ? 'timeout' : 'unavailable';
                            updateGpsUI();
                        },
                        highAccuracyOptions
                    );
                } else {
                    gpsStatus = (error.code === 3) ? 'timeout' : 'unavailable';
                    updateGpsUI();
                }
            } else {
                gpsStatus = 'denied';
                updateGpsUI();
            }
        }

        navigator.geolocation.getCurrentPosition(
            onPositionSuccess,
            onPositionError,
            lowAccuracyOptions
        );
    }

    // 6. Ganti Status Kehadiran (Hadir / Sakit / Izin)
    function changeStatus(status) {
        currentStatus = status;
        const statusInput = document.getElementById('selectedStatusInput');
        if (statusInput) statusInput.value = status;

        const btnHadir = document.getElementById('btnOptHadir');
        const btnSakit = document.getElementById('btnOptSakit');
        const btnIzin = document.getElementById('btnOptIzin');
        const notesInput = document.getElementById('notesInput');
        const policyNotice = document.getElementById('policyNotice');

        // Reset classes
        if (btnHadir) btnHadir.className = 'btn-status-option';
        if (btnSakit) btnSakit.className = 'btn-status-option';
        if (btnIzin) btnIzin.className = 'btn-status-option';

        if (status === 'Hadir') {
            if (btnHadir) btnHadir.classList.add('active-hadir');
            if (policyNotice) policyNotice.style.display = 'flex';
            if (notesInput) notesInput.placeholder = 'Keterangan opsional (misal: Kegiatan/tugas hari ini)';
        } else if (status === 'Sakit') {
            if (btnSakit) btnSakit.classList.add('active-sakit');
            if (policyNotice) policyNotice.style.display = 'none';
            if (notesInput) notesInput.placeholder = 'Tuliskan keterangan sakit atau gejala Anda...';
        } else if (status === 'Izin') {
            if (btnIzin) btnIzin.classList.add('active-izin');
            if (policyNotice) policyNotice.style.display = 'none';
            if (notesInput) notesInput.placeholder = 'Tuliskan alasan izin Anda...';
        }

        updateGpsUI();
    }

    // 7. Handle Form Submit Absen Masuk
    function handleAbsenSubmit(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }

        // Validasi GPS jika status Hadir
        if (currentStatus === 'Hadir') {
            if (STRICT_GPS) {
                if (!userLat || !userLng || gpsStatus === 'denied' || gpsStatus === 'insecure_origin') {
                    e.preventDefault();
                    alert('Akses lokasi (GPS) wajib diizinkan untuk status Hadir. Pastikan izin lokasi aktif pada pengaturan browser perangkat Anda.');
                    triggerGpsDetection(true);
                    return false;
                }

                if (gpsStatus === 'out_of_range') {
                    e.preventDefault();
                    alert(`Gagal: Anda berada sejauh ${userDistance} meter dari kantor Diskominfo Cilacap. Status Hadir wajib berada di area kantor (Maks. ${OFFICE_RADIUS} meter).`);
                    return false;
                }
            } else {
                // Non-strict GPS mode: konfirmasi jika GPS belum terdeteksi
                if (!userLat || !userLng) {
                    const confirmNoGps = confirm('Koordinat GPS belum terdeteksi secara otomatis.\n\nApakah Anda ingin tetap melanjutkan absensi Hadir?');
                    if (!confirmNoGps) {
                        e.preventDefault();
                        return false;
                    }
                }
            }
        } else {
            // Jika Sakit atau Izin, keterangan disarankan
            const notesVal = document.getElementById('notesInput')?.value?.trim();
            if (!notesVal) {
                const proceed = confirm(`Anda memilih status ${currentStatus} tanpa mengisi keterangan. Apakah Anda yakin ingin melanjutkan?`);
                if (!proceed) {
                    e.preventDefault();
                    document.getElementById('notesInput')?.focus();
                    return false;
                }
            }
        }

        const confirmMsg = `Konfirmasi Absensi:\nStatus: ${currentStatus}\nApakah Anda yakin ingin menyimpan absensi sekarang?`;
        if (!confirm(confirmMsg)) {
            e.preventDefault();
            return false;
        }

        // Tampilkan loading button
        isSubmitting = true;
        const btn = document.getElementById('btnSubmitAbsen');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses Absen...';
        }

        return true;
    }

    // Jalankan deteksi GPS saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        triggerGpsDetection();
    });
</script>
@endsection
