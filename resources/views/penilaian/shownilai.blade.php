@extends('layouts.app')

@section('title', 'Daftar Nilai Siswa')

@section('styles')
    <title>Daftar Nilai Siswa</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --primary-dark: #3730a3;
            --secondary: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --success: #22c55e;
            --info: #3b82f6;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }

        .nilai-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Header Section - Enhanced */
        .nilai-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: var(--radius-lg);
            padding: 32px;
            color: white;
            margin-bottom: 32px;
            box-shadow: var(--shadow-lg), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
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

        .nilai-header::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
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
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-text h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }

        .header-text p {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 400;
        }

        /* Action Buttons - Improved */
        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-modern {
            padding: 12px 24px;
            border-radius: var(--radius-md);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            color: white;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: white;
        }

        .btn-primary-modern {
            background: rgba(255, 255, 255, 0.2);
        }

        .btn-primary-modern:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .btn-success-modern {
            background: rgba(34, 197, 94, 0.9);
        }

        .btn-success-modern:hover {
            background: rgba(34, 197, 94, 1);
        }

        /* Cards - Enhanced Design */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            margin-bottom: 24px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 28px;
            border-bottom: 2px solid #f1f5f9;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .card-header-left {
            display: flex;
            align-items: center;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            background: var(--secondary);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 16px;
            box-shadow: var(--shadow-md);
        }

        .card-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.025em;
        }

        .card-content {
            padding: 28px;
        }

        /* Quick Stats - Enhanced */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 28px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card.primary::before {
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
        }

        .stat-card.success::before {
            background: linear-gradient(90deg, var(--success), #16a34a);
        }

        .stat-card.info::before {
            background: linear-gradient(90deg, var(--info), #2563eb);
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: var(--shadow-md);
        }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 4px;
            color: var(--text-primary);
            letter-spacing: -0.025em;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Student Info Grid */
        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .info-item {
            padding: 24px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: var(--radius-md);
            border-left: 4px solid var(--secondary);
            transition: all 0.3s ease;
            position: relative;
        }

        .info-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(16, 185, 129, 0.02) 100%);
            border-radius: var(--radius-md);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .info-item:hover::before {
            opacity: 1;
        }

        .info-item:hover {
            transform: translateX(4px);
            box-shadow: var(--shadow-md);
        }

        .info-label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 12px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-value {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 16px;
            position: relative;
            z-index: 1;
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
            border: 2px solid #ffffff !important;
        }

        .modern-table thead th:first-child {
            width: 60px;
        }

        .modern-table thead th:nth-child(2) {
            text-align: left !important;
        }

        .modern-table thead th:nth-child(5) {
            width: 120px;
        }

        /* Body rows - clean white */
        .modern-table tbody tr {
            background-color: #ffffff !important;
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
            border: 2px solid #1e3a5f !important;
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

        .modern-table tbody td:nth-child(4) {
            text-align: center !important;
        }

        .modern-table tbody td:nth-child(5) {
            text-align: center !important;
        }

        /* Action Buttons in Table */
        .table-btn {
            padding: 6px 10px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border: none;
            cursor: pointer;
            margin: 0 2px;
        }

        .table-btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            text-decoration: none;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #16a34a;
            color: white;
        }

        /* Summary Rows - Cream/beige background like reference */
        .modern-table tbody tr.summary-row {
            background-color: #f5f0e8 !important;
        }

        .modern-table tbody tr.summary-row:hover {
            background-color: #f5f0e8 !important;
        }

        .modern-table tbody tr.summary-row td {
            background-color: #f5f0e8 !important;
            border: 2px solid #1e3a5f !important;
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

        /* Grade Badge - Enhanced */
        .grade-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-sm);
        }

        .grade-a {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #15803d;
            border: 1px solid #22c55e;
        }

        .grade-b {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1d4ed8;
            border: 1px solid #3b82f6;
        }

        .grade-c {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #b45309;
            border: 1px solid #f59e0b;
        }

        .grade-d {
            background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
            color: #b91c1c;
            border: 1px solid #ef4444;
        }

        /* Grade Legend - Enhanced */
        .legend-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: var(--radius-md);
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .legend-item:hover {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            transform: translateX(4px);
            box-shadow: var(--shadow-md);
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

        /* Add Button in Header */
        .add-btn {
            background: var(--success);
            color: white;
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .add-btn:hover {
            background: #16a34a;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            color: white;
            text-decoration: none;
        }

        /* Print Styles */
        @media print {
            body {
                background: white !important;
            }
            
            .nilai-container {
                max-width: none;
                padding: 0;
            }
            
            .action-buttons,
            .btn-modern,
            .add-btn,
            .table-btn {
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
            .summary-row,
            .info-item,
            .legend-item {
                background: #f8fafc !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .grade-badge {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .card {
                box-shadow: none !important;
                break-inside: avoid;
            }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .student-info-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .nilai-container {
                padding: 16px;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            
            .action-buttons {
                width: 100%;
                justify-content: center;
            }
            
            .btn-modern {
                flex: 1;
                justify-content: center;
                min-width: 140px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 16px;
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
            
            .header-text h1 {
                font-size: 24px;
            }
            
            .card-header {
                padding: 20px;
            }
            
            .card-content {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .table-container {
                margin: -20px -20px 0 -20px;
                border-radius: 0;
            }
            
            .modern-table thead th,
            .modern-table tbody td {
                padding: 10px 6px;
            }
            
            .table-btn {
                padding: 6px 8px;
                font-size: 11px;
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

        /* Loading States */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .loading {
            animation: pulse 2s infinite;
        }

        /* Focus States for Accessibility */
        .btn-modern:focus,
        .table-btn:focus,
        .add-btn:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }
    </style>
@endsection

@section('content')
<div class="nilai-container">
    <!-- Header -->
    <div class="nilai-header fade-in">
        <div class="header-content">
            <div class="header-info">
                <div class="header-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="header-text">
                    <h1>Daftar Nilai Praktik Kerja Lapangan</h1>
                    <p>Hasil evaluasi dan penilaian pelaksanaan magang</p>
                </div>
            </div>
            <div class="action-buttons">
                <a href="{{ route('penilaian.index') }}" class="btn-modern btn-success-modern">
                    <i class="fas fa-arrow-left"></i>Kembali
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
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($penilaians->avg('nilai'), 1) }}</div>
                    <div class="stat-label">Rata-rata Nilai</div>
                </div>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-icon" style="background: var(--success)">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-content">
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
            </div>
        </div>
        <div class="stat-card info">
            <div class="stat-header">
                <div class="stat-icon" style="background: var(--info)">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $penilaians->count() }}</div>
                    <div class="stat-label">Aspek Dinilai</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Information -->
    <div class="card fade-in">
        <div class="card-header">
            <div class="card-header-left">
                <div class="card-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3 class="card-title">Informasi Siswa</h3>
            </div>
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
                <div class="info-item">
                    <div class="info-label">Pembimbing Lapangan</div>
                    <div class="info-value">{{ $sertifikat->mentorSiswa->mentor->nama ?? $sertifikat->nm_pembimbing ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Kepala Bidang (Kabid)</div>
                    <div class="info-value">{{ $sertifikat->nm_kadis ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nilai Table -->
    <div class="card fade-in">
        <div class="card-header">
            <div class="card-header-left">
                <div class="card-icon" style="background: var(--success)">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="card-title">Detail Penilaian</h3>
            </div>
            <a href="{{ route('penilaian.create', ['id_sertifikat' => $sertifikat->id_sertifikat]) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Tambah Penilaian
            </a>
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
                            <th style="background-color:#1e3a5f !important; color:#ffffff !important; font-weight:600; padding:15px 18px; text-align:center; font-size:13px; border:2px solid #c9a227; width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penilaians as $index => $penilaian)
                            <tr style="background-color:#ffffff;">
                                <td style="padding:14px 18px; text-align:center; font-size:14px; color:#374151; border:2px solid #c9a227;">{{ $loop->iteration }}</td>
                                <td style="padding:14px 18px; text-align:left; font-size:14px; color:#374151; border:2px solid #c9a227;">{{ $penilaian->aspek }}</td>
                                <td style="padding:14px 18px; text-align:center; font-size:14px; color:#374151; border:2px solid #c9a227;">{{ $penilaian->nilai }}</td>
                                <td style="padding:14px 18px; text-align:center; font-size:14px; color:#374151; border:2px solid #c9a227;">{{ $penilaian->ket ?? '-' }}</td>
                                <td style="padding:14px 18px; text-align:center; font-size:14px; color:#374151; border:2px solid #c9a227;">
                                    <a href="{{ route('penilaian.edit', $penilaian->id) }}" class="btn btn-warning btn-sm" title="Edit Penilaian">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('penilaian.destroy', $penilaian->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        {{-- Rata-rata row --}}
                        <tr style="background-color:#f5f0e8;">
                            <td colspan="3" style="padding:14px 30px 14px 18px; text-align:right; background-color:#f5f0e8; border:2px solid #c9a227;">
                                <span style="color:#1e3a5f !important; font-weight:700; font-size:14px;">Rata-rata</span>
                            </td>
                            <td colspan="2" style="padding:14px 18px; background-color:#f5f0e8; border:2px solid #c9a227; color:#1e3a5f !important;">
                                <span style="color:#1e3a5f !important; font-weight:700; font-size:15px;">{{ number_format($penilaians->avg('nilai'), 2) }}</span>
                            </td>
                        </tr>
                        {{-- Predikat row --}}
                        <tr style="background-color:#f5f0e8;">
                            <td colspan="3" style="padding:14px 30px 14px 18px; text-align:right; background-color:#f5f0e8; border:2px solid #c9a227;">
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

    <!-- Nilai Tugas Harian Section -->
    <div class="card fade-in">
        <div class="card-header">
            <div class="card-header-left">
                <div class="card-icon" style="background: var(--info)">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3 class="card-title">Nilai Tugas Harian</h3>
            </div>
        </div>
        <div class="card-content">
            @if($gradedTasks && $gradedTasks->count() > 0)
                <div class="table-container">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>Judul Tugas</th>
                                <th style="width: 200px;">Mentor Pemberi Tugas</th>
                                <th style="width: 100px;">Nilai</th>
                                <th style="width: 150px;">Tanggal Dikumpulkan</th>
                                <th style="width: 150px;">Tanggal Dinilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gradedTasks as $index => $submission)
                                <tr>
                                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                                    <td style="text-align: left;">
                                        <strong>{{ $submission->task->title }}</strong>
                                        @if($submission->mentor_feedback)
                                            <br>
                                            <small style="color: var(--text-muted); font-style: italic;">
                                                <i class="fas fa-comment-dots"></i> {{ \Illuminate\Support\Str::limit($submission->mentor_feedback, 80) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        {{ $submission->task->assignedByUser->nama ?? $submission->task->assignedByUser->username ?? '-' }}
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="grade-badge 
                                             @if($submission->grade === 'A') grade-a
                                             @elseif($submission->grade === 'B') grade-b
                                             @elseif($submission->grade === 'C') grade-c
                                             @elseif($submission->grade === 'D') grade-d
                                             @else grade-d
                                             @endif">
                                            {{ $submission->grade }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td style="text-align: center;">
                                        {{ $submission->graded_at ? $submission->graded_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                </tr>
                                @if($submission->mentor_feedback && strlen($submission->mentor_feedback) > 80)
                                    <tr style="background-color: #f8fafc;">
                                        <td colspan="6" style="padding: 12px 18px;">
                                            <div style="padding: 12px; background: #f0f9ff; border-left: 4px solid var(--info); border-radius: 6px;">
                                                <strong style="color: var(--info);">
                                                    <i class="fas fa-comment-alt"></i> Feedback Mentor:
                                                </strong>
                                                <p style="margin: 8px 0 0 0; color: var(--text-primary); white-space: pre-wrap;">{{ $submission->mentor_feedback }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            <!-- Summary Row -->
                            <tr class="summary-row">
                                <td colspan="3" style="text-align: right; padding-right: 18px;">
                                    <span class="summary-label">RATA-RATA NILAI TUGAS</span>
                                </td>
                                <td colspan="3" style="text-align: center;">
                                    <span class="summary-value">{{ number_format($gradedTasks->avg('grade'), 2) }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 48px 24px; color: var(--text-muted);">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.3;"></i>
                    <p style="font-size: 16px; font-weight: 500; margin: 0;">Belum ada tugas yang dinilai</p>
                    <p style="font-size: 14px; margin: 8px 0 0 0;">Tugas yang sudah dinilai oleh mentor akan muncul di sini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Grade Legend -->
    <div class="card fade-in">
        <div class="card-header d-flex justify-content-start">
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

        // Handle delete forms with SweetAlert2 confirmation
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                showConfirm('Yakin ingin menghapus aspek ini?', function() {
                    form.submit();
                });
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