@extends('layouts.app')

@section('title', 'Riwayat Absensi Siswa')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #5039eb;
        --primary-light: #8175f7;
        --primary-dark: #3730a3;
        --secondary: #14b8a6;
        --warning: #f59e0b;
        --danger: #ef4444;
        --success: #10b981;
        --info: #3b82f6;
        --background: #f8fafc;
        --card-bg: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --border-color: #e2e8f0;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--background);
        color: var(--text-primary);
    }

    .riwayat-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 25px 15px;
    }

    /* Header Banner */
    .riwayat-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 16px;
        padding: 28px;
        color: white;
        margin-bottom: 25px;
        box-shadow: 0 10px 20px -5px rgba(80, 57, 235, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .riwayat-header h2 {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 4px;
        color: #ffffff;
    }

    .riwayat-header p {
        font-size: 0.95rem;
        opacity: 0.9;
        margin: 0;
        color: rgba(255, 255, 255, 0.85);
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 9px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        backdrop-filter: blur(5px);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    /* Student Info Box */
    .student-info-card {
        background: var(--card-bg);
        border-radius: 14px;
        padding: 20px 24px;
        border: 1px solid var(--border-color);
        margin-bottom: 25px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .info-block span {
        font-size: 0.8rem;
        color: var(--text-secondary);
        display: block;
        margin-bottom: 3px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .info-block strong {
        font-size: 1rem;
        color: var(--text-primary);
    }

    /* Stat Counters */
    .stat-counters {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 25px;
    }

    .stat-counter-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .counter-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .counter-icon-green { background: #d1fae5; color: var(--success); }
    .counter-icon-blue { background: #dbeafe; color: var(--info); }
    .counter-icon-amber { background: #fef3c7; color: var(--warning); }
    .counter-icon-red { background: #fee2e2; color: var(--danger); }

    .counter-text h4 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .counter-text span {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* Card Box */
    .card-custom {
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        border: 1px solid var(--border-color);
        padding: 24px;
    }

    /* Filter Bar */
    .filter-wrapper {
        background: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 20px;
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        align-items: flex-end;
    }

    .filter-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
        min-width: 160px;
    }

    .filter-item label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .filter-item input,
    .filter-item select {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.88rem;
        font-family: inherit;
        background: #fff;
        outline: none;
    }

    .btn-filter {
        background: var(--primary);
        color: white;
        padding: 8px 18px;
        border-radius: 8px;
        border: none;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-reset {
        background: #f1f5f9;
        color: var(--text-secondary);
        padding: 8px 14px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        font-size: 0.88rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Table */
    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom th {
        background-color: #f8fafc;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .table-custom td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.9rem;
        color: var(--text-primary);
        vertical-align: middle;
    }

    .table-custom tr:hover td {
        background-color: #fbfcfe;
    }

    .badge-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .badge-status-hadir { background-color: #d1fae5; color: #065f46; }
    .badge-status-izin { background-color: #dbeafe; color: #1e40af; }
    .badge-status-sakit { background-color: #fef3c7; color: #92400e; }
    .badge-status-alpa { background-color: #fee2e2; color: #991b1b; }
</style>
@endsection

@section('content')
<div class="riwayat-container">
    <!-- Header -->
    <div class="riwayat-header">
        <div>
            <h2><i class="fas fa-history me-2"></i>Riwayat Absensi Siswa</h2>
            <p>Rekam jejak kehadiran lengkap selama masa magang/PKL.</p>
        </div>
        <div>
            <a href="{{ route('mentor.absensi.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Absensi
            </a>
        </div>
    </div>

    <!-- Student Info -->
    <div class="student-info-card">
        <div class="info-block">
            <span>Nama Siswa</span>
            <strong>{{ $siswa->nama_lengkap }}</strong>
        </div>
        <div class="info-block">
            <span>NIM / NIS</span>
            <strong>{{ $siswa->nim_nis ?? '-' }}</strong>
        </div>
        <div class="info-block">
            <span>Asal Sekolah / PT</span>
            <strong>{{ $siswa->asal_sekolah ?? '-' }}</strong>
        </div>
        <div class="info-block">
            <span>Bidang Magang</span>
            <strong>{{ $siswa->bidang ?? '-' }}</strong>
        </div>
        <div class="info-block">
            <span>Periode Magang</span>
            <strong>
                {{ $siswa->tgl_mulai ? \Carbon\Carbon::parse($siswa->tgl_mulai)->format('d/m/Y') : '-' }} s/d 
                {{ $siswa->tgl_selesai ? \Carbon\Carbon::parse($siswa->tgl_selesai)->format('d/m/Y') : '-' }}
            </strong>
        </div>
    </div>

    <!-- Summary Counters -->
    <div class="stat-counters">
        <div class="stat-counter-card">
            <div class="counter-icon counter-icon-green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="counter-text">
                <h4>{{ $totalHadir }}</h4>
                <span>Total Hadir</span>
            </div>
        </div>

        <div class="stat-counter-card">
            <div class="counter-icon counter-icon-blue">
                <i class="fas fa-envelope-open"></i>
            </div>
            <div class="counter-text">
                <h4>{{ $totalIzin }}</h4>
                <span>Izin</span>
            </div>
        </div>

        <div class="stat-counter-card">
            <div class="counter-icon counter-icon-amber">
                <i class="fas fa-first-aid"></i>
            </div>
            <div class="counter-text">
                <h4>{{ $totalSakit }}</h4>
                <span>Sakit</span>
            </div>
        </div>

        <div class="stat-counter-card">
            <div class="counter-icon counter-icon-red">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="counter-text">
                <h4>{{ $totalAlpa }}</h4>
                <span>Alpa</span>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-custom">
        <!-- Filter Form -->
        <div class="filter-wrapper">
            <form action="{{ route('mentor.absensi.riwayat', $siswa->id_sertifikat) }}" method="GET" class="filter-form">
                <div class="filter-item">
                    <label>Dari Tanggal:</label>
                    <input type="date" name="start_date" value="{{ $startDate }}">
                </div>

                <div class="filter-item">
                    <label>Sampai Tanggal:</label>
                    <input type="date" name="end_date" value="{{ $endDate }}">
                </div>

                <div class="filter-item">
                    <label>Status:</label>
                    <select name="status">
                        <option value="all">Semua Status</option>
                        <option value="Hadir" {{ $status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Izin" {{ $status == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Sakit" {{ $status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Alpa" {{ $status == 'Alpa' ? 'selected' : '' }}>Alpa</option>
                    </select>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if($startDate || $endDate || ($status && $status !== 'all'))
                        <a href="{{ route('mentor.absensi.riwayat', $siswa->id_sertifikat) }}" class="btn-reset">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- History Table -->
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Hari & Tanggal</th>
                        <th>Waktu Masuk</th>
                        <th>Status</th>
                        <th>Keterangan / Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $index => $item)
                        <tr>
                            <td>{{ $attendances->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d F Y') }}</strong>
                            </td>
                            <td>
                                <i class="far fa-clock me-1 text-muted"></i>
                                {{ $item->time ? \Carbon\Carbon::parse($item->time)->format('H:i') . ' WIB' : '-' }}
                            </td>
                            <td>
                                @if($item->status == 'Hadir')
                                    <span class="badge-status badge-status-hadir">Hadir</span>
                                @elseif($item->status == 'Izin')
                                    <span class="badge-status badge-status-izin">Izin</span>
                                @elseif($item->status == 'Sakit')
                                    <span class="badge-status badge-status-sakit">Sakit</span>
                                @else
                                    <span class="badge-status badge-status-alpa">Alpa</span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $item->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-calendar-times fa-3x mb-3 text-secondary d-block"></i>
                                Belum ada catatan absensi untuk filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($attendances->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
