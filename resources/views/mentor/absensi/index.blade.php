@extends('layouts.app')

@section('title', 'Absensi Siswa Bimbingan')

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

    .mentor-absensi-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 25px 15px;
    }

    /* Header Banner */
    .absensi-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 16px;
        padding: 30px;
        color: white;
        margin-bottom: 25px;
        box-shadow: 0 10px 20px -5px rgba(80, 57, 235, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .absensi-header-text h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 6px;
        color: #ffffff;
    }

    .absensi-header-text p {
        font-size: 0.95rem;
        opacity: 0.9;
        margin-bottom: 0;
        color: rgba(255, 255, 255, 0.85);
    }

    /* Stat Cards */
    .stat-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: var(--card-bg);
        border-radius: 14px;
        padding: 20px 24px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon-blue { background: #e0e7ff; color: var(--primary); }
    .stat-icon-green { background: #d1fae5; color: var(--success); }
    .stat-icon-amber { background: #fef3c7; color: var(--warning); }

    .stat-info h4 {
        font-size: 1.7rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .stat-info span {
        font-size: 0.85rem;
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
        margin-bottom: 25px;
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
        min-width: 180px;
    }

    .filter-item label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .filter-item input,
    .filter-item select {
        padding: 9px 14px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.9rem;
        font-family: inherit;
        background: #fff;
        outline: none;
        transition: border-color 0.2s;
    }

    .filter-item input:focus,
    .filter-item select:focus {
        border-color: var(--primary);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
    }

    .btn-filter {
        background: var(--primary);
        color: white;
        padding: 9px 20px;
        border-radius: 8px;
        border: none;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-filter:hover {
        background: var(--primary-dark);
        color: white;
    }

    .btn-reset {
        background: #f1f5f9;
        color: var(--text-secondary);
        padding: 9px 18px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-reset:hover {
        background: #e2e8f0;
        color: var(--text-primary);
    }

    /* Table Styling */
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

    .student-title {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .student-sub {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .badge-status {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-status-hadir {
        background-color: #d1fae5;
        color: #065f46;
    }

    .badge-status-belum {
        background-color: #fef3c7;
        color: #92400e;
    }

    .badge-status-izin {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .btn-history {
        background: #f8fafc;
        color: var(--primary);
        border: 1px solid var(--primary-light);
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-history:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
</style>
@endsection

@section('content')
<div class="mentor-absensi-container">
    <!-- Header -->
    <div class="absensi-header">
        <div class="absensi-header-text">
            <h2><i class="fas fa-clipboard-user me-2"></i>Absensi Siswa Bimbingan</h2>
            <p>Pantau kehadiran harian dan riwayat absensi siswa yang Anda bimbing.</p>
        </div>
        <div>
            <span class="badge bg-light text-dark px-3 py-2 fw-semibold" style="font-size: 0.9rem;">
                <i class="far fa-calendar-alt me-1 text-primary"></i> 
                {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $totalBimbingan }}</h4>
                <span>Total Siswa Bimbingan</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $totalHadir }}</h4>
                <span>Hadir Hari Ini</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-amber">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="stat-info">
                <h4>{{ $totalBelumAbsen }}</h4>
                <span>Belum Absen</span>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card-custom">
        <!-- Filter Form -->
        <div class="filter-wrapper">
            <form action="{{ route('mentor.absensi.index') }}" method="GET" class="filter-form">
                <div class="filter-item">
                    <label><i class="far fa-calendar me-1"></i>Pilih Tanggal:</label>
                    <input type="date" name="tanggal" value="{{ $selectedDate }}">
                </div>

                <div class="filter-item">
                    <label><i class="fas fa-search me-1"></i>Cari Siswa:</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nama / NIM / Sekolah...">
                </div>

                <div class="filter-item">
                    <label><i class="fas fa-filter me-1"></i>Status Kehadiran:</label>
                    <select name="status">
                        <option value="all">Semua Status</option>
                        <option value="Hadir" {{ $statusFilter == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Belum Absen" {{ $statusFilter == 'Belum Absen' ? 'selected' : '' }}>Belum Absen</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i> Terapkan
                    </button>
                    @if($search || $statusFilter || $selectedDate !== now()->format('Y-m-d'))
                        <a href="{{ route('mentor.absensi.index') }}" class="btn-reset">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Siswa</th>
                        <th>Tanggal</th>
                        <th>Waktu Masuk</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th style="width: 140px; text-align: center;">Riwayat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mappedStudents as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="student-title">{{ $student->nama_lengkap }}</div>
                                <div class="student-sub">
                                    <span>NIM/NIS: {{ $student->nim_nis ?? '-' }}</span> | 
                                    <span>{{ $student->asal_sekolah ?? '-' }}</span>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }}</td>
                            <td>
                                @if($student->attendance_time !== '-')
                                    <i class="far fa-clock me-1 text-muted"></i>
                                    <strong>{{ \Carbon\Carbon::parse($student->attendance_time)->format('H:i') }} WIB</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($student->attendance_status == 'Hadir')
                                    <span class="badge-status badge-status-hadir">
                                        <i class="fas fa-check-circle me-1"></i> Hadir
                                    </span>
                                @elseif($student->attendance_status == 'Izin')
                                    <span class="badge-status badge-status-izin">
                                        <i class="fas fa-envelope-open me-1"></i> Izin
                                    </span>
                                @else
                                    <span class="badge-status badge-status-belum">
                                        <i class="fas fa-hourglass-start me-1"></i> Belum Absen
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $student->attendance_notes ?? '-' }}
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('mentor.absensi.riwayat', $student->id_sertifikat) }}" class="btn-history">
                                    <i class="fas fa-history"></i> Riwayat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-users-slash fa-3x mb-3 text-secondary d-block"></i>
                                Tidak ada data siswa bimbingan yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
