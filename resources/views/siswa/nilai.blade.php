@extends('layouts.app')

@section('title', 'Nilai Sertifikat - Daftar Nilai Praktik Kerja Lapangan')

@section('styles')
    <title>Nilai Sertifikat - Daftar Nilai Praktik Kerja Lapangan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #5039eb;
            --primary-light: #8175f7;
            --primary-dark: #3730a3;
            --secondary: #14b8a6;
            --warning: #f59e0b;
            --danger: #e64942;
            --success: #10b981;
            --info: #3b82f6;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--background);
            font-family: 'Poppins', sans-serif;
            color: var(--text-primary);
            line-height: 1.6;
        }

        .nilai-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 25px;
        }

        /* Header Section - Matching Dashboard Style */
        .nilai-header {
            background: linear-gradient(135deg, #5b47eb 0%, #4338ca 100%);
            border-radius: 16px;
            padding: 32px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
        }

        .nilai-header::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            z-index: 1;
        }

        .header-content {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 24px;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-icon {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-text h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .header-text p {
            font-size: 16px;
            opacity: 0.85;
            font-weight: 400;
        }

        /* Action Buttons - Modern Style */
        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-modern {
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            color: white;
            backdrop-filter: blur(10px);
            min-width: 140px;
            justify-content: center;
        }

        .btn-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: white;
        }

        .btn-primary-modern {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-success-modern {
            background: rgba(16, 185, 129, 0.9);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .btn-download-modern {
            background: rgba(245, 158, 11, 0.9);
        }

        /* Cards - Consistent with Dashboard */
        .card {
            background: var(--card-bg);
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-header {
            display: flex;
            align-items: center;
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .card-icon {
            width: 48px;
            height: 48px;
            background: var(--secondary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-content {
            padding: 25px;
        }

        /* Student Info Grid - Updated to match image */
        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .info-item {
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid var(--secondary);
            transition: all 0.3s ease;
            text-align: left;
        }

        .info-item:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
        }

        .info-label {
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 12px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 16px;
            word-wrap: break-word;
        }

        /* Stats Cards Style - Updated layout */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid;
            transition: all 0.3s ease;
            text-align: left;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.08);
        }

        .stat-card.primary {
            border-left-color: var(--primary);
        }

        .stat-card.success {
            border-left-color: var(--success);
        }

        .stat-card.info {
            border-left-color: var(--info);
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--text-primary);
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Modern Table - Redesigned to match reference */
        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            box-shadow: 0 1px 4px rgba(0,0,0,0.07);
        }

        .modern-table {
            width: 100% !important;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            background: white !important;
            margin: 0 !important;
        }

        /* Header - Navy dark */
        .modern-table thead tr {
            background: none !important;
        }

        .modern-table thead th {
            background-color: #1e3a5f !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            padding: 15px 18px !important;
            text-align: center !important;
            font-size: 13px !important;
            letter-spacing: 0.3px !important;
            border: none !important;
            border-bottom: none !important;
        }

        .modern-table thead th:first-child {
            width: 60px;
        }

        .modern-table thead th:nth-child(2) {
            text-align: left !important;
        }

        .modern-table thead th:nth-child(3) {
            width: 100px;
        }

        .modern-table thead th:nth-child(4) {
            width: 150px;
        }

        /* Body rows - clean white */
        .modern-table tbody tr {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e9ecef !important;
            transition: background 0.15s ease;
        }

        .modern-table tbody tr:hover:not(.summary-row) {
            background-color: #f0f4ff !important;
        }

        .modern-table tbody tr:last-child {
            border-bottom: none !important;
        }

        .modern-table tbody td {
            padding: 14px 18px !important;
            vertical-align: middle !important;
            font-size: 14px !important;
            color: #374151 !important;
            border: none !important;
            border-bottom: 1px solid #e9ecef !important;
        }

        .modern-table tbody td:first-child {
            text-align: center !important;
            font-weight: 500 !important;
        }

        .modern-table tbody td:nth-child(2) {
            text-align: left !important;
            font-weight: 400 !important;
        }

        .modern-table tbody td:nth-child(3) {
            text-align: center !important;
            font-weight: 500 !important;
        }

        .modern-table tbody td:last-child {
            text-align: center !important;
        }

        /* Summary Rows - Cream/beige background like reference */
        .modern-table tbody tr.summary-row {
            background-color: #f5f0e8 !important;
            border-bottom: 1px solid #e5dcc8 !important;
        }

        .modern-table tbody tr.summary-row:hover {
            background-color: #f5f0e8 !important;
        }

        .modern-table tbody tr.summary-row td {
            background-color: #f5f0e8 !important;
            border-bottom: 1px solid #e5dcc8 !important;
        }

        .summary-label {
            color: #c9a227 !important;
            font-weight: 700 !important;
            font-size: 14px !important;
        }

        .summary-value {
            color: #1e3a5f !important;
            font-weight: 700 !important;
            font-size: 15px !important;
        }

        /* Grade Badge */
        .grade-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .grade-a {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .grade-b {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
        }

        .grade-c {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }

        .grade-d {
            background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
            color: #991b1b;
        }

        /* Grade Legend */
        .legend-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }

        .legend-item:hover {
            background: #f1f5f9;
            transform: translateX(4px);
        }

        .legend-range {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
        }

        .legend-grade {
            font-weight: 700;
            color: var(--primary);
            font-size: 14px;
        }

        /* Print Styles - FIXED VERSION */
        @media print {
            .nilai-container {
                max-width: none;
                padding: 0;
            }
            
            .action-buttons,
            .btn-modern {
                display: none !important;
            }
            
            .nilai-header {
                background: var(--primary) !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .modern-table thead th {
                background: #f8fafc !important;
                color: #1e293b !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .card-header,
            .summary-row {
                background: #f8fafc !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .grade-badge {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .student-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .student-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .nilai-container {
                padding: 15px;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .action-buttons {
                width: 100%;
                justify-content: center;
            }
            
            .btn-modern {
                flex: 1;
                justify-content: center;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .student-info-grid {
                grid-template-columns: 1fr;
            }
            
            .legend-grid {
                grid-template-columns: 1fr;
            }
            
            .modern-table {
                font-size: 12px;
            }
            
            .modern-table thead th,
            .modern-table tbody td {
                padding: 12px 8px;
            }
        }

        /* Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
    </style>
@endsection

@section('content')
<div class="nilai-container">
    @if(!$sertifikat || $penilaians->isEmpty())
        <!-- Empty State -->
        <div class="card fade-in" style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 64px; color: #cbd5e0; margin-bottom: 20px;">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h2 style="color: #4a5568; margin-bottom: 12px;">Belum Ada Penilaian</h2>
            <p style="color: #718096; margin-bottom: 24px;">
                Nilai Anda belum tersedia. Mentor akan memberikan penilaian setelah Anda menyelesaikan program magang.
            </p>
            <a href="{{ route('siswa.dashboard') }}" class="btn-modern btn-primary-modern">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    @else
    <!-- Header -->
    <div class="nilai-header fade-in">
        <div class="header-content">
            <div class="header-info">
                <div class="header-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="header-text">
                    <h1>Nilai Sertifikat</h1>
                    <p>Hasil evaluasi dan penilaian pelaksanaan magang</p>
                </div>
            </div>
            <div class="action-buttons">
                <a href="{{ route('sertifikat.cetak-gabungan-v5', ['id_sertifikat' => $sertifikat->id_sertifikat]) }}" class="btn-modern btn-success-modern">
                    <i class="fas fa-print"></i>Unduh Sertifikat / Nilai
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid fade-in">
        <div class="stat-card primary">
            <div class="stat-header">
                <div class="stat-icon" style="background: var(--primary)">
                    <i class="fas fa-chart-bar"></i>
                </div>
            </div>
            <div class="stat-value">{{ number_format($penilaians->avg('nilai'), 1) }}</div>
            <div class="stat-label">Rata-rata Nilai</div>
        </div>
        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-icon" style="background: var(--success)">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
            <div class="stat-value">
                @php
                    $avg = $penilaians->avg('nilai');
                    if ($avg >= 90) {
                        echo 'A';
                    } elseif ($avg >= 75) {
                        echo 'B';
                    } elseif ($avg >= 60) {
                        echo 'C';
                    } else {
                        echo 'D';
                    }
                @endphp
            </div>
            <div class="stat-label">Grade Akhir</div>
        </div>
        <div class="stat-card info">
            <div class="stat-header">
                <div class="stat-icon" style="background: var(--info)">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <div class="stat-value">{{ $penilaians->count() }}</div>
            <div class="stat-label">Aspek Dinilai</div>
        </div>
    </div>

    <!-- Student Information -->
    <div class="card fade-in">
        <div class="card-header">
            <div class="card-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h3 class="card-title">Informasi Siswa</h3>
        </div>
        <div class="card-content">
            <div class="student-info-grid">
                <div class="info-item">
                    <div class="info-label">Nama Siswa/Mahasiswa</div>
                    <div class="info-value">{{ $sertifikat->nama_lengkap }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">NIS/NIM</div>
                    <div class="info-value">{{ $sertifikat->nim_nis }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Jurusan</div>
                    <div class="info-value">{{ $sertifikat->jurusan }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Sekolah/Perguruan Tinggi</div>
                    <div class="info-value">{{ $sertifikat->asal_sekolah }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nilai Table -->
    <div class="card fade-in">
        <div class="card-header">
            <div class="card-icon" style="background: var(--success)">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="card-title">Detail Penilaian</h3>
        </div>
        <div class="card-content">
            <div style="overflow-x:auto; border-radius:10px; border:2px solid #c9a227; box-shadow:0 1px 4px rgba(0,0,0,0.07);">
                <table style="width:100%; border-collapse:collapse; border-spacing:0; background:white; margin:0;">
                    <thead>
                        <tr style="background:none;">
                            <th style="background-color:#1e3a5f !important; color:#ffffff !important; font-weight:600; padding:15px 18px; text-align:center; font-size:13px; border:2px solid #c9a227; width:60px;">No</th>
                            <th style="background-color:#1e3a5f !important; color:#ffffff !important; font-weight:600; padding:15px 18px; text-align:left; font-size:13px; border:2px solid #c9a227;">Aspek yang dinilai</th>
                            <th style="background-color:#1e3a5f !important; color:#ffffff !important; font-weight:600; padding:15px 18px; text-align:center; font-size:13px; border:2px solid #c9a227; width:110px;">Nilai</th>
                            <th style="background-color:#1e3a5f !important; color:#ffffff !important; font-weight:600; padding:15px 18px; text-align:center; font-size:13px; border:2px solid #c9a227; width:130px;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penilaians as $index => $penilaian)
                            <tr style="background-color:#ffffff;">
                                <td style="padding:14px 18px; text-align:center; font-size:14px; color:#374151; border:2px solid #c9a227;">{{ $loop->iteration }}</td>
                                <td style="padding:14px 18px; text-align:left; font-size:14px; color:#374151; border:2px solid #c9a227;">{{ $penilaian->aspek }}</td>
                                <td style="padding:14px 18px; text-align:center; font-size:14px; color:#374151; border:2px solid #c9a227;">{{ $penilaian->nilai }}</td>
                                <td style="padding:14px 18px; text-align:center; font-size:14px; color:#374151; border:2px solid #c9a227;">{{ $penilaian->ket ?? '-' }}</td>
                            </tr>
                        @endforeach
                        {{-- Rata-rata row --}}
                        <tr style="background-color:#f5f0e8;">
                            <td colspan="2" style="padding:14px 30px 14px 18px; text-align:right; background-color:#f5f0e8; border:2px solid #c9a227;">
                                <span style="color:#1e3a5f !important; font-weight:700; font-size:14px;">Rata-rata</span>
                            </td>
                            <td colspan="2" style="padding:14px 18px; background-color:#f5f0e8; border:2px solid #c9a227; color:#1e3a5f !important;">
                                <span style="color:#1e3a5f !important; font-weight:700; font-size:15px;">{{ number_format($penilaians->avg('nilai'), 2) }}</span>
                            </td>
                        </tr>
                        {{-- Predikat row --}}
                        <tr style="background-color:#f5f0e8;">
                            <td colspan="2" style="padding:14px 30px 14px 18px; text-align:right; background-color:#f5f0e8; border:2px solid #c9a227;">
                                <span style="color:#1e3a5f !important; font-weight:700; font-size:14px;">Predikat</span>
                            </td>
                            <td colspan="2" style="padding:14px 18px; background-color:#f5f0e8; border:2px solid #c9a227; color:#1e3a5f !important;">
                                @php
                                    $avg = $penilaians->avg('nilai');
                                    if ($avg >= 90) {
                                        $grade = 'Sangat Baik (A)';
                                    } elseif ($avg >= 75) {
                                        $grade = 'Baik (B)';
                                    } elseif ($avg >= 60) {
                                        $grade = 'Cukup (C)';
                                    } else {
                                        $grade = 'Kurang (D)';
                                    }
                                @endphp
                                <span style="color:#1e3a5f !important; font-weight:700; font-size:15px;">{{ $grade }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grade Legend -->
    <div class="card fade-in">
        <div class="card-header">
            <div class="card-icon" style="background: var(--warning)">
                <i class="fas fa-info-circle"></i>
            </div>
            <h3 class="card-title">Kategori Penilaian</h3>
        </div>
        <div class="card-content">
            <div class="legend-grid">
                <div class="legend-item">
                    <span class="legend-range">90,00 - 100</span>
                    <span class="legend-grade">Sangat Baik (A)</span>
                </div>
                <div class="legend-item">
                    <span class="legend-range">75,00 - 89,99</span>
                    <span class="legend-grade">Baik (B)</span>
                </div>
                <div class="legend-item">
                    <span class="legend-range">60,00 - 74,99</span>
                    <span class="legend-grade">Cukup (C)</span>
                </div>
                <div class="legend-item">
                    <span class="legend-range">00,00 - 59,99</span>
                    <span class="legend-grade">Kurang (D)</span>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced card animation with stagger effect
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        }, observerOptions);

        // Add fade-in animation to all cards
        const cards = document.querySelectorAll('.fade-in');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });

        // Add hover effects for table rows
        const tableRows = document.querySelectorAll('.modern-table tbody tr:not(.summary-row)');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });

        // Add click effect for buttons
        const buttons = document.querySelectorAll('.btn-modern');
        buttons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Create ripple effect
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.5);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Print functionality with better styling
        const originalTitle = document.title;
        window.addEventListener('beforeprint', function() {
            document.title = `Daftar Nilai - ${document.querySelector('.info-value').textContent}`;
        });
        
        window.addEventListener('afterprint', function() {
            document.title = originalTitle;
        });
    });

    // Add ripple animation CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection