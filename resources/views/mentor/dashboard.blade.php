@extends('layouts.app')

@section('title', 'Dashboard SIMGANG')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    {{-- Jika ada CSS khusus untuk modal pengumuman, tambahkan di sini --}}
    <style>
   /* Basic Modal Styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.5); /* Hitam transparan solid */
    /* backdrop-filter: blur(4px); /* Efek blur di belakang form */ */
    justify-content: center; /* Memposisikan konten modal di tengah horizontal */
    align-items: center; /* Memposisikan konten modal di tengah vertikal */
}

.modal.show {
    display: flex; /* Mengaktifkan display flex saat modal ditampilkan */
}

.modal-content {
    background-color: white; /* Menggunakan warna putih solid untuk latar belakang konten modal */
    margin: auto; /* Memposisikan konten modal di tengah */
    padding: 20px; /* Padding kembali ke nilai asli */
    border-radius: 8px; /* Border-radius kembali ke nilai asli */
    box-shadow: 0 4px 8px rgba(0,0,0,0.2); /* Box-shadow kembali ke nilai asli */
    width: 90%;
    max-width: 600px;
    position: relative;
    animation: modalSlide 0.3s ease; /* Animasi tetap dipertahankan */
}

/* Animasi baru untuk modal (dari sebelumnya) */
@keyframes modalSlide {
    from { opacity: 0; transform: translateY(-50px); }
    to { opacity: 1; transform: translateY(0); }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border-color); /* Menggunakan variabel CSS */
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.modal-title {
    margin: 0;
    font-size: 1.5em;
    color: var(--text-color); /* Menggunakan variabel CSS */
}

.modal-header .close {
    background: none;
    border: none;
    font-size: 1.5em;
    cursor: pointer;
    color: var(--text-color); /* Menggunakan variabel CSS */
}

.form-group {
    margin-bottom: 15px;
}

.form-label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: var(--text-color); /* Menggunakan variabel CSS */
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--border-color); /* Menggunakan variabel CSS */
    border-radius: 4px;
    background-color: var(--input-bg-color); /* Menggunakan variabel CSS */
    color: var(--text-color); /* Menggunakan variabel CSS */
    box-sizing: border-box;
}

.form-control.textarea {
    min-height: 100px;
    resize: vertical;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

/* Calendar Styles */
.calendar-container {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}
.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.calendar-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-color);
}
.calendar-nav button {
    background: none;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 5px 12px;
    cursor: pointer;
    color: var(--primary);
    transition: all 0.2s;
}
.calendar-nav button:hover {
    background: var(--primary);
    color: white;
}
.calendar-table {
    width: 100%;
    table-layout: fixed;
    border-collapse: separate;
    border-spacing: 2px;
}
.calendar-table th {
    text-align: center;
    padding: 10px 0;
    color: #64748b;
    font-weight: 600;
    font-size: 0.85rem;
}
.calendar-table td {
    text-align: center;
    padding: 12px 0;
    border-radius: 8px;
    cursor: pointer;
    position: relative;
    color: #334155;
    font-weight: 500;
    transition: all 0.2s;
}
.calendar-table td:not(.empty):hover {
    background-color: #f1f5f9;
}
.calendar-table td.empty {
    cursor: default;
}
.calendar-table td.today {
    background-color: #e0e7ff;
    color: var(--primary);
    font-weight: 700;
}
.calendar-table td.selected {
    background-color: var(--primary);
    color: white;
}
.calendar-table td.has-interns::after {
    content: '';
    display: block;
    width: 6px;
    height: 6px;
    background-color: #f59e0b; /* Orange dot indicator */
    border-radius: 50%;
    position: absolute;
    bottom: 4px;
    left: 50%;
    transform: translateX(-50%);
}
.calendar-table td.selected.has-interns::after {
    background-color: white;
}
.calendar-legend {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 15px;
    font-size: 0.85rem;
    color: #64748b;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
}
.legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

/* Alert Styles */
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    display: none; /* Hidden by default */
    align-items: center;
    gap: 10px;
}

.alert.show {
    display: flex;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
}

/* General button styles (adjust based on your main CSS) */
.btn {
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: background-color 0.2s ease;
}

.btn-primary {
    background-color: var(--primary-color); /* Menggunakan variabel CSS */
    color: white;
    border: 1px solid var(--primary-color); /* Menggunakan variabel CSS */
}

.btn-primary:hover {
    background-color: var(--primary-dark); /* Menggunakan variabel CSS */
}

.btn-secondary {
    background-color: var(--secondary-color); /* Menggunakan variabel CSS */
    color: white;
    border: 1px solid var(--secondary-color); /* Menggunakan variabel CSS */
}

.btn-secondary:hover {
    background-color: var(--secondary-dark); /* Menggunakan variabel CSS */
}

.btn-warning {
    background-color: var(--warning-color); /* Menggunakan variabel CSS */
    color: white;
    border: 1px solid var(--warning-color); /* Menggunakan variabel CSS */
}

.btn-warning:hover {
    background-color: var(--warning-dark); /* Menggunakan variabel CSS */
}

.btn-danger {
    background-color: var(--danger-color); /* Menggunakan variabel CSS */
    color: white;
    border: 1px solid var(--danger-color); /* Menggunakan variabel CSS */
}

.btn-danger:hover {
    background-color: var(--danger-dark); /* Menggunakan variabel CSS */
}

.btn-sm {
    padding: 5px 10px;
    font-size: 0.8rem;
}

/* Announcements Specific Styles */
:root {
    --primary: #007bff;
    --success: #28a745;
    --danger: #dc3545;
    --warning: #ffc107;
    --info: #17a2b8;
    --light: #f8f9fa;
    --dark: #343a40;
    --secondary: #6c757d;
    /* Tambahkan variabel CSS yang hilang jika diperlukan oleh dashboard.css atau tema Anda */
    --card-bg-color: white;
    --border-color: #e0e0e0;
    --text-color: #333;
    --input-bg-color: white;
    --primary-color: #007bff;
    --primary-dark: #0056b3;
    --secondary-color: #6c757d;
    --secondary-dark: #5a6268;
    --warning-color: #ffc107;
    --warning-dark: #e0a800;
    --danger-color: #dc3545;
    --danger-dark: #c82333;
    --background-color: #f4f7f6; /* Contoh, sesuaikan jika ada di dashboard.css */
}

* >
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .header h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .header p {
        opacity: 0.9;
    }

    .actions {
        display: flex;
        justify-content: between;
        margin-bottom: 2rem;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #667eea;
        color: white;
        border: 1px solid #667eea;
    }

    .btn-primary:hover {
        background: #5a6fd8;
        transform: translateY(-2px);
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 12px;
    }

    .announcements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }

    .announcement-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .announcement-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .announcement-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2d3748;
    }

    .announcement-type {
        display: inline-block;
        background: #667eea;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        margin-bottom: 1rem;
    }

    .announcement-content {
        color: #4a5568;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .announcement-date {
        color: #718096;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .announcement-actions {
        display: flex;
        gap: 10px;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #718096;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #cbd5e0;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #4a5568;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 2rem;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #000000;
    }

    .close {
        font-size: 1.5rem;
        cursor: pointer;
        color: #718096;
    }

    .close:hover {
        color: #2d3748;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #4a5568;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 2px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .alert {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        display: none;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }

        .announcements-grid {
            grid-template-columns: 1fr;
        }

        .modal-content {
            width: 95%;
            margin: 10% auto;
        }
    }

</style>
@endsection

@section('content')
<main>
    <!-- Alert Container untuk Success -->
    <div id="alertSuccess" class="alert alert-success" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
        <i class="fas fa-check-circle me-2"></i>
        <span id="successMessage"></span>
        <button type="button" class="btn-close" onclick="document.getElementById('alertSuccess').style.display='none';" style="margin-left: auto;"></button>
    </div>
    
    <!-- Alert Container untuk Error -->
    <div id="alertError" class="alert alert-danger" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
        <i class="fas fa-exclamation-circle me-2"></i>
        <span id="errorMessage"></span>
        <button type="button" class="btn-close" onclick="document.getElementById('alertError').style.display='none';" style="margin-left: auto;"></button>
    </div>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard SIMGANG
            </div>
            <div class="dashboard-actions">
                <a href="{{ route('mentor.exportData') }}" class="action-button btn-secondary" style="text-decoration: none;">
                    <i class="fas fa-download"></i> Ekspor Data
                </a>
                <a href="{{ route('mentor.create') }}" class="action-button btn-primary">
                    <i class="fas fa-user-plus"></i> Tambah Siswa
                </a>
            </div>
        </div>

        <div class="dashboard-welcome">
            <div class="welcome-text">
                <h2>Selamat Datang, !</h2>
                <p>Pantau dan kelola proses magang dengan mudah. Berikut adalah ringkasan data magang terkini Dinas Komunikasi dan Informatika Kabupaten Cilacap.</p>
            </div>
            <div class="welcome-date">
                <div>Hari ini</div>
                <div class="date" id="current-date"></div>
            </div>
        </div>

        <div class="stat-cards">
            {{-- Kartu Peserta Aktif --}}
            <div class="stat-card" style="border-left-color: var(--info);">
                <div class="stat-header">
                    <div class="stat-icon" style="background-color: var(--info);">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $siswaAktif }}</div>
                <div class="stat-label">Peserta Aktif</div>
                <div class="stat-footer">
                    <a href="{{ route('dashboard.siswaaktif') }}" class="stat-link">
                        <span>Lihat detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>



            {{-- Kartu Lembaga Pendidikan --}}
            <div class="stat-card" style="border-left-color: var(--secondary);">
                <div class="stat-header">
                    <div class="stat-icon" style="background-color: var(--secondary);">
                        <i class="fas fa-school"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $sekolahCount }}</div>
                <div class="stat-label">Lembaga Pendidikan</div>
                <div class="stat-footer">
                    <a href="{{ route('dashboard.sekolah') }}" class="stat-link">
                        <span>Lihat detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            {{-- Kartu Lulus Magang --}}
            <div class="stat-card" style="border-left-color: var(--success);">
                <div class="stat-header">
                    <div class="stat-icon" style="background-color: var(--success);">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $siswaLulus }}</div>
                <div class="stat-label">Lulus Magang</div>
                <div class="stat-footer">
                    <a href="{{ route('dashboard.siswalulus') }}" class="stat-link">
                        <span>Lihat detail</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            {{-- Kartu Verifikasi Pengajuan --}}
            <div class="stat-card" style="border-left-color: #667eea;">
                <div class="stat-header">
                    <div class="stat-icon" style="background-color: #667eea;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-value" id="pendingPengajuanCount">0</div>
                <div class="stat-label">Pengajuan Menunggu</div>
                <div class="stat-footer">
                    <a href="{{ route('mentor.verifikasi-pengajuan') }}" class="stat-link">
                        <span>Verifikasi</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row align-items-start mt-4" style="gap: 1.5rem 0;">
            <!-- Left Column: Grafik & Kalender -->
            <div class="col-lg-6 d-flex flex-column" style="gap: 1.5rem;">
                
                <!-- Card 1: Data Statistik Bidang -->
                <div class="dashboard-content m-0 shadow-sm border-0 rounded-4 d-flex flex-column" style="height: 450px;">
                    <div class="content-header border-bottom pb-3 mb-3">
                        <div class="content-title fw-bold">
                            <div class="icon shadow-sm" style="background-color: var(--primary);">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            Data Statistik Bidang
                        </div>
                        <div class="content-action">
                            <a href="{{ route('dashboard.statistik.magang') }}" class="btn btn-sm btn-primary rounded-pill text-white border-0 shadow-sm px-3">
                                <span class="fw-bold">Laporan Lengkap</span>
                                <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="chart-container flex-grow-1 d-flex justify-content-center align-items-center" style="position: relative; width: 100%; min-height: 0;">
                        <canvas id="bidangChart" style="max-height: 100%; max-width: 100%;"></canvas>
                    </div>
                </div>
                
                <!-- Card 2: Kalender Magang -->
                <div class="dashboard-content m-0 calendar-container shadow-sm border-0 rounded-4 d-flex flex-column flex-grow-1">
                    <div class="calendar-header border-bottom pb-3 mb-3">
                        <div class="calendar-title fw-bold">
                            <div class="icon shadow-sm d-inline-flex align-items-center justify-content-center me-2 text-white" style="background-color: var(--info); width: 35px; height: 35px; border-radius: 8px;">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            Kalender Magang
                        </div>
                        <div class="calendar-nav bg-light rounded-pill px-2 py-1 shadow-sm">
                            <button id="prevMonth" class="btn btn-sm text-primary rounded-circle" title="Bulan Sebelumnya" style="border:none;"><i class="fas fa-chevron-left"></i></button>
                            <span id="calendarMonthYear" class="mx-2 fw-bold text-dark" style="font-size: 0.9rem;"></span>
                            <button id="nextMonth" class="btn btn-sm text-primary rounded-circle" title="Bulan Berikutnya" style="border:none;"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="flex-grow-1 d-flex flex-column justify-content-center">
                        <table class="calendar-table mb-0">
                            <thead>
                                <tr>
                                    <th>Min</th><th>Sen</th><th>Sel</th><th>Rab</th><th>Kam</th><th>Jum</th><th>Sab</th>
                                </tr>
                            </thead>
                            <tbody id="calendarBody"></tbody>
                        </table>
                    </div>
                    <div class="calendar-legend mt-4 pt-3 border-top pb-1">
                        <div class="legend-item">
                            <div class="legend-dot shadow-sm" style="background: #f59e0b;"></div> Ada Peserta
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot shadow-sm" style="background: #e0e7ff; border: 1px solid var(--primary);"></div> Hari Ini
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot shadow-sm" style="background: var(--primary);"></div> Dipilih
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- Right Column: Daftar Anak Magang & Detail Jadwal -->
            <div class="col-lg-6 d-flex flex-column" style="gap: 1.5rem;">
                
                <!-- Card 3: Daftar Anak Magang Aktif -->
                <div class="dashboard-content m-0 d-flex flex-column shadow-sm border-0 rounded-4" style="height: 450px;">
                    <div class="content-header border-bottom pb-3 mb-3">
                        <div class="content-title fw-bold">
                            <div class="icon shadow-sm" style="background-color: var(--success);">
                                <i class="fas fa-users"></i>
                            </div>
                            Daftar Anak Magang Aktif
                        </div>
                    </div>
                    
                    <div class="mb-3 px-1">
                        <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                            <span class="input-group-text bg-white border-0 text-muted ps-3"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control border-0 shadow-none bg-white" id="searchInternshipStudentInput" placeholder="Cari peserta (nama, sekolah, jurusan)...">
                            <button class="btn btn-primary px-4 fw-bold" type="button" id="searchInternshipStudentBtn" style="border-radius: 0 50rem 50rem 0;">Cari</button>
                        </div>
                    </div>
                    
                    <div class="internship-students-list-container flex-grow-1" style="overflow-y: auto; padding-right: 5px;">
                        <div id="internshipLoading" class="empty-inbox text-center py-5" style="display: none;">
                            <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                            <h5 class="text-gray-700 fw-bold">Memuat daftar peserta...</h5>
                            <p class="text-gray-500 small">Harap tunggu sebentar.</p>
                        </div>
                        <div id="noInternshipStudentsFound" class="empty-inbox text-center py-5" style="display: none;">
                            <i class="fas fa-user-slash fa-3x text-warning mb-3 opacity-75"></i>
                            <h5 class="text-gray-700 fw-bold">Tidak ada peserta aktif.</h5>
                            <p class="text-gray-500 small" id="noStudentsMessage"></p>
                        </div>
                        <ul class="list-group list-group-flush gap-2" id="internshipStudentsList"></ul>
                    </div>
                </div>

                <!-- Card 4: Detail Jadwal Magang -->
                <div class="dashboard-content m-0 shadow-sm border-0 rounded-4 d-flex flex-column flex-grow-1" style="transition: all 0.3s ease;">
                    <div class="content-header border-bottom pb-3 mb-3">
                        <div class="content-title fw-bold">
                            <div class="icon shadow-sm" style="background-color: var(--secondary);">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            Detail Jadwal Magang
                        </div>
                        <div class="current-datetime text-primary fw-bold" id="detailDatetime" style="font-size: 0.85rem; background: var(--background); padding: 5px 12px; border-radius: 20px;"></div>
                    </div>
                    
                    <div id="detailJadwalContent" class="flex-grow-1 d-flex flex-column" style="position: relative;">
                        <!-- Default Empty State -->
                        <div id="detailJadwalEmpty" class="text-center py-4 fade-in m-auto w-100">
                            <div class="icon-circle shadow-sm bg-light text-primary mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 28px;">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h5 class="text-secondary fw-bold">Belum Ada Tanggal Dipilih</h5>
                            <p class="text-muted small px-4 mb-0" style="line-height: 1.6;">Pilih salah satu tanggal pada Kalender Magang untuk melihat informasi peserta yang sedang menjalani magang pada tanggal tersebut.</p>
                        </div>

                        <!-- Data State -->
                        <div id="detailJadwalData" style="display: none; overflow-y: auto; flex: 1; padding-right: 5px;" class="fade-in">
                            <ul class="list-group list-group-flush gap-2" id="detailJadwalList"></ul>
                        </div>
                        
                        <!-- Loading State -->
                        <div id="detailJadwalLoading" class="text-center py-4 fade-in m-auto w-100" style="display: none;">
                            <i class="fas fa-circle-notch fa-spin fa-3x text-primary mb-3"></i>
                            <h5 class="text-secondary fw-bold">Memuat Jadwal...</h5>
                        </div>
                        
                        <!-- Not Found State -->
                        <div id="detailJadwalNotFound" class="text-center py-4 fade-in m-auto w-100" style="display: none;">
                            <div class="icon-circle shadow-sm bg-light text-warning mx-auto mb-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 28px;">
                                <i class="fas fa-users-slash"></i>
                            </div>
                            <h5 class="text-secondary fw-bold">Tidak Ada Peserta</h5>
                            <p class="text-muted small px-4 mb-0">Tidak terdapat peserta magang yang aktif pada tanggal ini.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <div class="dashboard-content mt-4">
            <div class="content-header">
                <div class="content-title">
                    <div class="icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    Pengumuman
                </div>
                <div class="content-action">
                    <a href="javascript:void(0)" class="content-action-link" onclick="openModal('create')">
                        <span>Tambah Pengumuman</span>
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
            <div class="announcements-grid" id="announcementsGrid">
                </div>
        </div>

    </div>
</main>
    
<div class="modal fade" id="assignTaskModal" tabindex="-1" aria-labelledby="assignTaskModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="assignTaskModalLabel"><i class="fas fa-tasks me-2"></i> Tetapkan Tugas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    Menetapkan tugas untuk siswa: <strong id="taskRecipientName"></strong>
                </div>

                <form id="assignTaskForm">
                    <input type="hidden" id="taskStudentSertifikatId">
                    <div class="mb-3">
                        <label for="taskTitle" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="taskTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="taskDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="taskDueDate" class="form-label">Tenggat Waktu</label>
                        <input type="date" class="form-control" id="taskDueDate">
                    </div>
                    <div class="mb-3">
                        <label for="taskBidangFilter" class="form-label">Filter Bidang</label>
                        <select class="form-control" id="taskBidangFilter" onchange="loadStudentsByBidangForDashboard()">
                            <option value="">Semua Bidang</option>
                            @foreach(\App\Constants\BidangConstants::getValidBidang() as $b)
                                <option value="{{ $b }}">{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="taskStudent" class="form-label">Pilih Siswa Penerima Tugas <span class="text-danger">*</span></label>
                        <select class="form-control" id="taskStudent" required>
                            <option value="">-- Pilih Siswa --</option>
                        </select>
                        <small class="text-muted">Pilih siswa dari bidang manapun untuk memberikan tugas lintas bidang</small>
                    </div>
                    <div id="assignTaskAlert" class="alert mt-3" style="display:none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="submitAssignTaskBtn">Tetapkan Tugas</button>
            </div>
        </div>
    </div>
</div>

<div id="announcementModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle" class="modal-title">Tambah Pengumuman</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <form id="announcementForm">
            <div class="form-group">
                <label class="form-label" for="title">Judul Pengumuman</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="type">Jenis Pengumuman</label>
                <select id="type" name="type" class="form-control" required>
                    <option value="">Pilih Jenis</option>
                    <option value="Akademik">Akademik</option>
                    <option value="Beasiswa">Beasiswa</option>
                    <option value="Kegiatan">Kegiatan</option>
                    <option value="Pengumuman">Pengumuman</option>
                    <option value="Informasi">Informasi</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="content">Isi Pengumuman</label>
                <textarea id="content" name="content" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="published_at">Tanggal Publikasi</label>
                <input type="date" id="published_at" name="published_at" class="form-control">
            </div>
            <div class="form-group">
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-danger" onclick="closeModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Konfirmasi Hapus</h2>
            <span class="close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <p>Apakah Anda yakin ingin menghapus pengumuman "<span id="deleteItemTitle"></span>"?</p>
        <div class="form-group">
            <button id="confirmDeleteBtn" class="btn btn-danger">
                <i class="fas fa-trash"></i> Hapus
            </button>
            <button type="button" class="btn btn-primary" onclick="closeDeleteModal()">
                <i class="fas fa-times"></i> Batal
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    {{-- Script untuk Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set tanggal hari ini dengan format lokal Indonesia
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const today = new Date();
            document.getElementById('current-date').textContent = today.toLocaleDateString('id-ID', options);

            // Data untuk Chart.js dari PHP
            const chartLabels = JSON.parse('{!! $data->pluck("bidang") !!}');
            const chartValues = JSON.parse('{!! $data->pluck("total") !!}');

            // Konfigurasi chart
            const chartData = {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Siswa per Bidang',
                    data: chartValues,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)'
                    ],
                    borderWidth: 1,
                }]
            };

            // Konfigurasi chart
            const config = {
                type: 'pie',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 10,
                            bottom: 10
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 15,
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        title: {
                            display: false
                        }
                    }
                },
            };

            // Buat chart
            const ctx = document.getElementById('bidangChart').getContext('2d');
            new Chart(ctx, config);
        });

         // Data storage
        let announcements = [];
        let editingId = null;
        let deletingId = null;

        // Calendar variables
        let currentDate = new Date();
        let selectedDate = new Date();
        const semuaMagang = @json($semuaMagang ?? []);

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadAnnouncements();
            renderCalendar(currentDate);

            // Calendar Navigation
            document.getElementById('prevMonth').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });
            document.getElementById('nextMonth').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });

        // Form submission
            document.getElementById('announcementForm').addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form submitted, editingId:', editingId);
                
                // Validasi form values
                const title = document.getElementById('title').value.trim();
                const type = document.getElementById('type').value.trim();
                const content = document.getElementById('content').value.trim();
                
                if (!title || !type || !content) {
                    showAlert('error', 'Semua field harus diisi! (Judul, Jenis, dan Isi Pengumuman)');
                    return;
                }
                
                const formData = new FormData(this);
                const announcementData = {
                    title: title,
                    type: type,
                    content: content,
                    published_at: formData.get('published_at'),
                };

                console.log('Announcement data:', announcementData);
                console.log('editingId value:', editingId, 'type:', typeof editingId);

                if (editingId) {
                    // Update existing announcement
                    console.log('Calling updateAnnouncement with ID:', editingId);
                    updateAnnouncement(editingId, announcementData);
                } else {
                    // Add new announcement
                    console.log('Calling createAnnouncement');
                    createAnnouncement(announcementData);
                }
            });

            // Delete confirmation
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (deletingId) {
                    deleteAnnouncement(deletingId);
                }
            });
        });

        // Modal functions
        function openModal(mode, id = null) {
            const modal = document.getElementById('announcementModal');
            const modalTitle = document.getElementById('modalTitle');
            const form = document.getElementById('announcementForm');
            const submitBtn = document.getElementById('submitBtn');

            console.log('openModal called with:', { mode, id });

            if (mode === 'create') {
                modalTitle.textContent = 'Tambah Pengumuman';
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan';
                form.reset();
                editingId = null;
                console.log('Create mode - editingId set to null');
        } else if (mode === 'edit' && id) {
                modalTitle.textContent = 'Edit Pengumuman';
                submitBtn.innerHTML = '<i class="fas fa-edit"></i> Update';
                editingId = id;
                console.log('Edit mode - editingId set to:', editingId);
                console.log('Edit mode - editingId type:', typeof editingId);
                console.log('All announcements:', announcements);
                
                const announcement = announcements.find(a => a.id === id);
                console.log('Found announcement:', announcement);
                console.log('Searching for id:', id, 'in announcements with ids:', announcements.map(a => a.id));
                
                if (announcement) {
                    const titleElem = document.getElementById('title');
                    const typeElem = document.getElementById('type');
                    const contentElem = document.getElementById('content');
                    const pubElem = document.getElementById('published_at');
                    
                    titleElem.value = announcement.title;
                    typeElem.value = announcement.type;
                    contentElem.value = announcement.content;
                    // Input type="date" hanya menerima format YYYY-MM-DD.
                    // published_at dari server bisa berupa datetime penuh (misal "2024-01-10 00:00:00")
                    // sehingga perlu diambil 10 karakter pertama saja.
                    const rawPubAt = announcement.published_at || announcement.formatted_published_at || '';
                    pubElem.value = rawPubAt ? rawPubAt.substring(0, 10) : '';
                    
                    console.log('Form fields populated:');
                    console.log('  title:', titleElem.value);
                    console.log('  type:', typeElem.value);
                    console.log('  content:', contentElem.value);
                    console.log('  published_at:', pubElem.value);
                } else {
                    console.warn('Announcement not found in announcements array');
                    const matchingByIdString = announcements.filter(a => String(a.id) === String(id));
                    if (matchingByIdString.length > 0) {
                        console.log('Found by string comparison:', matchingByIdString[0]);
                        const ann = matchingByIdString[0];
                        document.getElementById('title').value = ann.title;
                        document.getElementById('type').value = ann.type;
                        document.getElementById('content').value = ann.content;
                        // Sama: ambil 10 karakter pertama untuk input[type=date]
                        const rawPub = ann.published_at || ann.formatted_published_at || '';
                        document.getElementById('published_at').value = rawPub ? rawPub.substring(0, 10) : '';
                        editingId = parseInt(id);
                    }
                }
            }

            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('announcementModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            editingId = null;
        }

        function openDeleteModal(id) {
            const modal = document.getElementById('deleteModal');
            const deleteItemTitle = document.getElementById('deleteItemTitle');
            
            const announcement = announcements.find(a => a.id === id);
            if (announcement) {
                deleteItemTitle.textContent = announcement.title;
                deletingId = id;
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            deletingId = null;
        }

        // Create new announcement
        function createAnnouncement(data) {
            const url = "{{ route('announcements.store') }}";
            
            console.log('Creating announcement:', { url, data });
            
            $.ajax({
                url: url,
                type: "POST",
                data: JSON.stringify(data),
                contentType: "application/json", 
                dataType: "json", 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Create response:', response);
                    if (response.success) {
                        showAlert('success', 'Pengumuman berhasil ditambahkan!');
                        loadAnnouncements(); // Reload data from database
                        closeModal();
                    } else {
                        showAlert('error', response.message || 'Gagal menambahkan pengumuman!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Create AJAX error:', { xhr, status, error, responseText: xhr.responseText });
                    showAlert('error', 'Terjadi kesalahan saat menambahkan pengumuman!');
                }
            });
        }

        // Update existing announcement
        function updateAnnouncement(id, data) {
            const url = `/dashboard/pengumuman/update/${id}`;
            
            console.log('Updating announcement:', { id, data, url });
            console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
            
            $.ajax({
                url: url,
                type: "PUT",
                data: JSON.stringify(data),
                contentType: "application/json", 
                dataType: "json", 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Update response:', response);
                    if (response.success) {
                        showAlert('success', response.message || 'Pengumuman berhasil diperbarui!');
                        loadAnnouncements(); // Reload data from database
                        closeModal();
                    } else {
                        console.error('Update failed:', response.message);
                        showAlert('error', response.message || 'Gagal memperbarui pengumuman!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Update AJAX error:', { 
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        url: url,
                        id: id
                    });
                    
                    let errorMsg = 'Terjadi kesalahan saat memperbarui pengumuman!';
                    if (xhr.status === 404) {
                        errorMsg = 'Pengumuman tidak ditemukan atau Anda tidak memiliki akses!';
                    } else if (xhr.status === 422) {
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            errorMsg = errorResponse.message || 'Data input tidak valid!';
                        } catch (e) {
                            errorMsg = 'Data input tidak valid!';
                        }
                    } else if (xhr.status === 500) {
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            errorMsg = errorResponse.message || 'Terjadi kesalahan pada server!';
                        } catch (e) {
                            errorMsg = 'Terjadi kesalahan pada server!';
                        }
                    }
                    showAlert('error', errorMsg);
                }
            });
        }

        // Delete announcement
        function deleteAnnouncement(id) {
            const url = `/dashboard/pengumuman/delete/${id}`;
            
            $.ajax({
                url: url,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Pengumuman berhasil dihapus!');
                        loadAnnouncements(); // Reload data from database
                        closeDeleteModal();
                    } else {
                        showAlert('error', 'Gagal menghapus pengumuman!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    showAlert('error', 'Terjadi kesalahan saat menghapus pengumuman!');
                }
            });
        }

        // Load announcements from database
        function loadAnnouncements() {
            const url = "{{ route('announcements.get') }}";
            
            console.log('Loading announcements from:', url);
            
            $.ajax({
                url: url,
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Load announcements response:', response);
                    if (response.success) {
                        announcements = response.data;
                        console.log('Announcements loaded:', announcements.length, 'items');
                        renderAnnouncements();
                    } else {
                        console.error('Failed to load announcements:', response.message);
                        showAlert('error', response.message || 'Gagal memuat pengumuman!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Load AJAX error:', { xhr, status, error, responseText: xhr.responseText });
                    // Fallback to sample data if database fails
                    loadSampleData();
                }
            });
        }

        // Fallback sample data
        function loadSampleData() {
            announcements = [
                {
                    id: 1,
                    title: 'Libur Semester Ganjil 2024',
                    type: 'Akademik',
                    content: 'Libur semester ganjil akan dimulai pada tanggal 15 Januari 2024 sampai dengan 30 Januari 2024.',
                    published_at: '2024-01-10',
                    created_at: '2024-01-08T10:00:00Z'
                },
                {
                    id: 2,
                    title: 'Pendaftaran Beasiswa Prestasi',
                    type: 'Beasiswa',
                    content: 'Pendaftaran beasiswa prestasi untuk semester genap tahun 2024 telah dibuka. Silakan mendaftar melalui portal akademik.',
                    published_at: '2024-01-12',
                    created_at: '2024-01-08T14:30:00Z'
                },
                {
                    id: 3,
                    title: 'Pelatihan Soft Skills',
                    type: 'Kegiatan',
                    content: 'Akan diadakan pelatihan soft skills untuk mahasiswa semester 6 dan 8. Daftar segera karena kuota terbatas.',
                    published_at: '2024-01-15',
                    created_at: '2024-01-08T16:45:00Z'
                }
            ];
            renderAnnouncements();
        }

        function renderAnnouncements() {
            const grid = document.getElementById('announcementsGrid');
            
            if (announcements.length === 0) {
                grid.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-bullhorn"></i>
                        <h3>Belum ada pengumuman</h3>
                        <p>Klik tombol "Tambah Pengumuman" untuk membuat pengumuman baru</p>
                    </div>
                `;
                return;
            }

            grid.innerHTML = announcements.map(announcement => {
                // Tampilkan tombol edit/hapus hanya jika user bisa mengedit pengumuman ini
                const actionButtons = announcement.can_edit ? `
                    <div class="announcement-actions">
                        <button class="btn btn-primary btn-sm" onclick="openModal('edit', ${announcement.id})" type="button">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="openDeleteModal(${announcement.id})" type="button">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                ` : `
                    <div class="announcement-info">
                        <small class="text-muted"><i class="fas fa-info-circle"></i> Pengumuman dari ${announcement.creator_name || 'Admin'}</small>
                    </div>
                `;

                return `
                    <div class="announcement-card">
                        <div class="announcement-title">${announcement.title}</div>
                        <div class="announcement-type">${announcement.type}</div>
                        <div class="announcement-content">${announcement.content}</div>
                        <div class="announcement-date">
                            <i class="fas fa-calendar"></i> ${formatDate(announcement.published_at || announcement.formatted_published_at)}
                        </div>
                        ${actionButtons}
                    </div>
                `;
            }).join('');
        }

        function formatDate(dateString) {
            if (!dateString) return 'Tanggal tidak valid';
            
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        // Calendar Logic
        function renderCalendar(date) {
            const year = date.getFullYear();
            const month = date.getMonth();
            const firstDayIndex = new Date(year, month, 1).getDay();
            const lastDayDate = new Date(year, month + 1, 0).getDate();
            const today = new Date();
            
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            document.getElementById('calendarMonthYear').textContent = `${monthNames[month]} ${year}`;
            
            let days = "";
            let dayCount = 1;
            
            for (let i = 0; i < 6; i++) { // 6 rows max
                let row = "<tr>";
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDayIndex) {
                        row += `<td class="empty"></td>`;
                    } else if (dayCount > lastDayDate) {
                        row += `<td class="empty"></td>`;
                    } else {
                        // Check conditions
                        const currentCellDate = new Date(year, month, dayCount);
                        // Format currentCellDate to YYYY-MM-DD precisely without timezone shifting
                        const formattedCellDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(dayCount).padStart(2, '0')}`;
                        
                        let classes = [];
                        if (dayCount === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                            classes.push('today');
                        }
                        if (dayCount === selectedDate.getDate() && month === selectedDate.getMonth() && year === selectedDate.getFullYear()) {
                            classes.push('selected');
                        }
                        
                        // Check if there are interns on this date
                        const hasInterns = semuaMagang.some(magang => {
                            return formattedCellDate >= magang.tgl_mulai && formattedCellDate <= magang.tgl_selesai;
                        });
                        
                        if (hasInterns) classes.push('has-interns');
                        
                        row += `<td class="${classes.join(' ')}" onclick="selectDate(${year}, ${month}, ${dayCount})">${dayCount}</td>`;
                        dayCount++;
                    }
                }
                row += "</tr>";
                days += row;
                if (dayCount > lastDayDate) break; // Stop adding rows if month is done
            }
            document.getElementById('calendarBody').innerHTML = days;
        }

        window.selectDate = function(year, month, day) {
            selectedDate = new Date(year, month, day);
            renderCalendar(currentDate); // Re-render to highlight selected date
            
            const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            
            // Show feedback in detail column header
            const dateStr = selectedDate.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            document.getElementById('detailDatetime').innerHTML = `<i class="fas fa-calendar-day me-1"></i> ${dateStr}`;
            
            // Fetch detail jadwal for selected date
            fetchDetailJadwal(formattedDate);
        };

        function showAlert(type, message) {
            const alertElement = document.getElementById(type === 'success' ? 'alertSuccess' : 'alertError');
            const messageElement = document.getElementById(type === 'success' ? 'successMessage' : 'errorMessage');
            
            if (alertElement && messageElement) {
                messageElement.textContent = message;
                alertElement.style.display = 'block';
                
                setTimeout(() => {
                    alertElement.style.display = 'none';
                }, 3000);
            }
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const announcementModal = document.getElementById('announcementModal');
            const deleteModal = document.getElementById('deleteModal');
            
            if (e.target === announcementModal) {
                closeModal();
            }
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
        });
        //siswa
        // Pastikan jQuery dan Bootstrap sudah dimuat sebelum skrip ini
     // Skrip JavaScript yang diperbarui agar sesuai dengan struktur HTML Bootstrap
     $(document).ready(function() {
            // Definisi elemen-elemen DOM
            const internshipLoading = $('#internshipLoading');
            const noInternshipStudentsFound = $('#noInternshipStudentsFound');
            const noStudentsMessage = $('#noStudentsMessage');
            const internshipStudentsList = $('#internshipStudentsList');
            const searchInternshipStudentInput = $('#searchInternshipStudentInput');
            const searchInternshipStudentBtn = $('#searchInternshipStudentBtn');
            const currentDatetimeElement = $('#currentDatetime');

            // Definisi elemen-elemen DOM untuk modal penetapan tugas
            const assignTaskModal = $('#assignTaskModal');
            const taskRecipientName = $('#taskRecipientName');
            const taskStudentSertifikatId = $('#taskStudentSertifikatId');
            const taskTitle = $('#taskTitle');
            const taskDescription = $('#taskDescription');
            const taskDueDate = $('#taskDueDate');
            const taskStatus = $('#taskStatus');
            const submitAssignTaskBtn = $('#submitAssignTaskBtn');
            const assignTaskAlert = $('#assignTaskAlert');

            // Fungsi untuk menampilkan tanggal dan waktu saat ini
            function updateCurrentDatetime() {
                const now = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
                const formattedDatetime = now.toLocaleDateString('id-ID', options);
                currentDatetimeElement.text(formattedDatetime);
            }

            // Panggil fungsi untuk memperbarui tanggal dan waktu setiap detik
            updateCurrentDatetime();
            setInterval(updateCurrentDatetime, 1000);

            // Fungsi untuk memuat daftar seluruh siswa magang aktif (untuk card Daftar Anak Magang Aktif)
            function fetchActiveInternshipStudents(searchTerm = '') {
                const internshipStudentsList = $('#internshipStudentsList');
                const internshipLoading = $('#internshipLoading');
                const noInternshipStudentsFound = $('#noInternshipStudentsFound');
                const noStudentsMessage = $('#noStudentsMessage');

                internshipStudentsList.hide();
                noInternshipStudentsFound.hide();
                internshipLoading.fadeIn(200);

                // Gunakan tanggal hari ini agar selalu menampilkan yang sedang aktif saat ini
                const todayDate = new Date();
                const dateString = `${todayDate.getFullYear()}-${String(todayDate.getMonth() + 1).padStart(2, '0')}-${String(todayDate.getDate()).padStart(2, '0')}`;

                $.ajax({
                    url: '{{ url("api/internship-students") }}',
                    type: 'GET',
                    data: {
                        date: dateString,
                        search: searchTerm
                    },
                    success: function(response) {
                        internshipLoading.hide();
                        
                        if (response.success && response.data && response.data.length > 0) {
                            internshipStudentsList.empty();
                            internshipStudentsList.fadeIn(300);
                            
                            response.data.forEach(function(student) {
                                const statusBadgeClass = student.status_magang === 'Sedang Berjalan' ? 'bg-success' : 'bg-secondary';
                                
                                const listItem = `
                                    <li class="list-group-item d-flex justify-content-between align-items-center rounded-3 border mb-2 shadow-sm" style="background: #fff; transition: transform 0.2s; cursor: default;">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm" style="width: 45px; height: 45px; font-weight: bold; font-size: 18px;">
                                                ${student.nama_lengkap.charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark">${student.nama_lengkap} <span class="badge bg-light text-secondary ms-1 border">${student.nim_nis}</span></h6>
                                                <p class="mb-1 text-muted small"><i class="fas fa-school me-1"></i> ${student.asal_sekolah || '-'} | <i class="fas fa-book me-1"></i> ${student.jurusan || '-'}</p>
                                                <div class="d-flex align-items-center mt-1">
                                                    <span class="badge ${statusBadgeClass} me-2 shadow-sm"><i class="fas fa-info-circle me-1"></i> ${student.status_magang}</span>
                                                    <small class="text-primary fw-bold"><i class="fas fa-briefcase me-1"></i> ${student.bidang || '-'}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ms-2">
                                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#assignTaskModal"
                                                    data-id-sertifikat="${student.id_sertifikat}"
                                                    data-nama-lengkap="${student.nama_lengkap}"
                                                    title="Tetapkan Tugas">
                                                <i class="fas fa-tasks"></i>
                                            </button>
                                        </div>
                                    </li>
                                `;
                                internshipStudentsList.append(listItem);
                            });
                        } else {
                            internshipStudentsList.hide();
                            noInternshipStudentsFound.fadeIn(300);
                            noStudentsMessage.text(response.message || 'Tidak ada peserta aktif ditemukan dengan kata kunci tersebut.');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        internshipLoading.hide();
                        internshipStudentsList.hide();
                        noInternshipStudentsFound.fadeIn(300);
                        let errorMessage = 'Gagal memuat daftar peserta. Terjadi kesalahan jaringan.';
                        noStudentsMessage.text(errorMessage);
                    }
                });
            }

            // Fungsi baru untuk memuat Detail Jadwal Magang berdasarkan tanggal kalender
            window.fetchDetailJadwal = function(dateStr) {
                $('#detailJadwalEmpty, #detailJadwalData, #detailJadwalNotFound').hide();
                $('#detailJadwalLoading').fadeIn(200);

                $.ajax({
                    url: '{{ url("api/internship-students") }}',
                    type: 'GET',
                    data: { date: dateStr },
                    success: function(response) {
                        $('#detailJadwalLoading').hide();
                        
                        if (response.success && response.data && response.data.length > 0) {
                            const list = $('#detailJadwalList');
                            list.empty();
                            
                            response.data.forEach(function(student) {
                                const statusBadgeClass = student.status_magang === 'Sedang Berjalan' ? 'bg-success' : 'bg-secondary';
                                const tglMulai = new Date(student.tgl_mulai).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'});
                                const tglSelesai = new Date(student.tgl_selesai).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'});
                                
                                const listItem = `
                                    <li class="list-group-item rounded-3 border mb-2 shadow-sm" style="background: #fff; border-left: 4px solid var(--primary) !important;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-user-circle me-1"></i> ${student.nama_lengkap}</h6>
                                            <span class="badge ${statusBadgeClass}">${student.status_magang}</span>
                                        </div>
                                        <div class="row g-2 small text-muted">
                                            <div class="col-6"><i class="fas fa-school me-1"></i> ${student.asal_sekolah || '-'}</div>
                                            <div class="col-6"><i class="fas fa-book me-1"></i> ${student.jurusan || '-'}</div>
                                            <div class="col-6"><i class="fas fa-briefcase me-1"></i> ${student.bidang || '-'}</div>
                                            <div class="col-6"><i class="fas fa-calendar-alt me-1"></i> ${tglMulai} - ${tglSelesai}</div>
                                        </div>
                                    </li>
                                `;
                                list.append(listItem);
                            });
                            
                            $('#detailJadwalData').fadeIn(300);
                        } else {
                            $('#detailJadwalNotFound').fadeIn(300);
                        }
                    },
                    error: function() {
                        $('#detailJadwalLoading').hide();
                        $('#detailJadwalNotFound').fadeIn(300);
                    }
                });
            };

            // Panggil fungsi untuk memuat daftar siswa aktif saat halaman pertama kali dimuat
            fetchActiveInternshipStudents();

            // Event listener untuk tombol pencarian Daftar Anak Magang Aktif
            searchInternshipStudentBtn.on('click', function() {
                const searchTerm = searchInternshipStudentInput.val();
                fetchActiveInternshipStudents(searchTerm);
            });

            // Event listener untuk input pencarian saat 'Enter' ditekan
            searchInternshipStudentInput.on('keypress', function(e) {
                if (e.which === 13) {
                    searchInternshipStudentBtn.click();
                }
            });

            // --- Event listener untuk modal 'assignTaskModal' ---
            assignTaskModal.on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                const studentIdSertifikat = button.data('id-sertifikat');
                const studentNamaLengkap = button.data('nama-lengkap');

                taskRecipientName.text(studentNamaLengkap);
                taskStudentSertifikatId.val(studentIdSertifikat);

                taskTitle.val('');
                taskDescription.val('');
                taskDueDate.val('');
                taskStatus.val('pending');
                $('#taskBidangFilter').val('');
                $('#taskStudent').html('<option value="">-- Pilih Siswa --</option>');
                assignTaskAlert.hide().removeClass('alert-success alert-danger alert-info');
                submitAssignTaskBtn.prop('disabled', false);

                // If studentIdSertifikat is present, hide the selection fields
                if (studentIdSertifikat) {
                    $('#taskBidangFilter').closest('.mb-3').hide();
                    $('#taskStudent').closest('.mb-3').hide();
                } else {
                    $('#taskBidangFilter').closest('.mb-3').show();
                    $('#taskStudent').closest('.mb-3').show();
                    // Load all students by default
                    loadStudentsByBidangForDashboard();
                }
            });

            // Function untuk load existing tasks
            function loadExistingTasks(sertifikatPklId) {
                $.ajax({
                    url: "{{ route('api.tasks.student', '') }}/" + sertifikatPklId,
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            let taskHtml = '';
                            response.data.forEach(function(task, index) {
                                const statusBadge = task.status === 'pending' ? 'bg-warning' : 
                                                   (task.status === 'in progress' ? 'bg-info' : 'bg-success');
                                const overdueBadge = task.is_overdue ? '<span class="badge bg-danger ms-2">Overdue</span>' : '';
                                
                                taskHtml += `
                                    <a href="#" class="list-group-item list-group-item-action py-2" style="font-size: 0.9rem;">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>${task.title}</strong><br>
                                                <small class="text-muted">${task.description ? task.description.substring(0, 50) + '...' : '-'}</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge ${statusBadge}">${task.status}</span>
                                                ${overdueBadge}
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            Deadline: ${task.due_date} | Dibuat: ${task.created_at}
                                        </small>
                                    </a>
                                `;
                            });
                            
                            $('#existingTasksList').html(taskHtml);
                            $('#taskListContainer').show();
                        } else {
                            $('#taskListContainer').hide();
                        }
                    },
                    error: function() {
                        $('#taskListContainer').hide();
                    }
                });
            }

            // Function untuk load students by bidang for Dashboard
            function loadStudentsByBidangForDashboard() {
                const bidang = $('#taskBidangFilter').val();
                const studentSelect = $('#taskStudent');
                
                fetch(`/api/students-by-bidang?bidang=${bidang}`)
                    .then(response => response.json())
                    .then(data => {
                        studentSelect.html('<option value="">-- Pilih Siswa --</option>');
                        data.students.forEach(student => {
                            const option = document.createElement('option');
                            option.value = student.id_sertifikat;
                            option.textContent = `${student.nama_lengkap} (${student.bidang})`;
                            studentSelect.append(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading students:', error);
                    });
            }

            // --- Event listener untuk tombol 'Tetapkan Tugas' di modal assignTaskModal ---
            submitAssignTaskBtn.on('click', function() {
                const sertifikatPklId = $('#taskStudentSertifikatId').val() || $('#taskStudent').val();
                const title = taskTitle.val();
                const description = taskDescription.val();
                const dueDate = taskDueDate.val();
                const status = taskStatus.val();

                if (!title.trim()) {
                    assignTaskAlert.text('Judul tugas tidak boleh kosong.').removeClass('alert-success alert-info').addClass('alert-danger').show();
                    return;
                }

                if (!sertifikatPklId) {
                    assignTaskAlert.text('Silakan pilih siswa terlebih dahulu.').removeClass('alert-success alert-info').addClass('alert-danger').show();
                    return;
                }

                assignTaskAlert.text('Menetapkan tugas...').removeClass('alert-danger alert-success').addClass('alert-info').show();
                submitAssignTaskBtn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('tasks.assign') }}",
                    method: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        sertifikat_pkl_id: sertifikatPklId,
                        title: title,
                        description: description,
                        due_date: dueDate,
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            assignTaskAlert.text(response.message).removeClass('alert-info alert-danger').addClass('alert-success').show();
                            
                            // Clear form
                            taskTitle.val('');
                            taskDescription.val('');
                            taskDueDate.val('');
                            
                            // Delay close modal untuk user melihat success message
                            setTimeout(function() {
                                assignTaskModal.modal('hide');
                                // Reset modal state
                                assignTaskAlert.hide().removeClass('alert-success alert-danger alert-info');
                            }, 1500);
                        } else {
                            assignTaskAlert.text('Gagal menetapkan tugas: ' + (response.message || 'Terjadi kesalahan.')).removeClass('alert-info alert-success').addClass('alert-danger').show();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        let errorMessage = 'Gagal menetapkan tugas. Terjadi kesalahan jaringan atau server.';
                        if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                            errorMessage = jqXHR.responseJSON.message;
                        } else if (jqXHR.status === 422) {
                            const errors = jqXHR.responseJSON.errors;
                            errorMessage = 'Validasi Gagal: ';
                            for (const key in errors) {
                                errorMessage += errors[key][0] + ' ';
                            }
                        }
                        assignTaskAlert.text(errorMessage).removeClass('alert-info alert-success').addClass('alert-danger').show();
                        console.error('Error assigning task:', textStatus, errorThrown, jqXHR.responseText, jqXHR);
                    },
                    complete: function() {
                        submitAssignTaskBtn.prop('disabled', false);
                    }
                });
            });
        });
                  
            // Load count pengajuan pending
            function loadPendingPengajuanCount() {
                $.ajax({
                    url: "{{ route('api.pengajuan-pending-count') }}",
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#pendingPengajuanCount').text(response.count);
                        }
                    },
                    error: function() {
                        console.log('Gagal load pending count');
                    }
                });
            }
            
            // Call pada page load
            loadPendingPengajuanCount();
            // Refresh setiap 30 detik
            setInterval(loadPendingPengajuanCount, 30000);
</script>
@endsection