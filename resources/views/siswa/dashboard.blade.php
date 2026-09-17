@extends('layouts.app')

@section('title', 'Dashboard Siswa SIMGANG')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Di bagian <head> untuk CSS, atau di bagian bawah <body> untuk JS -->
<!-- Bootstrap CSS (jika belum ada) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome CSS (jika belum ada) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Bootstrap JS Bundle (HARUS ADA dan dimuat setelah CSS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<style>
    
    /* Tambahan CSS untuk tampilan pengumuman */
    .announcements-section {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .announcements-section .section-header {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .announcements-section .section-header i {
        color: #4f46e5; /* Warna ikon sesuai tema */
    }

    /* Styling untuk grid pengumuman */
    .announcements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Sesuaikan lebar kolom */
        gap: 1.5rem; /* Jarak antar kartu pengumuman */
    }

    .announcement-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        transition: all 0.2s ease-in-out;
    }

    .announcement-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }

    .announcement-card .card-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: #1f2937;
        margin-bottom: 0.75rem;
    }

    .announcement-card .badge {
        display: inline-block;
        padding: 0.3em 0.6em;
        font-size: 0.75em;
        font-weight: 600;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.375rem;
        margin-bottom: 0.75rem;
        background-color: #6c757d; /* Default secondary */
    }

    .announcement-card .card-content {
        font-size: 0.95rem;
        color: #374151;
        line-height: 1.5;
        margin-bottom: 1rem;
        flex-grow: 1; /* Memastikan konten mengisi ruang yang tersedia */
    }

    .announcement-card .card-date {
        font-size: 0.85rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: auto; /* Mendorong tanggal ke bawah */
    }

    .empty-state {
        text-align: center;
        padding: 3rem 0;
        color: #94a3b8;
    }

    .empty-state i {
        color: #cbd5e1;
        margin-bottom: 1rem;
        font-size: 4rem;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-size: 1rem;
    }

    /* Styling untuk modal pengumuman */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 1.5rem;
        background-color: #f9fafb;
        border-radius: 12px 12px 0 0;
    }

    .modal-title {
        font-weight: 600;
        color: #1f2937;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        background-color: #f9fafb;
        border-radius: 0 0 12px 12px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .announcements-grid {
            grid-template-columns: 1fr; /* Satu kolom di layar kecil */
        }
    }
    /*data*/
    /* Statistics Section */
.chart-container {
    padding: 25px;
}

.monthly-summary {
    margin-bottom: 30px;
}

.summary-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.summary-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 18px;
}

.month-selector {
    display: flex;
    align-items: center;
    gap: 15px;
}

.month-btn {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
}

.month-btn:hover {
    background: #e9ecef;
}

#currentMonth {
    font-weight: 600;
    color: #2c3e50;
    min-width: 120px;
    text-align: center;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 25px;
}

.summary-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    border-left: 4px solid;
}

.summary-total { border-left-color: #6c757d; }
.summary-present { border-left-color: #28a745; }
.summary-absent { border-left-color: #dc3545; }
.summary-permission { border-left-color: #ffc107; }

.summary-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    color: white;
}

.summary-total .summary-icon { background: #6c757d; }
.summary-present .summary-icon { background: #28a745; }
.summary-absent .summary-icon { background: #dc3545; }
.summary-permission .summary-icon { background: #ffc107; }

.summary-number {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 5px;
}

.summary-label {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.summary-percentage {
    font-size: 14px;
    font-weight: 600;
    margin-top: 5px;
}

.summary-present .summary-percentage { color: #28a745; }
.summary-absent .summary-percentage { color: #dc3545; }
.summary-permission .summary-percentage { color: #ffc107; }

.chart-wrapper {
    height: 300px;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Attendance Section */
.attendance-container {
    padding: 25px;
}

.current-time {
    text-align: center;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    margin-bottom: 25px;
    font-size: 18px;
    font-weight: 600;
}

.attendance-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 25px;
}

.stat-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}

.stat-hadir { border-left: 4px solid #28a745; }
.stat-alpha { border-left: 4px solid #dc3545; }
.stat-izin { border-left: 4px solid #ffc107; }

.stat-number {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-hadir .stat-number { color: #28a745; }
.stat-alpha .stat-number { color: #dc3545; }
.stat-izin .stat-number { color: #ffc107; }

.stat-label {
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
}

.attendance-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.attendance-date {
    font-weight: 600;
    color: #2c3e50;
}

.status-badge {
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: #6c757d;
    color: white;
    display: flex;
    align-items: center;
    gap: 5px;
}

.attendance-form {
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
}

.btn-group {
    display: flex;
    gap: 15px;
}

.btn {
    flex: 1;
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-primary {
    background: #28a745;
    color: white;
}

.btn-primary:hover {
    background: #218838;
}

.btn-warning {
    background: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background: #e0a800;
}

.attendance-history {
    border-top: 1px solid #e9ecef;
    padding-top: 25px;
}

.history-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.history-list {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    min-height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-style: italic;
}

/* Announcements Section */
.announcements-section {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: 25px;
    margin-top: 30px;
}

.section-header {
    color: #2c3e50;
    margin-bottom: 25px;
    font-size: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.empty-state {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

.announcements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.announcement-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
    border-left: 4px solid #667eea;
}

.announcement-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.card-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 10px;
}

.badge {
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 15px;
    background: #667eea;
    color: white;
    margin-bottom: 10px;
    display: inline-block;
}

.card-content {
    color: #6c757d;
    line-height: 1.5;
    margin-bottom: 15px;
}

.card-date {
    font-size: 12px;
    color: #adb5bd;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Tambahan CSS untuk tampilan pengumuman */
.announcements-section {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
    margin-top: 2rem;
}

.announcements-section .section-header {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.announcements-section .section-header i {
    color: #4f46e5; /* Warna ikon sesuai tema */
}

 /* Styling Umum untuk Dashboard */
 body 
        .content-title .icon { margin-right: 10px; color: #007bff; }
        .content-action .content-action-link { text-decoration: none; background-color: #007bff; color: white; padding: 8px 15px; border-radius: 5px; font-size: 0.9em; display: flex; align-items: center; transition: background-color 0.3s ease; }
        .content-action .content-action-link:hover { background-color: #0056b3; }
        .content-action .content-action-link span { margin-right: 5px; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .modal-header h2 { margin: 0; font-size: 1.5em; }
        .close-button { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; line-height: 1; }
        .close-button:hover, .close-button:focus { color: black; text-decoration: none; cursor: pointer; }
        .attendance-list table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .attendance-list th, .attendance-list td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .attendance-list th { background-color: #f2f2f2; font-weight: bold; color: #555; }
        .attendance-list tr:nth-child(even) { background-color: #f9f9f9; }
        .attendance-list tr:hover { background-color: #f1f1f1; }
        .content-action-link 
{
    display: inline-flex; /* Memungkinkan item di dalamnya diatur dengan flexbox */
    align-items: center;  /* Mengatur vertikal tengah */
    justify-content: center; /* Mengatur horizontal tengah */
    padding: 8px 15px; /* <--- SESUAIKAN PADDING UNTUK UKURAN LEBIH KECIL */
    font-size: 0.9em;  /* <--- SESUAIKAN UKURAN FONT */
    background-color: #007bff; /* Warna biru */
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    cursor: pointer;
    gap: 8px; /* Jarak antara teks dan ikon */
    height: 35px; /* <--- TAMBAHKAN HEIGHT JIKA PERLU KONTROL LEBIH BAIK */
}

.content-action-link:hover {
    background-color: #0056b3; /* Warna biru lebih gelap saat hover */
}

/* Jika Anda memiliki ikon di dalam tombol, pastikan ukurannya juga sesuai */
.content-action-link .fas {
    font-size: 0.8em; /* Ukuran ikon, bisa disesuaikan */
}

/* Optional: jika Anda ingin tombolnya hanya selebar isinya (bukan full width) */
.content-action {
    /* Jika .content-action adalah parent dari tombol, pastikan tidak ada width: 100% atau display: block yang berlebihan */
    display: flex; /* Atau block, sesuai layout Anda */
    justify-content: flex-end; /* Posisikan tombol ke kanan jika diinginkan */
    margin-top: 15px; /* Jarak dari elemen di atasnya */
}
/* ========================================================= */
/* --- CSS Khusus untuk Riwayat 5 Hari Terakhir (Dashboard Utama) --- */
/* ========================================================= */

.recent-attendance-section {
    background-color: #f9fbfe; /* Latar belakang sangat terang */
    border: 1px solid #e0e6ed; /* Border tipis */
    padding: 25px; /* Padding di sekitar konten */
    border-radius: 10px; /* Sudut membulat */
    box-shadow: 0 2px 8px rgba(0,0,0,0.05); /* Sedikit bayangan */
    margin-bottom: 25px; /* Jarak dari elemen bawahnya */
}

.recent-attendance-section h3 {
    margin-top: 0; /* Hapus margin atas default */
    color: #333; /* Warna teks gelap */
    font-size: 1.4em; /* Ukuran font judul */
    display: flex; /* Gunakan flexbox untuk ikon dan teks */
    align-items: center; /* Sejajarkan vertikal ikon dan teks */
    gap: 10px; /* Jarak antara ikon dan teks */
    padding-bottom: 12px; /* Padding bawah judul */
    margin-bottom: 20px; /* Jarak dari daftar riwayat */
    border-bottom: 1px solid #e9ecef; /* Garis bawah judul */
}

.recent-attendance-section h3 .fas {
    color: #6a11cb; /* Warna ikon riwayat */
    font-size: 1.1em; /* Ukuran ikon sedikit lebih besar dari teks */
}

/* Kontainer untuk daftar item riwayat */
#recentAttendanceList {
    display: flex; /* Gunakan flexbox untuk daftar */
    flex-direction: column; /* Tata letak item secara vertikal */
    gap: 12px; /* Jarak antar setiap item riwayat */
}

/* Gaya untuk setiap item riwayat individual */
.recent-attendance-item {
    display: grid; /* Gunakan Grid untuk tata letak kolom yang responsif */
    grid-template-columns: 2.5fr 1.2fr 2fr 1fr; /* Definisi kolom: Tanggal | Status | Keterangan | Waktu */
    align-items: center; /* Sejajarkan item di tengah secara vertikal */
    gap: 15px; /* Jarak antar kolom */
    padding: 12px 15px; /* Padding di dalam setiap item */
    border-bottom: 1px dashed #e0e0e0; /* Garis putus-putus antar item */
    font-size: 0.95em; /* Ukuran font item */
    background-color: #ffffff; /* Latar belakang item */
    border-radius: 8px; /* Sudut membulat item */
    transition: background-color 0.2s ease, transform 0.1s ease; /* Efek transisi saat hover */
}

.recent-attendance-item:last-child {
    border-bottom: none; /* Hapus border bawah untuk item terakhir */
}

.recent-attendance-item:hover {
    background-color: #f5f5f5; /* Warna latar saat hover */
    transform: translateY(-1px); /* Sedikit efek naik saat hover */
}

/* Gaya untuk masing-masing bagian dalam item riwayat */
.recent-item-date {
    font-weight: 600; /* Tebal untuk tanggal */
    color: #34495e; /* Warna teks tanggal */
}

.recent-item-status {
    text-align: center; /* Pusatkan teks status */
    font-weight: 700; /* Sangat tebal untuk status */
    display: flex;
    align-items: center;
    justify-content: center; /* Pusatkan ikon dan teks */
    gap: 5px; /* Jarak antara ikon dan teks status */
}

.recent-item-status .fas {
    font-size: 0.9em; /* Ukuran ikon status */
}

/* Warna untuk status badge di riwayat singkat */
.status-hadir { color: #28a745; } /* Hijau */
.status-izin { color: #ffc107; } /* Kuning */
.status-alpha { color: #dc3545; } /* Merah */
@media (max-width: 768px) {
    .recent-attendance-section {
        padding: 20px; /* Kurangi padding di layar kecil */
    }
    .recent-attendance-section h3 {
        font-size: 1.2em; /* Ukuran judul lebih kecil */
        flex-direction: column; /* Tumpuk ikon dan teks judul */
        align-items: flex-start; /* Sejajarkan ke kiri */
    }
    .recent-attendance-item {
        grid-template-columns: 1fr; /* Tumpuk semua elemen secara vertikal */
        row-gap: 8px; /* Jarak antar baris saat ditumpuk */
        padding: 12px; /* Sesuaikan padding */
    }
    .recent-item-date,
    .recent-item-status,
    .recent-item-keterangan,
    .recent-item-time {
        grid-column: span 1; /* Pastikan mengambil satu kolom penuh */
        text-align: left !important; /* Semua teks sejajar kiri */
    }
    .recent-item-status {
        justify-content: flex-start; /* Sejajarkan status ke kiri */
    }
}

@media (max-width: 480px) {
    .recent-attendance-section {
        padding: 15px; /* Padding lebih kecil lagi */
    }
    .recent-attendance-item {
        font-size: 0.85em; /* Ukuran font lebih kecil */
    }
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
    gap: 10px;
}
.calendar-title {
    font-size: 1rem;
    font-weight: 700;
    color: #2c3e50;
    display: flex;
    align-items: center;
}
.calendar-nav button {
    background: none;
    border: none;
    border-radius: 50%;
    width: 26px;
    height: 26px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}
.calendar-nav button:hover {
    background: #667eea;
    color: white;
}
.calendar-table {
    width: 100%;
    table-layout: fixed;
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
    color: #667eea;
    font-weight: 700;
}
.calendar-table td.selected {
    background-color: #667eea;
    color: white;
}
.calendar-table td.has-interns::after {
    content: '';
    display: block;
    width: 6px;
    height: 6px;
    background-color: #f59e0b;
    border-radius: 50%;
    position: absolute;
    bottom: 4px;
    left: 50%;
    transform: translateX(-50%);
}
.calendar-table td.selected.has-interns::after {
    background-color: #f59e0b;
}

.detail-header {
    padding-bottom: 15px;
    border-bottom: 2px solid #e2e8f0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.stat-item i {
    font-size: 24px;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
}

.stat-label {
    font-size: 12px;
    color: #6c757d;
}

.info-section {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.badge-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.badge-container .badge {
    font-size: 0.85rem;
    padding: 8px 12px;
}

/* Styling Khusus Modal Riwayat Absen Magang */
.attendance-modal-overlay {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(6px);
    align-items: center;
    justify-content: center;
}

.attendance-modal-overlay.show {
    display: flex !important;
}

.attendance-modal-card {
    background: #ffffff;
    border-radius: 18px;
    width: 92%;
    max-width: 680px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.4);
    overflow: hidden;
    position: relative;
}

.attendance-modal-header {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    padding: 16px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: move;
    user-select: none;
}

.modal-icon-bg {
    width: 38px;
    height: 38px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.attendance-modal-close {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: white;
    font-size: 1.5rem;
    line-height: 1;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.attendance-modal-close:hover {
    background: #ef4444;
    color: white;
    transform: rotate(90deg);
}

.attendance-modal-body {
    padding: 20px 24px;
    background: #ffffff;
}

.custom-attendance-table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}

.custom-attendance-table thead th {
    background-color: #f8fafc;
    color: #475569;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    border-bottom: 2px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 2;
}

.custom-attendance-table tbody td {
    padding: 14px 16px;
    font-size: 0.9rem;
    color: #1e293b;
    border-bottom: 1px solid #f1f5f9;
}

.custom-attendance-table tbody tr:hover {
    background-color: #f8fafc !important;
}

/* Status Pill Badges */
.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 600;
}
.badge-status.status-present {
    background-color: #d1fae5;
    color: #065f46;
}
.badge-status.status-permission {
    background-color: #fef3c7;
    color: #92400e;
}
.badge-status.status-absent {
    background-color: #fee2e2;
    color: #991b1b;
}
.badge-status.status-pending {
    background-color: #f1f5f9;
    color: #64748b;
}
</style>
@endsection

@section('content')
<main>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="dashboard-title">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard SIMGANG
            </div>
        </div>
        
        <div class="dashboard-welcome">
            <div class="welcome-text">
                <h2>Selamat Datang, {{ ucwords(strtolower(Auth::user()->nama ?? Auth::user()->name ?? 'Siswa')) }}!</h2>
                <p>Pantau dan kelola proses magang dengan mudah. Berikut adalah ringkasan data magang terkini Dinas Komunikasi dan Informatika Kabupaten Cilacap.</p>
            </div>
            <div class="welcome-date" style="text-align: right;">
                <div style="font-size: 1.1em; font-weight: 600; color: #ffffff;">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</div>
                <div id="realtime-clock" style="font-size: 1.4em; font-weight: 700; color: #ffffff; margin-top: 5px;"></div>
            </div>
        </div>
        <head>
    @auth
        <meta name="user-id" content="{{ $idSertifikat ?? ($sertifikat?->id_sertifikat ?? (Auth::user()->id_sertifikat ?? Auth::id())) }}">
    @else
        <meta name="user-id" content=""> 
    @endauth

</head>
        <div class="side-by-side">
                <!-- Kolom Kiri: Statistik Presensi & Pengumuman Terbaru -->
                <div class="d-flex flex-column gap-4">
                    <!-- Statistik Presensi Siswa (Ringkasan Bulan Ini) -->
                    <div class="dashboard-content p-4">
                        <div class="calendar-header border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                            <div class="calendar-title fw-bold d-flex align-items-center">
                                <div class="icon shadow-sm d-inline-flex align-items-center justify-content-center me-2 text-white" style="background-color: #667eea; width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;">
                                    <i class="fas fa-chart-line" style="font-size: 13px;"></i>
                                </div>
                                <span style="font-size: 0.95rem; white-space: nowrap;">Statistik Presensi</span>
                            </div>
                            <div class="month-selector">
                                <span id="currentMonthDisplay" style="font-weight: 600; color: #667eea; font-size: 0.85rem;"></span>
                            </div>
                        </div>
                        <div class="chart-container p-0" style="min-height: auto;">
                            <!-- Monthly Summary -->
                            <div class="monthly-summary mb-0">
                                <div class="summary-header mb-2">
                                    <h3 style="font-size: 0.9rem; font-weight: 600; color: #374151;">Ringkasan Bulan Ini</h3>
                                </div>

                                <div class="summary-grid">
                                    <div class="summary-card summary-total">
                                        <div class="summary-icon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div class="summary-info">
                                            <div class="summary-number" id="totalDays">0</div>
                                            <div class="summary-label">Total Hari</div>
                                        </div>
                                    </div>

                                    <div class="summary-card summary-present">
                                        <div class="summary-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="summary-info">
                                            <div class="summary-number" id="presentDays">0</div>
                                            <div class="summary-label">Hadir</div>
                                            <div class="summary-percentage" id="presentPercentage">0%</div>
                                        </div>
                                    </div>

                                    <div class="summary-card summary-absent">
                                        <div class="summary-icon">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <div class="summary-info">
                                            <div class="summary-number" id="absentDays">0</div>
                                            <div class="summary-label">Alpha</div>
                                            <div class="summary-percentage" id="absentPercentage">0%</div>
                                        </div>
                                    </div>

                                    <div class="summary-card summary-permission">
                                        <div class="summary-icon">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="summary-info">
                                            <div class="summary-number" id="permissionDays">0</div>
                                            <div class="summary-label">Izin</div>
                                            <div class="summary-percentage" id="permissionPercentage">0%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pengumuman Terbaru (Tepat di Bawah Statistik Presensi) -->
                    <div class="dashboard-content p-4">
                        <div class="calendar-header border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                            <div class="calendar-title fw-bold d-flex align-items-center">
                                <div class="icon shadow-sm d-inline-flex align-items-center justify-content-center me-2 text-white" style="background-color: #667eea; width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;">
                                    <i class="fas fa-bullhorn" style="font-size: 13px;"></i>
                                </div>
                                <span style="font-size: 0.95rem; white-space: nowrap;">Pengumuman Terbaru</span>
                            </div>
                        </div>
                        @if($pengumumans->isEmpty())
                            <div class="empty-state my-auto text-center py-3">
                                <i class="fas fa-bell-slash text-muted mb-2" style="font-size: 1.8rem;"></i>
                                <h5 class="fw-bold text-dark fs-6 mb-1">Belum ada pengumuman untuk Anda</h5>
                                <p class="text-muted small mb-0">Mohon cek kembali nanti.</p>
                            </div>
                        @else
                            <div class="announcements-grid" style="display: flex; flex-direction: column; gap: 10px; max-height: 250px; overflow-y: auto; padding-right: 5px;">
                                @foreach($pengumumans as $pengumuman)
                                    <div class="announcement-card mb-0" data-bs-toggle="modal" data-bs-target="#announcementModal{{ $pengumuman->id }}" style="cursor: pointer; padding: 12px 15px; border-radius: 10px; background: #f8f9fa; border-left: 4px solid #667eea;">
                                        <div class="card-title fw-bold mb-1" style="font-size: 0.95rem; color: #2c3e50;">{{ $pengumuman->title }}</div>
                                        @if($pengumuman->type)
                                            <span class="badge bg-primary mb-1" style="font-size: 0.7rem; padding: 3px 8px; border-radius: 10px;">{{ $pengumuman->type }}</span>
                                        @endif
                                        <div class="card-content text-muted small mb-1" style="line-height: 1.3; font-size: 0.82rem;">
                                            {{ Str::limit($pengumuman->content, 80) }}
                                        </div>
                                        <div class="card-date text-muted" style="font-size: 0.78rem;">
                                            <i class="fas fa-calendar me-1"></i> {{ $pengumuman->published_at ? $pengumuman->published_at->locale('id')->translatedFormat('d F Y') : ($pengumuman->created_at ? $pengumuman->created_at->locale('id')->translatedFormat('d F Y') : '-') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Presensi Harian (Existing content) -->
                <div class="dashboard-content d-flex flex-column justify-content-between p-4">
                    <div>
                        {{-- Bagian Header Dashboard --}}
                        <div class="calendar-header border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                            <div class="calendar-title fw-bold d-flex align-items-center">
                                <div class="icon shadow-sm d-inline-flex align-items-center justify-content-center me-2 text-white" style="background-color: #667eea; width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;">
                                    <i class="fas fa-user-check" style="font-size: 13px;"></i>
                                </div>
                                <span style="font-size: 0.95rem; white-space: nowrap;">Presensi Harian</span>
                            </div>
                            <div class="content-action m-0">
                                <a href="#" class="content-action-link btn btn-primary btn-sm rounded-pill px-3 py-1 text-white shadow-sm" id="showAttendancePopup" style="font-size: 0.8rem; height: auto; text-decoration: none;">
                                    <span>Riwayat Lengkap</span>
                                    <i class="fas fa-history ms-1"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Tempat penyimpanan ID Sertifikat untuk JavaScript --}}
                        @php
                            $userIdSertifikat = $idSertifikat ?? ($sertifikat?->id_sertifikat ?? (Auth::user()->id_sertifikat ?? Auth::id()));
                        @endphp
                        @if(!empty($userIdSertifikat))
                            <div id="studentData" data-id-sertifikat="{{ $userIdSertifikat }}"></div>
                        @else
                            <div id="studentData" data-id-sertifikat="{{ Auth::id() }}"></div>
                        @endif

                        {{-- Tampilan Waktu Saat Ini --}}
                        <div class="current-time" id="currentTime" style="margin-bottom: 15px;">
                            <i class="fas fa-clock"></i>
                            <span id="timeDisplay"></span>
                        </div>

                        {{-- Header Status Presensi Hari Ini (Tanggal & Status) --}}
                        <div class="attendance-header" style="margin-bottom: 15px;">
                            <div class="attendance-date" id="todayDate"></div>
                            <div class="attendance-status">
                                <div class="status-badge" id="todayStatus">
                                    <i class="fas fa-question-circle"></i>
                                    Memuat status...
                                </div>
                            </div>
                        </div>
                        
                        {{-- Form Presensi --}}
                        <div class="attendance-form mb-0" id="attendanceForm">
                            <div class="form-group mb-3">
                                <label class="form-label" for="location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Lokasi Presensi
                                </label>
                                <input type="text" class="form-control" id="location" placeholder="Mendapatkan lokasi..." readonly>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label" for="notes">
                                    <i class="fas fa-comment"></i>
                                    Keterangan (Opsional)
                                </label>
                                <textarea class="form-control" id="notes" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group mt-3" style="position: relative; z-index: 1000;">
                        <button type="button" class="btn btn-primary" id="btnHadir" data-status="Hadir" onclick="markAttendance('Hadir')" style="pointer-events: auto !important; cursor: pointer !important;">
                            <i class="fas fa-check"></i>
                            Hadir
                        </button>
                        <button type="button" class="btn btn-warning" id="btnIzin" data-status="Izin" onclick="handleIzinClick(event)" style="pointer-events: auto !important; cursor: pointer !important;">
                            <i class="fas fa-exclamation-triangle"></i>
                            Izin
                        </button>
                    </div>
                    <div id="attendanceNoteMessage" class="mt-3 p-2 rounded text-center fw-bold alert mb-0" style="display: none; font-size: 0.9rem;"></div>
                </div> {{-- End of dashboard-content Presensi Harian --}}
    </div> {{-- End of side-by-side section --}}

    <!-- Kalender Magang untuk Siswa -->
    <div class="row mt-4 mb-4">
        <!-- Kalender -->
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="dashboard-content calendar-container shadow-sm border-0 rounded-4 h-100 d-flex flex-column justify-content-between overflow-hidden">
                <div>
                    <div class="calendar-header border-bottom pb-3 mb-3 d-flex justify-content-between align-items-start flex-wrap gap-2" style="height: 88px; flex-shrink: 0;">
                        <div class="calendar-title fw-bold d-flex align-items-center me-auto">
                            <div class="icon shadow-sm d-inline-flex align-items-center justify-content-center me-2 text-white" style="background-color: #667eea; width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;">
                                <i class="fas fa-calendar-alt" style="font-size: 13px;"></i>
                            </div>
                            <span style="font-size: 0.95rem; white-space: nowrap;">Kalender Magang</span>
                        </div>
                        <div class="calendar-nav bg-light rounded-pill px-2 py-1 shadow-sm d-inline-flex align-items-center gap-1 ms-auto">
                            <button id="prevMonth" class="btn btn-sm text-primary rounded-circle p-0" title="Bulan Sebelumnya"><i class="fas fa-chevron-left" style="font-size: 11px;"></i></button>
                            <span id="calendarMonthYear" class="mx-1 fw-bold text-dark" style="font-size: 0.82rem; white-space: nowrap;"></span>
                            <button id="nextMonth" class="btn btn-sm text-primary rounded-circle p-0" title="Bulan Berikutnya"><i class="fas fa-chevron-right" style="font-size: 11px;"></i></button>
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
                </div>

                <!-- Keterangan Warna Kalender (Legend) -->
                <div class="calendar-legend mt-3 pt-2 border-top d-flex align-items-center justify-content-center flex-nowrap gap-3 text-nowrap" style="font-size: 12px;">
                    <div class="d-flex align-items-center gap-1">
                        <span style="display: inline-block; width: 8px; height: 8px; background-color: #f59e0b; border-radius: 50%; flex-shrink: 0;"></span>
                        <span class="text-secondary font-weight-500">Ada Peserta</span>
                    </div>
                    <div class="d-flex align-items-center gap-1 ms-2">
                        <span style="display: inline-block; width: 12px; height: 12px; background-color: #667eea; border-radius: 3px; flex-shrink: 0;"></span>
                        <span class="text-secondary font-weight-500">Tanggal Dipilih</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Panel Informasi Kalender -->
        <div class="col-lg-6">
            <div class="dashboard-content calendar-container shadow-sm border-0 rounded-4 h-100 d-flex flex-column justify-content-between overflow-hidden" style="min-height: 450px;">
                
                <!-- Informasi Bulan (Default) -->
                <div id="monthDetailEmpty" class="flex-grow-1 d-flex flex-column justify-content-between">
                    <div class="calendar-header border-bottom pb-3 mb-3 d-flex align-items-start" style="height: 88px; flex-shrink: 0;">
                        <div class="calendar-title fw-bold d-flex align-items-center">
                            <div class="icon shadow-sm d-inline-flex align-items-center justify-content-center me-2 text-white" style="background-color: #667eea; width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;">
                                <i class="fas fa-calendar-alt" style="font-size: 13px;"></i>
                            </div>
                            <span style="font-size: 0.95rem; white-space: nowrap;">Siswa Magang Bulan <span id="monthYearDisplay"></span></span>
                        </div>
                    </div>
                    
                    <div class="row g-3 flex-grow-1 align-items-start">
                        <!-- Stat Cards (Kiri) -->
                        <div class="col-md-5 d-flex flex-column gap-3">
                            <div style="text-align: center; padding: 16px 12px; background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border-radius: 12px; border-left: 4px solid #667eea;">
                                <div style="font-size: 28px; font-weight: 700; color: #667eea; margin-bottom: 2px;" id="monthTotalPeserta">0</div>
                                <div style="font-size: 12px; color: #6c757d; font-weight: 500;">Total Siswa</div>
                            </div>
                            <div style="text-align: center; padding: 16px 12px; background: linear-gradient(135deg, #28a74515 0%, #20c99715 100%); border-radius: 12px; border-left: 4px solid #28a745;">
                                <div style="font-size: 28px; font-weight: 700; color: #28a745; margin-bottom: 2px;" id="monthTotalBidang">0</div>
                                <div style="font-size: 13px; color: #6c757d; font-weight: 500;">Total Bidang</div>
                            </div>
                        </div>
                        <!-- List Bidang (Kanan) -->
                        <div class="col-md-7 d-flex flex-column justify-content-start">
                            <h6 class="fw-bold mb-3" style="font-size: 14px; color: #2c3e50;">
                                <i class="fas fa-briefcase me-2" style="color: #667eea;"></i>Bidang Penempatan
                            </h6>
                            <div id="monthBidangList" style="display: flex; flex-direction: column; gap: 8px;"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Loading -->
                <div id="monthDetailLoading" class="text-center py-5 flex-grow-1 flex-column justify-content-center d-none">
                    <div class="spinner-border text-primary mx-auto mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted">Memuat data...</p>
                </div>
                
                <!-- Detail Tanggal Dipilih -->
                <div id="dayDetailData" style="display:none;" class="flex-grow-1 flex-column justify-content-between">
                    <div class="calendar-header border-bottom pb-3 mb-3 d-flex align-items-start" style="height: 88px; flex-shrink: 0;">
                        <div class="calendar-title fw-bold d-flex align-items-center">
                            <div class="icon shadow-sm d-inline-flex align-items-center justify-content-center me-2 text-white" style="background-color: #667eea; width: 30px; height: 30px; border-radius: 8px; flex-shrink: 0;">
                                <i class="fas fa-calendar-day" style="font-size: 13px;"></i>
                            </div>
                            <div>
                                <span id="selectedDayDisplay" class="text-dark fw-bold" style="font-size: 0.95rem;"></span>
                                <div class="text-muted small font-weight-normal" style="font-size: 12px; margin-top: 1px;">Detail siswa magang pada tanggal tersebut</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3 flex-grow-1 align-items-start">
                        <!-- Stat Cards (Kiri) -->
                        <div class="col-md-5 d-flex flex-column gap-3">
                            <div style="text-align: center; padding: 16px 12px; background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border-radius: 12px; border-left: 4px solid #667eea;">
                                <div style="font-size: 28px; font-weight: 700; color: #667eea; margin-bottom: 2px;" id="dayTotalPeserta">0</div>
                                <div style="font-size: 12px; color: #6c757d; font-weight: 500;">Peserta Aktif</div>
                            </div>
                            <div style="text-align: center; padding: 16px 12px; background: linear-gradient(135deg, #28a74515 0%, #20c99715 100%); border-radius: 12px; border-left: 4px solid #28a745;">
                                <div style="font-size: 28px; font-weight: 700; color: #28a745; margin-bottom: 2px;" id="dayTotalBidang">0</div>
                                <div style="font-size: 13px; color: #6c757d; font-weight: 500;">Bidang Aktif</div>
                            </div>
                        </div>
                        <!-- List Bidang (Kanan) -->
                        <div class="col-md-7 d-flex flex-column justify-content-start">
                            <h6 class="fw-bold mb-3" style="font-size: 14px; color: #2c3e50;">
                                <i class="fas fa-briefcase me-2" style="color: #667eea;"></i>Bidang Aktif
                            </h6>
                            <div id="dayBidangList" style="display: flex; flex-direction: column; gap: 8px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Tanggal dipilih tanpa peserta -->
                <div id="dayDetailEmpty" style="display:none;" class="flex-grow-1 flex-column justify-content-center align-items-center text-center px-4">
                    <div class="icon shadow-sm d-inline-flex align-items-center justify-content-center mb-3 text-white" style="background-color: #667eea; width: 42px; height: 42px; border-radius: 10px;">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <h6 id="emptyDayDisplay" class="fw-bold text-dark mb-2"></h6>
                    <p id="emptyDayMessage" class="text-muted mb-0">Tidak ada peserta magang pada tanggal ini.</p>
                </div>
            </div>
        </div>
    </div>

    </div> {{-- End of dashboard-content --}}

    {{-- Modal Riwayat Absen Magang Lengkap (Movable / Draggable & Centered) --}}
    <div id="attendanceModal" class="attendance-modal-overlay">
        <div class="attendance-modal-card" id="attendanceModalCard">
            <div class="attendance-modal-header" id="attendanceModalHeader">
                <div class="d-flex align-items-center gap-2">
                    <div class="modal-icon-bg">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <div>
                        <h5 class="m-0 fw-bold text-white fs-6">Riwayat Absen Magang</h5>
                        <small class="text-white-50" style="font-size: 0.75rem;">
                            <i class="fas fa-arrows-alt me-1"></i>Geser modal ini dengan kursor
                        </small>
                    </div>
                </div>
                <button type="button" class="attendance-modal-close" id="closeModalBtn" title="Tutup">&times;</button>
            </div>
            <div class="attendance-modal-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="custom-attendance-table">
                        <thead>
                            <tr>
                                <th><i class="far fa-calendar-alt me-1"></i>Tanggal</th>
                                <th><i class="fas fa-info-circle me-1"></i>Status</th>
                                <th><i class="far fa-sticky-note me-1"></i>Keterangan</th>
                                <th><i class="far fa-clock me-1"></i>Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceTableBody">
                            {{-- Data akan dimuat di sini oleh AJAX --}}
                            <tr><td colspan="4" class="text-center text-muted py-4">Klik "Riwayat Lengkap" untuk memuat data.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Message Box -->
    <div id="messageBox" class="message-box"></div>
        
        <!-- Task Summary Widget -->
        @if(isset($taskStats))
        <div class="col-12 mt-4 mb-4">
            <div class="dashboard-content">
                <div class="content-header" style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb; margin-bottom: 20px;">
                    <div class="content-title" style="display: flex; align-items: center; gap: 10px; margin: 0; font-size: 1.1rem; font-weight: 600;">
                        <div class="icon" style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #f3f4f6; border-radius: 8px; color: #667eea;">
                            <i class="fas fa-tasks"></i>
                        </div>
                        Ringkasan Tugas
                    </div>
                    <div>
                        <a href="{{ route('siswa.tasks.index') }}" class="btn btn-sm" style="background: #667eea; color: white; text-decoration: none; padding: 8px 16px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; font-weight: 500;">
                            Lihat Semua <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="attendance-stats" style="margin-top: 20px; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;">
                    <div class="stat-card" style="border-left: 4px solid #6c757d;">
                        <div class="stat-number" style="color: #6c757d;">{{ $taskStats['total'] ?? 0 }}</div>
                        <div class="stat-label">Total Tugas</div>
                    </div>
                    <div class="stat-card" style="border-left: 4px solid #ffc107;">
                        <div class="stat-number" style="color: #ffc107;">{{ $taskStats['pending'] ?? 0 }}</div>
                        <div class="stat-label">Tugas Aktif</div>
                    </div>
                    <div class="stat-card stat-hadir">
                        <div class="stat-number">{{ $taskStats['graded'] ?? 0 }}</div>
                        <div class="stat-label">Tugas Selesai</div>
                    </div>
                    <div class="stat-card" style="border-left: 4px solid #17a2b8;">
                        <div class="stat-number" style="color: #17a2b8;">{{ $taskStats['submitted'] ?? 0 }}</div>
                        <div class="stat-label">Menunggu Penilaian</div>
                    </div>
                </div>
                
                @if(($taskStats['new'] ?? 0) > 0)
                <div class="alert" style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-top: 20px; border-radius: 8px;">
                    <i class="fas fa-bell" style="color: #ffc107;"></i>
                    <strong>Tugas Baru!</strong> Anda memiliki <strong>{{ $taskStats['new'] }}</strong> tugas baru yang belum dikerjakan.
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Card: Penempatan Bidang & Rekan Magang --}}
        @if(isset($sertifikat) && $sertifikat)
        <div class="col-12 mt-4 mb-0">
            <div class="dashboard-content">
                <div class="content-header">
                    <div class="content-title">
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        Penempatan Bidang & Rekan Magang
                    </div>
                </div>
                <div class="mt-3">
                    <div style="background: linear-gradient(135deg, #667eea15, #764ba215); border: 1px solid #667eea30; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div style="background: #667eea; color: white; width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.8rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px;">Bidang Penempatan Anda</div>
                                <div style="font-size: 1.3rem; font-weight: 700; color: #2c3e50;">{{ $sertifikat?->bidang ?? '-' }}</div>
                            </div>
                            <div class="ms-auto text-end">
                                <div style="font-size: 0.8rem; color: #6c757d;">Periode Magang</div>
                                <div style="font-size: 0.9rem; font-weight: 600; color: #495057;">
                                    {{ $sertifikat?->tgl_mulai ? $sertifikat->tgl_mulai->locale('id')->isoFormat('D MMMM YYYY') : '-' }}
                                    &mdash;
                                    {{ $sertifikat?->tgl_selesai ? $sertifikat->tgl_selesai->locale('id')->isoFormat('D MMMM YYYY') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @php $temanMagangList = $temanMagang ?? collect(); @endphp
                    @if($temanMagangList->isEmpty())
                        {{-- Hanya siswa ini sendiri --}}
                        <div style="padding: 16px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #667eea;">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 38px; height: 38px; background: #667eea; color: white; border-radius: 50%; display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #2c3e50;">{{ $sertifikat?->nama_lengkap ?? Auth::user()->name }}</div>
                                    <div style="font-size: 0.82rem; color: #6c757d;">{{ $sertifikat?->jurusan ?? '-' }} &bull; {{ $sertifikat?->asal_sekolah ?? '-' }}</div>
                                </div>
                                <span class="ms-auto badge" style="background:#28a745; color:white; padding: 5px 12px; border-radius: 20px;">Anda</span>
                            </div>
                        </div>
                        <p class="text-muted small mt-3 mb-0"><i class="fas fa-info-circle me-1"></i>Belum ada rekan magang lain di bidang <strong>{{ $sertifikat?->bidang ?? '-' }}</strong> pada periode yang sama.</p>
                    @else
                        <h6 class="fw-bold mb-3" style="color: #495057; font-size: 0.9rem;">
                            <i class="fas fa-users me-2" style="color: #667eea;"></i>
                            {{ $temanMagangList->count() + 1 }} orang di bidang {{ $sertifikat?->bidang ?? '-' }}
                        </h6>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            {{-- Siswa sendiri --}}
                            <div style="padding: 14px 16px; background: linear-gradient(135deg, #667eea15, #764ba215); border-radius: 10px; border-left: 4px solid #667eea; display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; background: #667eea; color: white; border-radius: 50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <div style="font-weight: 700; color: #2c3e50; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $sertifikat?->nama_lengkap ?? Auth::user()->name }}</div>
                                    <div style="font-size: 0.8rem; color: #6c757d;">{{ $sertifikat?->jurusan ?? '-' }} &bull; {{ $sertifikat?->asal_sekolah ?? '-' }}</div>
                                </div>
                                <span style="background:#667eea; color:white; padding: 3px 12px; border-radius: 20px; font-size: 0.78rem; white-space:nowrap;">Anda</span>
                            </div>
                            {{-- Teman magang lainnya --}}
                            @foreach($temanMagangList as $teman)
                            <div style="padding: 14px 16px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #dee2e6; display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; background: #dee2e6; color: #6c757d; border-radius: 50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <div style="font-weight: 600; color: #2c3e50; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $teman->nama_lengkap ?? '-' }}</div>
                                    <div style="font-size: 0.8rem; color: #6c757d;">{{ $teman->jurusan ?? '-' }} &bull; {{ $teman->asal_sekolah ?? '-' }}</div>
                                </div>
                                <span style="background:#e9ecef; color:#495057; padding: 3px 12px; border-radius: 20px; font-size: 0.78rem; white-space:nowrap;">
                                    {{ $teman->tgl_mulai->locale('id')->isoFormat('MMMM YYYY') }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Card: Tugas Terbaru --}}
        @if(isset($recentTasks) && $recentTasks->isNotEmpty())
        <div class="col-12 mt-4 mb-0">
            <div class="dashboard-content">
                <div class="content-header">
                    <div class="content-title">
                        <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                        Tugas Terbaru dari Mentor
                    </div>
                    <a href="{{ route('siswa.tasks.index') }}" class="btn btn-sm" style="background: #667eea; color: white; text-decoration: none; padding: 8px 16px; border-radius: 6px;">
                        <i class="fas fa-arrow-right"></i> Semua Tugas
                    </a>
                </div>
                <div class="mt-3" style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($recentTasks as $task)
                    @php
                        $taskStatus = $task->submissions->first()->status ?? null;
                        $statusColor = match($taskStatus) {
                            'graded'    => '#28a745',
                            'submitted' => '#17a2b8',
                            default     => '#ffc107',
                        };
                        $statusLabel = match($taskStatus) {
                            'graded'    => 'Dinilai',
                            'submitted' => 'Dikirim',
                            default     => 'Belum dikerjakan',
                        };
                    @endphp
                    <div style="padding: 14px 18px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid {{ $statusColor }}; display: flex; align-items: center; gap: 14px;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 600; color: #2c3e50; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $task->title }}</div>
                            @if($task->due_date)
                            <div style="font-size: 0.8rem; color: #6c757d; margin-top: 2px;">
                                <i class="fas fa-clock me-1"></i>Batas: {{ $task->due_date->locale('id')->isoFormat('D MMMM YYYY') }}
                            </div>
                            @endif
                        </div>
                        <span style="background: {{ $statusColor }}20; color: {{ $statusColor }}; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; white-space: nowrap; border: 1px solid {{ $statusColor }}40;">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        


        <!-- Section Gambar Grafik (Di Taroh Paling Bawah) -->
        <div class="col-12 mt-4 mb-4">
            <div class="dashboard-content shadow-sm border-0 rounded-4 p-4" style="background-color: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                <div class="chart-wrapper p-3" style="height: 320px; background: #f8fafc; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>

    </div> 
    
    {{-- Modals untuk Pengumuman Lengkap --}}
    @if(!$pengumumans->isEmpty())
        @foreach($pengumumans as $pengumuman)
            <div class="modal fade" id="announcementModal{{ $pengumuman->id }}" tabindex="-1" aria-labelledby="announcementModalLabel{{ $pengumuman->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="announcementModalLabel{{ $pengumuman->id }}">{{ $pengumuman->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted small">
                                <i class="fas fa-calendar-alt me-1"></i> {{ $pengumuman->published_at ? $pengumuman->published_at->locale('id')->translatedFormat('d F Y H:i') : ($pengumuman->created_at ? $pengumuman->created_at->locale('id')->translatedFormat('d F Y H:i') : '-') }}
                                @if($pengumuman->type)
                                    <span class="badge bg-info ms-2">{{ $pengumuman->type }}</span>
                                @endif
                            </p>
                            <p>{!! nl2br(e($pengumuman->content)) !!}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</main>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set tanggal hari ini dengan format lokal Indonesia (di-handle oleh realtime-clock sekarang)
    setInterval(() => {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const clock = document.getElementById('realtime-clock');
        if(clock) clock.textContent = timeStr;
    }, 1000);
});

// --- Data Presensi & Fungsionalitas Utama ---

// Konfigurasi API
const API_CONFIG = {
    baseUrl: '/attendance',
    endpoints: {
        get: '/get',
        save: '/save',
        getStats: '/stats',
        getHistory: '/history',
        getFullHistory: '/full-history' // Akan menggunakan GET method
    }
};

// Data presensi lokal (cache)
let attendanceData = {};
let currentUserId = null; // id_sertifikat

// Variabel global untuk tanggal dan chart
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();
let attendanceChart; // Untuk chart presensi bulanan

// Nama bulan dalam bahasa Indonesia

// Nama hari dalam bahasa Indonesia
const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
const monthNames = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

// Inisialisasi aplikasi
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000); // Update waktu setiap detik
    getUserLocation(); // Coba dapatkan lokasi saat inisialisasi
    initializeModal(); // Inisialisasi modal riwayat lengkap
});

/**
 * Menginisialisasi aplikasi presensi.
 * Mendapatkan ID pengguna, memuat data, dan memperbarui UI.
 */
async function initializeApp() {
    // Ambil id_sertifikat
    currentUserId = getUserId();
    
    console.log('initializeApp: currentUserId is', currentUserId); // Debugging

    if (!currentUserId || currentUserId === 'null' || currentUserId === 'undefined' || currentUserId.trim() === '') {
        showMessage('ID Sertifikat tidak ditemukan. Silakan login kembali.', 'error');
        console.error('ID Sertifikat tidak valid atau kosong:', currentUserId);
        return; // Hentikan inisialisasi jika ID tidak valid
    }

    showMessage('Memuat data presensi...', 'info');
    
    try {
        await loadAttendanceData(); // Muat data presensi untuk bulan dan tahun saat ini
        updateMonthDisplay();
        await updateStatistics(); // Tunggu statistik selesai dimuat
        await createChart(); // Buat chart setelah statistik siap
        updateTodayAttendance();
        updateAttendanceHistory();
        hideMessage();
    } catch (error) {
        console.error('Error initializing app:', error);
        showMessage('Gagal memuat data presensi. Coba refresh halaman.', 'error');
    }
}

/**
 * Mendapatkan ID pengguna (id_sertifikat) dari berbagai sumber dengan prioritas.
 * Prioritas: Data Attribute (dari Blade) > URL Parameter > Meta Tag > Session Storage > Local Storage.
 */
function getUserId() {
    // 1. Prioritas tertinggi: Data Attribute dari elemen HTML (#studentData) yang di-render Blade
    const studentDataElement = document.getElementById('studentData');
    if (studentDataElement) {
        const fromDataAttr = studentDataElement.dataset.idSertifikat;
        if (fromDataAttr && fromDataAttr.trim() !== '' && fromDataAttr !== 'null' && fromDataAttr !== 'undefined') {
            sessionStorage.setItem('id_sertifikat', fromDataAttr); // Simpan ke session untuk navigasi
            console.log('getUserId: Found from data attribute', fromDataAttr);
            return fromDataAttr;
        }
    }

    // 2. Prioritas kedua: URL parameter (biasanya untuk debugging atau deep linking)
    const urlParams = new URLSearchParams(window.location.search);
    const fromUrl = urlParams.get('id_sertifikat');
    if (fromUrl) {
        sessionStorage.setItem('id_sertifikat', fromUrl);
        console.log('getUserId: Found from URL param', fromUrl);
        return fromUrl;
    }

    // 3. Prioritas ketiga: Meta tag (jika ada)
    const metaTag = document.querySelector('meta[name="user-id"]');
    if (metaTag) {
        const userIdFromMeta = metaTag.getAttribute('content');
        if (userIdFromMeta && userIdFromMeta.trim() !== '' && userIdFromMeta !== 'null' && userIdFromMeta !== 'undefined') {
            sessionStorage.setItem('id_sertifikat', userIdFromMeta);
            console.log('getUserId: Found from meta tag', userIdFromMeta);
            return userIdFromMeta;
        }
    }

    // 4. Prioritas keempat: Session storage (untuk mempertahankan ID selama sesi)
    const fromSession = sessionStorage.getItem('id_sertifikat');
    if (fromSession) {
        console.log('getUserId: Found from session storage', fromSession);
        return fromSession;
    }

    // 5. Prioritas terakhir: Local storage (kurang disarankan untuk ID sensitif tanpa login ulang)
    const fromLocal = localStorage.getItem('id_sertifikat');
    if (fromLocal) {
        console.log('getUserId: Found from local storage', fromLocal);
        return fromLocal;
    }

    console.warn('getUserId: No valid user ID found from any source. User might not be logged in or ID not passed correctly from backend.');
    return null;
}

/**
 * Memuat data presensi untuk bulan dan tahun yang sedang aktif dari API.
 * Data di-cache secara lokal di `attendanceData`.
 */
async function loadAttendanceData() {
    if (!currentUserId) {
        console.warn('loadAttendanceData: No currentUserId available. Cannot load data.');
        return;
    }

    // Ambil CSRF Token dari meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token not found. Please ensure <meta name="csrf-token" content="{{ csrf_token() }}"> is in your Blade layout.');
        showMessage('CSRF token tidak ditemukan. Pastikan meta tag ada di HTML.', 'error');
        return; // Hentikan proses jika token tidak ada
    }

    try {
        const response = await fetch(`${API_CONFIG.baseUrl}${API_CONFIG.endpoints.get}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken // Tambahkan CSRF token di sini
            },
            body: JSON.stringify({
                id_sertifikat: currentUserId,
                year: currentYear,
                month: currentMonth + 1 // Bulan API biasanya 1-indexed
            })
        });

        if (!response.ok) {
            const errorBody = await response.json();
            throw new Error(errorBody.message || `HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.success) {
            // Strukturkan data sesuai format lokal {tahun: {bulan: [record1, record2]}}
            if (!attendanceData[currentYear]) {
                attendanceData[currentYear] = {};
            }
            // Pastikan result.data adalah array, fallback ke array kosong jika null/undefined
            attendanceData[currentYear][currentMonth + 1] = result.data || []; 
            console.log(`Attendance data loaded for ${currentMonth + 1}/${currentYear}:`, attendanceData[currentYear][currentMonth + 1]);
        } else {
            throw new Error(result.message || 'Gagal memuat data presensi dari server.');
        }
    } catch (error) {
        console.error('Error loading attendance data:', error);
        // Fallback ke data lokal yang mungkin sudah ada (jika ini bukan load pertama)
        if (!attendanceData[currentYear]) {
            attendanceData[currentYear] = {};
        }
        if (!attendanceData[currentYear][currentMonth + 1]) {
            attendanceData[currentYear][currentMonth + 1] = [];
        }
        throw error; // Propagate error for initializeApp to handle
    }
}

/**
 * Menyimpan data presensi baru ke database melalui API.
 * @param {object} attendanceRecord - Objek data presensi yang akan disimpan.
 */
async function saveAttendanceToDatabase(attendanceRecord) {
    if (!currentUserId) {
        throw new Error('ID Sertifikat tidak tersedia. Gagal menyimpan presensi.');
    }

    // Ambil CSRF Token dari meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token not found. Please ensure <meta name="csrf-token" content="{{ csrf_token() }}"> is in your Blade layout.');
        showMessage('CSRF token tidak ditemukan. Pastikan meta tag ada di HTML.', 'error');
        return; // Hentikan proses jika token tidak ada
    }

    try {
        const response = await fetch(`${API_CONFIG.baseUrl}${API_CONFIG.endpoints.save}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken // Tambahkan CSRF token di sini
            },
            body: JSON.stringify({
                id_sertifikat: currentUserId, // Pastikan ID dikirim
                ...attendanceRecord
            })
        });

        if (!response.ok) {
            const errorBody = await response.json();
            throw new Error(errorBody.message || `HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        
        if (!result.success) {
            throw new Error(result.message || 'Gagal menyimpan presensi ke database.');
        }

        return result; // Mengembalikan respons sukses dari server
    } catch (error) {
        console.error('Error saving attendance:', error);
        throw error;
    }
}

/**
 * Memuat statistik presensi untuk bulan dan tahun yang sedang aktif dari API.
 * @returns {object} Statistik presensi (hadir, alpha, izin, persentase).
 */
async function loadStatistics() {
    if (!currentUserId) {
        console.warn('loadStatistics: No currentUserId available. Calculating local stats.');
        return calculateLocalStatistics();
    }

    // Ambil CSRF Token dari meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token not found. Please ensure <meta name="csrf-token" content="{{ csrf_token() }}"> is in your Blade layout.');
        showMessage('CSRF token tidak ditemukan. Pastikan meta tag ada di HTML.', 'error');
        return; // Hentikan proses jika token tidak ada
    }

    try {
        const response = await fetch(`${API_CONFIG.baseUrl}${API_CONFIG.endpoints.getStats}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken // Tambahkan CSRF token di sini
            },
            body: JSON.stringify({
                id_sertifikat: currentUserId,
                year: currentYear,
                month: currentMonth + 1
            })
        });

        if (!response.ok) {
             const errorBody = await response.json();
             throw new Error(errorBody.message || `HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        if (result.success) {
            return result.data;
        } else {
            throw new Error(result.message || 'Gagal memuat statistik dari server.');
        }
    } catch (error) {
        console.error('Error loading statistics from API, falling back to local calculation:', error);
        return calculateLocalStatistics(); // Fallback ke perhitungan lokal
    }
}

/**
 * Mengambil riwayat presensi lengkap dari server untuk modal pop-up.
 * @param {string} userId - ID pengguna (id_sertifikat) yang riwayatnya ingin diambil.
 */
async function fetchFullAttendanceHistory(userId) {
    const attendanceTableBody = document.getElementById('attendanceTableBody');
    if (!attendanceTableBody) {
        console.error("Elemen 'attendanceTableBody' tidak ditemukan untuk riwayat lengkap. Pastikan ID HTML sudah benar.");
        return;
    }

    // Tampilkan pesan loading di tabel
    attendanceTableBody.innerHTML = '<tr><td colspan="4" style="text-align: center;">Memuat riwayat lengkap...</td></tr>';

    // Validasi ID pengguna
    if (!userId || userId === 'null' || userId.trim() === '') {
        attendanceTableBody.innerHTML = '<tr><td colspan="4" style="color: red; text-align: center;">ID Sertifikat tidak ditemukan. Tidak dapat memuat riwayat presensi.</td></tr>';
        console.error('fetchFullAttendanceHistory: ID Sertifikat tidak valid atau kosong:', userId);
        return;
    }

    try {
        // Membangun URL dengan query parameters untuk GET request
        const url = new URL(`${window.location.origin}${API_CONFIG.baseUrl}${API_CONFIG.endpoints.getFullHistory}`);
        url.searchParams.append('id_sertifikat', userId);

        const response = await fetch(url.toString(), {
            method: 'GET', // Metode GET
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Memberi tahu server ini adalah AJAX request
                // Untuk GET request, CSRF token tidak diperlukan di header
            }
            // Penting: Tidak boleh ada 'body' di GET request!
        });

        if (!response.ok) {
            let errorMessageDetail = `Status: ${response.status}`;
            try {
                const errorData = await response.json();
                if (errorData.message) errorMessageDetail = errorData.message;
                else if (errorData.error) errorMessageDetail = errorData.error;
            } catch (jsonError) {
                const responseText = await response.text();
                if (responseText) errorMessageDetail += `. Detail: ${responseText.substring(0, 100)}...`;
            }
            throw new Error(`Kesalahan HTTP! ${errorMessageDetail}`);
        }

        const apiResponse = await response.json();
        attendanceTableBody.innerHTML = ''; // Kosongkan placeholder loading setelah respons

        if (apiResponse.success && apiResponse.data && apiResponse.data.length > 0) {
            // Iterasi melalui setiap record data dan tambahkan ke tabel
            apiResponse.data.forEach(record => {
                let statusClass = '';
                let statusIcon = '';
                const status = record.status || '-';

                switch(status) {
                    case 'Hadir':
                        statusClass = 'status-present';
                        statusIcon = '<i class="fas fa-check-circle me-1"></i>';
                        break;
                    case 'Alpha':
                        statusClass = 'status-absent';
                        statusIcon = '<i class="fas fa-times-circle me-1"></i>';
                        break;
                    case 'Izin':
                        statusClass = 'status-permission';
                        statusIcon = '<i class="fas fa-exclamation-triangle me-1"></i>';
                        break;
                    default:
                        statusClass = 'status-pending';
                        statusIcon = '<i class="fas fa-question-circle me-1"></i>';
                }

                // Format tanggal Bahasa Indonesia fallback jika ada string bahasa Inggris (e.g. July -> Juli)
                let formattedTanggal = record.tanggal ? String(record.tanggal)
                    .replace(/july/i, 'Juli')
                    .replace(/january/i, 'Januari')
                    .replace(/february/i, 'Februari')
                    .replace(/march/i, 'Maret')
                    .replace(/may/i, 'Mei')
                    .replace(/june/i, 'Juni')
                    .replace(/august/i, 'Agustus')
                    .replace(/september/i, 'September')
                    .replace(/october/i, 'Oktober')
                    .replace(/november/i, 'November')
                    .replace(/december/i, 'Desember') : '-';

                const row = `
                    <tr>
                        <td class="fw-semibold text-secondary">${formattedTanggal}</td>
                        <td><span class="badge-status ${statusClass}">${statusIcon}${status}</span></td>
                        <td>${record.keterangan || '-'}</td>
                        <td><span class="text-muted"><i class="far fa-clock me-1"></i>${record.time || '-'}</span></td>
                    </tr>
                `;
                attendanceTableBody.innerHTML += row;
            });
        } else {
            attendanceTableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-4">${apiResponse.message || 'Tidak ada riwayat presensi ditemukan.'}</td></tr>`;
        }
    } catch (error) {
        console.error('Gagal mengambil riwayat presensi lengkap:', error);
        attendanceTableBody.innerHTML = `<tr><td colspan="4" class="text-center text-danger py-4">Gagal memuat riwayat: ${error.message}</td></tr>`;
    }
}

/**
 * Inisialisasi modal riwayat lengkap (Posisi Tengah & Bisa Digeser / Draggable)
 */
function initializeModal() {
    const showAttendancePopupBtn = document.getElementById('showAttendancePopup');
    const attendanceModal = document.getElementById('attendanceModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const attendanceTableBody = document.getElementById('attendanceTableBody');
    const modalHeader = document.getElementById('attendanceModalHeader');
    const modalCard = document.getElementById('attendanceModalCard');

    if (showAttendancePopupBtn && attendanceModal && closeModalBtn && attendanceTableBody) {
        // Aktifkan Fitur Drag & Drop menggunakan Header Modal
        if (modalCard && modalHeader) {
            makeModalDraggable(modalCard, modalHeader);
        }

        showAttendancePopupBtn.addEventListener('click', function(event) {
            event.preventDefault();

            // Reset posisi modal ke tengah layar setiap kali tombol diklik
            if (modalCard) {
                modalCard.style.position = '';
                modalCard.style.top = '';
                modalCard.style.left = '';
                modalCard.style.margin = '';
            }

            attendanceModal.classList.add('show');

            const userId = getUserId();
            if (userId) {
                fetchFullAttendanceHistory(userId);
            } else {
                attendanceTableBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4">ID Sertifikat tidak ditemukan. Tidak dapat memuat riwayat presensi.</td></tr>';
            }
        });

        closeModalBtn.addEventListener('click', function() {
            attendanceModal.classList.remove('show');
            attendanceTableBody.innerHTML = '';
        });

        window.addEventListener('click', function(event) {
            if (event.target === attendanceModal) {
                attendanceModal.classList.remove('show');
                attendanceTableBody.innerHTML = '';
            }
        });
    } else {
        console.warn("Salah satu elemen HTML untuk modal riwayat lengkap tidak ditemukan.");
    }
}

/**
 * Fungsi untuk membuat modal dapat di-drag / digeser menggunakan kursor
 */
function makeModalDraggable(modalElement, dragHandle) {
    let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

    dragHandle.onmousedown = dragMouseDown;

    function dragMouseDown(e) {
        // Ignorir jika yang diklik adalah tombol close
        if (e.target.closest('#closeModalBtn') || e.target.classList.contains('attendance-modal-close')) return;

        e.preventDefault();
        pos3 = e.clientX;
        pos4 = e.clientY;

        document.onmouseup = closeDragElement;
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e.preventDefault();
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;

        const rect = modalElement.getBoundingClientRect();

        modalElement.style.position = 'fixed';
        modalElement.style.top = (rect.top - pos2) + "px";
        modalElement.style.left = (rect.left - pos1) + "px";
        modalElement.style.margin = '0';
    }

    function closeDragElement() {
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

/**
 * Memperbarui tampilan waktu dan tanggal saat ini.
 */
function updateCurrentTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    
    const timeDisplay = document.getElementById('timeDisplay');
    if (timeDisplay) {
        timeDisplay.textContent = timeString;
    }

    const todayDate = document.getElementById('todayDate');
    if (todayDate) {
        const dateString = `${dayNames[now.getDay()]}, ${now.getDate()} ${monthNames[now.getMonth()]} ${now.getFullYear()}`;
        todayDate.textContent = dateString;
    }
}

/**
 * Mendapatkan lokasi geografis pengguna.
 */
function getUserLocation() {
    const locationInput = document.getElementById('location');
    if (!locationInput) return;

    locationInput.value = 'Mendeteksi lokasi...'; // Tampilkan status deteksi

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                locationInput.value = `Lokasi Terdeteksi (${lat.toFixed(6)}, ${lng.toFixed(6)})`;
                locationInput.setAttribute('readonly', 'readonly');
            },
            function(error) {
                console.warn('Geolocation warning/error:', error.message);
                // Fallback agar presensi tidak terblokir jika lokasi gagal dideteksi
                locationInput.value = '';
                locationInput.placeholder = 'Lokasi Online (Presensi Web)';
                locationInput.removeAttribute('readonly');
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
    } else {
        locationInput.value = '';
        locationInput.placeholder = 'Lokasi Online (Presensi Web)';
        locationInput.removeAttribute('readonly');
    }
}

/**
 * Mengubah bulan yang ditampilkan di kalender dan memuat ulang data.
 * @param {number} direction - -1 untuk bulan sebelumnya, 1 untuk bulan berikutnya.
 */
async function changeMonth(direction) {
    currentMonth += direction;
    
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    } else if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    
    showMessage('Memuat data bulan...', 'info');
    
    try {
        await loadAttendanceData();
        updateMonthDisplay();
        await updateStatistics(); // Tunggu statistik selesai
        await updateChart(); // Perbarui chart setelah statistik siap
        hideMessage();
    } catch (error) {
        showMessage('Gagal memuat data bulan tersebut.', 'error');
    }
}

/**
 * Memperbarui teks tampilan bulan dan tahun.
 */
function updateMonthDisplay() {
    const monthDisplay = document.getElementById('currentMonthDisplay');
    if (monthDisplay) {
        monthDisplay.textContent = `${monthNames[currentMonth]} ${currentYear}`;
    }
}

/**
 * Memperbarui statistik presensi di UI.
 */
async function updateStatistics() {
    try {
        const stats = await loadStatistics();
        
        if (stats) {
            updateElementText('totalDays', stats.totalDays || '0');
            updateElementText('presentDays', stats.presentDays || '0');
            updateElementText('absentDays', stats.absentDays || '0');
            updateElementText('permissionDays', stats.permissionDays || '0');
            updateElementText('presentPercentage', (stats.presentPercentage || 0) + '%');
            updateElementText('absentPercentage', (stats.absentPercentage || 0) + '%');
            updateElementText('permissionPercentage', (stats.permissionPercentage || 0) + '%');
        } else {
            // Jika stats null/undefined, set ke 0 atau nilai default
            updateElementText('totalDays', '0');
            updateElementText('presentDays', '0');
            updateElementText('absentDays', '0');
            updateElementText('permissionDays', '0');
            updateElementText('presentPercentage', '0%');
            updateElementText('absentPercentage', '0%');
            updateElementText('permissionPercentage', '0%');
        }
    } catch (error) {
        console.error('Error updating statistics:', error);
        // Statistik mungkin sudah diatur ke 0 oleh blok catch di loadStatistics
    }
}

/**
 * Menghitung statistik presensi dari data lokal (sebagai fallback).
 * @returns {object} Statistik presensi.
 */
function calculateLocalStatistics() {
    const monthData = getMonthData(currentYear, currentMonth + 1);
    const totalDaysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    const now = new Date();
    let maxDay;
    if (currentYear === now.getFullYear() && currentMonth === now.getMonth()) {
        maxDay = now.getDate();
    } else {
        maxDay = totalDaysInMonth;
    }

    // Hitung hari kerja saja (Senin - Jumat), lewati Sabtu (6) dan Minggu (0)
    let totalDays = 0;
    for (let day = 1; day <= maxDay; day++) {
        const dateObj = new Date(currentYear, currentMonth, day);
        const dayOfWeek = dateObj.getDay();
        if (dayOfWeek !== 0 && dayOfWeek !== 6) {
            totalDays++;
        }
    }

    // Abaikan record presensi yang jatuh pada akhir pekan (Sabtu/Minggu) jika ada
    const validMonthData = monthData.filter(d => {
        if (!d.date) return true;
        const dt = new Date(d.date);
        const dayOfWeek = dt.getDay();
        return dayOfWeek !== 0 && dayOfWeek !== 6;
    });

    const presentDays = validMonthData.filter(d => d.status === 'Hadir').length;
    const permissionDays = validMonthData.filter(d => d.status === 'Izin').length;
    
    const absentDays = Math.max(0, totalDays - (presentDays + permissionDays));
    
    const presentPercentage = totalDays > 0 ? Math.round((presentDays / totalDays) * 100) : 0;
    const permissionPercentage = totalDays > 0 ? Math.round((permissionDays / totalDays) * 100) : 0;
    const absentPercentage = totalDays > 0 ? Math.round((absentDays / totalDays) * 100) : 0;

    return {
        totalDays,
        presentDays,
        absentDays,
        permissionDays,
        presentPercentage,
        absentPercentage,
        permissionPercentage
    };
}

/**
 * Fungsi helper untuk memperbarui teks elemen HTML berdasarkan ID.
 * @param {string} id - ID elemen.
 * @param {string} text - Teks yang akan dimasukkan.
 */
function updateElementText(id, text) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = text;
    }
}

/**
 * Mendapatkan data presensi untuk bulan dan tahun tertentu dari cache lokal.
 * @param {number} year - Tahun.
 * @param {number} month - Bulan (1-indexed).
 * @returns {Array} Daftar catatan presensi.
 */
function getMonthData(year, month) {
    if (attendanceData[year] && attendanceData[year][month]) {
        return attendanceData[year][month];
    }
    return [];
}

/**
 * Mendapatkan jumlah Hadir, Alpha, dan Izin yang konsisten antara kartu ringkasan dan grafik.
 */
async function getAttendanceCounts() {
    let stats = null;
    try {
        stats = await loadStatistics();
    } catch (e) {
        console.warn('Fallback to local calculation for chart:', e);
    }
    
    if (!stats) {
        stats = calculateLocalStatistics();
    }

    return {
        presentCount: parseInt(stats.presentDays) || 0,
        absentCount: parseInt(stats.absentDays) || 0,
        permissionCount: parseInt(stats.permissionDays) || 0
    };
}

/**
 * Membuat atau memperbarui chart presensi bulanan.
 */
async function createChart() {
    const ctx = document.getElementById('attendanceChart');
    if (!ctx) return;

    const { presentCount, absentCount, permissionCount } = await getAttendanceCounts();

    // Hancurkan chart yang ada sebelum membuat yang baru untuk mencegah duplikasi
    if (attendanceChart) {
        attendanceChart.destroy();
    }

    const hasData = presentCount > 0 || absentCount > 0 || permissionCount > 0;

    attendanceChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: hasData ? ['Hadir', 'Alpha', 'Izin'] : ['Belum ada data'],
            datasets: [{
                data: hasData ? [presentCount, absentCount, permissionCount] : [1],
                backgroundColor: hasData ? [
                    '#28a745', // Hadir (Green)
                    '#dc3545', // Alpha (Red)
                    '#ffc107'  // Izin (Yellow/Orange)
                ] : ['#e9ecef'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                title: { // Tambahkan judul chart
                    display: true,
                    text: 'Statistik Presensi Bulan Ini'
                }
            }
        }
    });
}

/**
 * Memperbarui data pada chart presensi bulanan yang sudah ada.
 */
async function updateChart() {
    if (attendanceChart) {
        const { presentCount, absentCount, permissionCount } = await getAttendanceCounts();

        const hasData = presentCount > 0 || absentCount > 0 || permissionCount > 0;

        attendanceChart.data.labels = hasData ? ['Hadir', 'Alpha', 'Izin'] : ['Belum ada data'];
        attendanceChart.data.datasets[0].data = hasData ? [presentCount, absentCount, permissionCount] : [1];
        attendanceChart.data.datasets[0].backgroundColor = hasData ? ['#28a745', '#dc3545', '#ffc107'] : ['#e9ecef'];
        attendanceChart.update();
    } else {
        await createChart();
    }
}

/**
 * Mendapatkan string tanggal lokal YYYY-MM-DD.
 */
function getLocalDateString(dateObj = new Date()) {
    const year = dateObj.getFullYear();
    const month = String(dateObj.getMonth() + 1).padStart(2, '0');
    const day = String(dateObj.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

/**
 * Memperbarui status presensi untuk hari ini di UI.
 */
function updateTodayAttendance() {
    const todayString = getLocalDateString();
    
    const todayData = findAttendanceByDate(todayString);
    const statusElement = document.getElementById('todayStatus');
    const btnHadir = document.getElementById('btnHadir');
    const btnIzin = document.getElementById('btnIzin');
    const noteMsgElement = document.getElementById('attendanceNoteMessage');
    
    // Pastikan tombol selalu tampil (tidak dihilangkan)
    if (btnHadir) btnHadir.style.display = '';
    if (btnIzin) btnIzin.style.display = '';

    if (todayData) {
        // Sudah presensi
        updateStatusBadge(statusElement, todayData.status);
        disableAttendanceForm();

        if (noteMsgElement) {
            noteMsgElement.style.display = 'block';
            if (todayData.status === 'Hadir') {
                noteMsgElement.className = 'mt-3 p-2 rounded text-center fw-bold alert alert-success text-success';
                noteMsgElement.innerHTML = '<i class="fas fa-check-circle me-1"></i> Anda telah hadir pada absensi hari ini';
            } else if (todayData.status === 'Izin') {
                noteMsgElement.className = 'mt-3 p-2 rounded text-center fw-bold alert alert-warning text-dark';
                noteMsgElement.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Anda telah izin pada absensi hari ini';
            } else {
                noteMsgElement.className = 'mt-3 p-2 rounded text-center fw-bold alert alert-secondary text-secondary';
                noteMsgElement.innerHTML = `Anda telah presensi (${todayData.status}) hari ini`;
            }
        }
    } else {
        // Belum presensi
        if (statusElement) {
            statusElement.innerHTML = '<i class="fas fa-question-circle"></i> Belum Presensi';
            statusElement.className = 'status-badge status-pending';
        }
        enableAttendanceForm();
        if (noteMsgElement) {
            noteMsgElement.style.display = 'none';
        }
    }
}

/**
 * Memperbarui tampilan badge status.
 * @param {HTMLElement} element - Elemen badge status.
 * @param {string} status - Status presensi (Hadir, Alpha, Izin).
 */
function updateStatusBadge(element, status) {
    if (!element) return;
    let icon, className;
    
    switch(status) {
        case 'Hadir':
            icon = 'fas fa-check-circle';
            className = 'status-badge status-present';
            break;
        case 'Alpha':
            icon = 'fas fa-times-circle';
            className = 'status-badge status-absent';
            break;
        case 'Izin':
            icon = 'fas fa-exclamation-triangle';
            className = 'status-badge status-permission';
            break;
        default:
            icon = 'fas fa-question-circle';
            className = 'status-badge status-pending';
    }
    
    element.innerHTML = `<i class="${icon}"></i> ${status}`;
    element.className = className;
}

function findAttendanceByDate(dateString) {
    if (!dateString) return null;
    const targetDateStr = String(dateString).slice(0, 10);
    const parts = targetDateStr.split('-');
    if (parts.length < 3) return null;
    const targetYear = parseInt(parts[0], 10);
    const targetMonth = parseInt(parts[1], 10); // 1-indexed

    if (attendanceData[targetYear] && attendanceData[targetYear][targetMonth]) {
        return attendanceData[targetYear][targetMonth].find(record => {
            if (!record || !record.date) return false;
            return String(record.date).slice(0, 10) === targetDateStr;
        });
    }
    return null;
}

/**
 * Menonaktifkan form presensi.
 */
function disableAttendanceForm() {
    const form = document.getElementById('attendanceForm');
    const btnHadir = document.getElementById('btnHadir');
    const btnIzin = document.getElementById('btnIzin');
    
    if (form) {
        const buttons = form.querySelectorAll('button');
        const textarea = form.querySelector('textarea');
        const locationInput = document.getElementById('location');
        
        buttons.forEach(btn => {
            btn.disabled = true;
            btn.style.opacity = '0.6';
            btn.style.cursor = 'not-allowed';
        });
        
        if (textarea) {
            textarea.disabled = true;
        }
        if (locationInput) {
            locationInput.disabled = true;
        }
    }
    
    // Disable tombol di luar form juga
    if (btnHadir) {
        btnHadir.disabled = true;
        btnHadir.style.opacity = '0.6';
        btnHadir.style.cursor = 'not-allowed';
    }
    if (btnIzin) {
        btnIzin.disabled = true;
        btnIzin.style.opacity = '0.6';
        btnIzin.style.cursor = 'not-allowed';
    }
}

/**
 * Mengaktifkan form presensi.
 */
function enableAttendanceForm() {
    const form = document.getElementById('attendanceForm');
    const btnHadir = document.getElementById('btnHadir');
    const btnIzin = document.getElementById('btnIzin');
    
    if (form) {
        const buttons = form.querySelectorAll('button');
        const textarea = form.querySelector('textarea');
        const locationInput = document.getElementById('location');
        
        buttons.forEach(btn => {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
        });
        
        if (textarea) {
            textarea.disabled = false;
        }
        if (locationInput) {
            locationInput.disabled = false;
        }
    }
    
    // Enable tombol di luar form juga
    if (btnHadir) {
        btnHadir.disabled = false;
        btnHadir.style.opacity = '1';
        btnHadir.style.cursor = 'pointer';
    }
    if (btnIzin) {
        btnIzin.disabled = false;
        btnIzin.style.opacity = '1';
        btnIzin.style.cursor = 'pointer';
    }
}

function handleIzinClick(e) {
    if (e && e.preventDefault) e.preventDefault();
    console.log('handleIzinClick dipanggil');
    const notesInput = document.getElementById('notes');
    const notes = notesInput ? notesInput.value.trim() : '';
    if (notes.length < 3) {
        showMessage('Untuk status Izin, catatan/keterangan harus diisi minimal 3 karakter.', 'warning');
        if (notesInput) notesInput.focus();
        return;
    }
    markAttendance('Izin');
}

/**
 * Fungsi untuk menandai presensi (Hadir/Izin).
 * @param {string} status - Status presensi ('Hadir' atau 'Izin').
 */
async function markAttendance(status) {
    if (!currentUserId || currentUserId === 'null' || currentUserId === 'undefined' || currentUserId.trim() === '') {
        showMessage('ID Sertifikat tidak valid. Silakan refresh halaman atau login kembali.', 'error');
        console.error('markAttendance: currentUserId tidak valid:', currentUserId);
        return;
    }

    const locationInput = document.getElementById('location');
    let location = locationInput ? locationInput.value.trim() : '';
    const notesInput = document.getElementById('notes');
    const notes = notesInput ? notesInput.value.trim() : '';
    const today = new Date();
    const todayString = getLocalDateString(today);
    const hours = String(today.getHours()).padStart(2, '0');
    const minutes = String(today.getMinutes()).padStart(2, '0');
    const seconds = String(today.getSeconds()).padStart(2, '0');
    const timeString = `${hours}:${minutes}:${seconds}`;

    // Fallback jika lokasi kosong, gagal terdeteksi, atau sedang proses mendeteksi
    if (!location || location === 'Lokasi tidak dapat dideteksi' || location === 'Geolocation tidak didukung oleh browser Anda' || location === 'Mendeteksi lokasi...' || location === 'Mendapatkan lokasi...') {
        location = 'Lokasi Online (Presensi Web)';
        if (locationInput) {
            locationInput.value = location;
            locationInput.removeAttribute('readonly');
        }
    }

    // Cek apakah sudah presensi hari ini
    if (findAttendanceByDate(todayString)) {
        showMessage('Anda sudah melakukan presensi hari ini!', 'warning');
        disableAttendanceForm();
        return;
    }

    // Disable form sementara
    disableAttendanceForm();
    showMessage('Menyimpan presensi...', 'info');

    try {
        const attendanceRecord = {
            date: todayString,
            status: status,
            location: location,
            notes: notes,
            time: timeString,
            timestamp: new Date().toISOString() // Untuk sort di backend jika diperlukan
        };

        console.log('Attempting to save attendance record:', attendanceRecord);
        console.log('Sending with currentUserId:', currentUserId);

        const saveResult = await saveAttendanceToDatabase(attendanceRecord);

        // Update data lokal setelah berhasil disimpan
        const currentMonthNum = today.getMonth() + 1;
        const currentYearForRecord = today.getFullYear();
        
        if (!attendanceData[currentYearForRecord]) {
            attendanceData[currentYearForRecord] = {};
        }
        if (!attendanceData[currentYearForRecord][currentMonthNum]) {
            attendanceData[currentYearForRecord][currentMonthNum] = [];
        }
        
        const existingRecordIndex = attendanceData[currentYearForRecord][currentMonthNum]
            .findIndex(record => record.date === todayString);
        
        if (existingRecordIndex > -1) {
            attendanceData[currentYearForRecord][currentMonthNum][existingRecordIndex] = { 
                ...attendanceData[currentYearForRecord][currentMonthNum][existingRecordIndex], 
                ...attendanceRecord 
            };
        } else {
            attendanceData[currentYearForRecord][currentMonthNum].push(attendanceRecord);
        }

        // Perbarui UI setelah sukses
        updateTodayAttendance();
        updateAttendanceHistory();
        updateStatistics();
        updateChart();

        document.getElementById('notes').value = ''; // Reset notes

        showMessage(`Presensi ${status} berhasil disimpan!`, 'success');

    } catch (error) {
        console.error('Error in markAttendance:', error);
        showMessage(`Gagal menyimpan presensi: ${error.message || 'Terjadi kesalahan tidak dikenal.'}`, 'error');
        enableAttendanceForm(); // Aktifkan kembali form jika gagal
    }
}

/**
 * Memperbarui daftar riwayat presensi terbaru di UI.
 */
async function updateAttendanceHistory() {
    const historyList = document.getElementById('historyList');
    if (!historyList) return;

    historyList.innerHTML = '<div style="text-align: center; color: #888;">Memuat riwayat...</div>';
    try {
        const recentRecords = await loadHistory();

        if (!recentRecords || recentRecords.length === 0) {
            historyList.innerHTML = '<div style="text-align: center; color: #888;">Belum ada riwayat presensi</div>';
            return;
        }

        const historyHTML = recentRecords.map(record => {
            const date = new Date(record.date + 'T00:00:00'); // Tambahkan T00:00:00 untuk hindari masalah timezone
            const dateStr = `${dayNames[date.getDay()]}, ${date.getDate()} ${monthNames[date.getMonth()]}`;
            
            let statusClass = '';
            let statusIcon = '';
            
            switch(record.status) {
                case 'Hadir':
                    statusClass = 'status-present';
                    statusIcon = 'fas fa-check-circle';
                    break;
                case 'Alpha':
                    statusClass = 'status-absent';
                    statusIcon = 'fas fa-times-circle';
                    break;
                case 'Izin':
                    statusClass = 'status-permission';
                    statusIcon = 'fas fa-exclamation-triangle';
                    break;
                default:
                    statusClass = 'status-pending';
                    statusIcon = 'fas fa-question-circle';
            }

            return `
                <div class="history-item">
                    <div class="history-date">${dateStr}</div>
                    <div class="history-status ${statusClass}">
                        <i class="${statusIcon}"></i>
                        ${record.status}
                    </div>
                    <div class="history-time">${record.time || '-'}</div>
                </div>
            `;
        }).join('');

        historyList.innerHTML = historyHTML;
    } catch (error) {
        console.error('Error updating history:', error);
        historyList.innerHTML = '<div style="text-align: center; color: #888;">Gagal memuat riwayat</div>';
    }
}

/**
 * Mendapatkan riwayat presensi dari data lokal (sebagai fallback).
 * Menggabungkan semua catatan dari cache lokal dan mengurutkannya.
 * @returns {Array} 5 catatan presensi terbaru dari cache lokal.
 */
function getLocalHistory() {
    const allRecords = [];
    for (let year in attendanceData) {
        for (let month in attendanceData[year]) {
            allRecords.push(...attendanceData[year][month]);
        }
    }

    // Urutkan berdasarkan tanggal descending
    allRecords.sort((a, b) => new Date(b.date) - new Date(a.date));
    return allRecords.slice(0, 5); // Ambil 5 record terbaru
}

/**
 * Menampilkan pesan notifikasi di UI.
 * @param {string} message - Pesan yang akan ditampilkan.
 * @param {string} type - Tipe pesan ('info', 'success', 'warning', 'error').
 */
function showMessage(message, type = 'info') {
    const messageBox = document.getElementById('messageBox');
    if (messageBox) {
        messageBox.className = 'message-box'; 
        messageBox.textContent = message;

        // Remove existing type classes
        messageBox.classList.remove('message-info', 'message-success', 'message-warning', 'message-error');

        // Add new type class
        messageBox.classList.add(`message-${type}`);
        messageBox.style.display = 'block';

        if (type === 'success' || type === 'info') {
            setTimeout(() => {
                hideMessage();
            }, 5000); // Pesan info/sukses hilang setelah 5 detik
        }
    } else {
        // Fallback jika messageBox tidak ditemukan
        const messageContainer = document.getElementById('messageContainer');
        if (messageContainer) {
            messageContainer.textContent = message;
            messageContainer.className = `message-container ${type}`;
            messageContainer.style.display = 'block';
        } else {
            console.warn(`Message container not found. Message: ${message} (${type})`);
            alert(message); // Fallback terakhir
        }
    }
}

/**
 * Menyembunyikan pesan notifikasi.
 */
function hideMessage() {
    const messageBox = document.getElementById('messageBox');
    if (messageBox) {
        messageBox.style.display = 'none';
    }
    
    const messageContainer = document.getElementById('messageContainer');
    if (messageContainer) {
        messageContainer.style.display = 'none';
    }
}

// Event Listeners untuk tombol navigasi bulan
document.addEventListener('DOMContentLoaded', function() {
    const prevMonthBtn = document.getElementById('prevMonthBtn');
    const nextMonthBtn = document.getElementById('nextMonthBtn');
    const btnHadir = document.getElementById('btnHadir');
    const btnIzin = document.getElementById('btnIzin');
    
    if (prevMonthBtn) {
        prevMonthBtn.addEventListener('click', () => changeMonth(-1));
    }
    
    if (nextMonthBtn) {
        nextMonthBtn.addEventListener('click', () => changeMonth(1));
    }
    
    // Pastikan tombol tidak disabled saat load (kecuali sudah presensi)
    setTimeout(() => {
        if (btnHadir && !btnHadir.style.display.includes('none')) {
            btnHadir.disabled = false;
            btnHadir.style.opacity = '1';
            btnHadir.style.cursor = 'pointer';
            console.log('Tombol Hadir diaktifkan');
        }
        if (btnIzin && !btnIzin.style.display.includes('none')) {
            btnIzin.disabled = false;
            btnIzin.style.opacity = '1';
            btnIzin.style.cursor = 'pointer';
            console.log('Tombol Izin diaktifkan');
        }
    }, 100);
    
    // Event Listeners untuk tombol presensi
    if (btnHadir) {
        btnHadir.addEventListener('click', function(e) {
            console.log('Tombol Hadir diklik!');
            if (e) e.preventDefault();
            markAttendance('Hadir');
        });
    }
    
    if (btnIzin) {
        btnIzin.addEventListener('click', handleIzinClick);
    }
    
    // Event Listener untuk tombol debug (jika ada)
    const debugButton = document.getElementById('debugButton');
    if (debugButton) {
        debugButton.addEventListener('click', () => {
            console.log('Attendance Data:', attendanceData);
            console.log('Current User ID:', currentUserId);
            console.log('Current Month:', currentMonth);
            console.log('Current Year:', currentYear);
            showMessage('Data debug logged to console.', 'info');
        });
    }
});

// Fungsi untuk debugging koneksi API
const attendanceDebug = {
    testConnection: async function() {
        showMessage('Testing API connection...', 'info');
        try {
            const response = await fetch(`${API_CONFIG.baseUrl}/test`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const result = await response.json();
            if (response.ok && result.success) {
                showMessage(`API connection successful: ${result.message}`, 'success');
                console.log('API Test Result:', result);
            } else {
                throw new Error(result.message || 'Unknown API test error.');
            }
        } catch (error) {
            console.error('API Test Error:', error);
            showMessage(`API connection failed: ${error.message}`, 'error');
        }
    }
};

// =============== KALENDER MAGANG ===============
const semuaMagang = @json($semuaMagang ?? []);
let calendarCurrentDate = new Date();
let calendarSelectedDate = new Date();

// Carbon diserialisasi Laravel sebagai ISO datetime. Kalender membandingkan
// tanggal saja, jadi normalisasi dulu agar hari pertama masa magang ikut ditandai.
function toCalendarDate(value) {
    return value ? String(value).slice(0, 10) : '';
}

// Debug: Log data magang yang diterima
console.log('Data semuaMagang:', semuaMagang);

// Render kalender
function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDayIndex = new Date(year, month, 1).getDay();
    const lastDayDate = new Date(year, month + 1, 0).getDate();
    const today = new Date();
    
    const calMonthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const el = document.getElementById('calendarMonthYear');
    if (el) el.textContent = `${calMonthNames[month]} ${year}`;
    
    let days = "";
    let dayCount = 1;
    
    for (let i = 0; i < 6; i++) {
        let row = "<tr>";
        for (let j = 0; j < 7; j++) {
            if (i === 0 && j < firstDayIndex) {
                row += `<td class="empty"></td>`;
            } else if (dayCount > lastDayDate) {
                row += `<td class="empty"></td>`;
            } else {
                const currentCellDate = new Date(year, month, dayCount);
                const formattedCellDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(dayCount).padStart(2, '0')}`;
                
                let classes = [];
                if (dayCount === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                    classes.push('today');
                }
                if (dayCount === calendarSelectedDate.getDate() && month === calendarSelectedDate.getMonth() && year === calendarSelectedDate.getFullYear()) {
                    classes.push('selected');
                }
                
                const hasInterns = semuaMagang.some(magang => {
                    const startDate = toCalendarDate(magang.tgl_mulai);
                    const endDate = toCalendarDate(magang.tgl_selesai);
                    const match = startDate && endDate && formattedCellDate >= startDate && formattedCellDate <= endDate;
                    return match;
                });
                
                if (hasInterns) {
                    classes.push('has-interns');
                    console.log(`Date ${formattedCellDate} has interns - classes: ${classes.join(' ')}`);
                }
                
                row += `<td class="${classes.join(' ')}" onclick="selectDate(${year}, ${month}, ${dayCount})">${dayCount}</td>`;
                dayCount++;
            }
        }
        row += "</tr>";
        days += row;
        if (dayCount > lastDayDate) break;
    }
    document.getElementById('calendarBody').innerHTML = days;
    
    // Update info bulan setelah kalender dirender
    updateMonthInfo(year, month);
}

// Update informasi bulan
function updateMonthInfo(year, month) {
    const calMonthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    // Hitung siswa aktif di bulan ini
    const monthStart = `${year}-${String(month + 1).padStart(2, '0')}-01`;
    const monthEnd = `${year}-${String(month + 1).padStart(2, '0')}-31`;
    
    const magangThisMonth = semuaMagang.filter(magang => {
        // Cek apakah magang terjadi di bulan ini
        return (magang.tgl_mulai <= monthEnd && magang.tgl_selesai >= monthStart);
    });
    
    // Hitung bidang dan jumlah siswa per bidang
    const bidangMap = {};
    magangThisMonth.forEach(magang => {
        if (!bidangMap[magang.bidang]) {
            bidangMap[magang.bidang] = 0;
        }
        bidangMap[magang.bidang]++;
    });
    
    // Update tampilan bulan
    const monthYearDisplay = document.getElementById('monthYearDisplay');
    const monthTotalPeserta = document.getElementById('monthTotalPeserta');
    const monthTotalBidang = document.getElementById('monthTotalBidang');
    const monthBidangList = document.getElementById('monthBidangList');
    
    if (monthYearDisplay) monthYearDisplay.textContent = `${calMonthNames[month]} ${year}`;
    if (monthTotalPeserta) monthTotalPeserta.textContent = magangThisMonth.length;
    if (monthTotalBidang) monthTotalBidang.textContent = Object.keys(bidangMap).length;
    
    // Tampilkan bidang dengan jumlah siswa
    if (monthBidangList) {
        monthBidangList.innerHTML = '';
        if (magangThisMonth.length > 0) {
            Object.entries(bidangMap).forEach(([bidang, count]) => {
                const badgeDiv = document.createElement('div');
                badgeDiv.style.cssText = 'display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 10px 14px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #667eea; width: 100%;';
                badgeDiv.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 8px; overflow: hidden;">
                        <i class="fas fa-briefcase" style="color: #667eea; font-size: 14px; flex-shrink: 0;"></i>
                        <span style="font-weight: 500; color: #2c3e50; font-size: 13px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">${bidang}</span>
                    </div>
                    <span style="background: #667eea; color: white; padding: 2px 10px; border-radius: 20px; font-weight: 600; font-size: 12px; flex-shrink: 0;">${count}</span>
                `;
                monthBidangList.appendChild(badgeDiv);
            });
        } else {
            monthBidangList.innerHTML = '<p class="text-muted small">Tidak ada bidang penempatan</p>';
        }
    }
}

// Select date di kalender
window.selectDate = function(year, month, day) {
    calendarSelectedDate = new Date(year, month, day);
    renderCalendar(calendarCurrentDate);
    
    const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    const dateStr = calendarSelectedDate.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    
    // Update display tanggal
    const selectedDayDisplay = document.getElementById('selectedDayDisplay');
    if (selectedDayDisplay) {
        selectedDayDisplay.textContent = dateStr;
    }
    
    fetchDetailJadwalSiswa(formattedDate);
};

// Fetch detail jadwal untuk siswa (hanya agregat, tanpa identitas pribadi)
function fetchDetailJadwalSiswa(dateStr) {
    const monthDetailEmpty = document.getElementById('monthDetailEmpty');
    const dayDetailData = document.getElementById('dayDetailData');
    const dayDetailEmpty = document.getElementById('dayDetailEmpty');
    const monthDetailLoading = document.getElementById('monthDetailLoading');
    
    if (monthDetailEmpty) {
        monthDetailEmpty.classList.add('d-none');
        monthDetailEmpty.classList.remove('d-flex');
    }
    if (dayDetailData) {
        dayDetailData.style.display = 'none';
    }
    if (dayDetailEmpty) {
        dayDetailEmpty.style.display = 'none';
    }
    if (monthDetailLoading) {
        monthDetailLoading.classList.remove('d-none');
        monthDetailLoading.classList.add('d-flex');
    }
    
    fetch(`{{ url("api/internship-students") }}?date=${encodeURIComponent(dateStr)}`)
        .then(response => {
            if (!response.ok) throw new Error('Gagal memuat detail peserta.');
            return response.json();
        })
        .then(result => {
            if (monthDetailLoading) {
                monthDetailLoading.classList.add('d-none');
                monthDetailLoading.classList.remove('d-flex');
            }
            
            if (result.success && result.data && result.data.length > 0) {
                // Hitung statistik agregat dan per bidang
                const totalPeserta = result.data.length;
                const bidangMap = {};
                
                result.data.forEach(student => {
                    if (!bidangMap[student.bidang]) {
                        bidangMap[student.bidang] = 0;
                    }
                    bidangMap[student.bidang]++;
                });
                
                // Update UI
                const dayTotalPeserta = document.getElementById('dayTotalPeserta');
                const dayTotalBidang = document.getElementById('dayTotalBidang');
                
                if (dayTotalPeserta) dayTotalPeserta.textContent = totalPeserta;
                if (dayTotalBidang) dayTotalBidang.textContent = Object.keys(bidangMap).length;
                
                // Tampilkan daftar bidang dengan jumlah siswa
                const dayBidangList = document.getElementById('dayBidangList');
                if (dayBidangList) {
                    dayBidangList.innerHTML = '';
                    Object.entries(bidangMap).forEach(([bidang, count]) => {
                        const badgeDiv = document.createElement('div');
                        badgeDiv.style.cssText = 'display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 10px 14px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #667eea; width: 100%;';
                        badgeDiv.innerHTML = `
                            <div style="display: flex; align-items: center; gap: 8px; overflow: hidden;">
                                <i class="fas fa-briefcase" style="color: #667eea; font-size: 14px; flex-shrink: 0;"></i>
                                <span style="font-weight: 500; color: #2c3e50; font-size: 13px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">${bidang}</span>
                            </div>
                            <span style="background: #667eea; color: white; padding: 2px 10px; border-radius: 20px; font-weight: 600; font-size: 12px; flex-shrink: 0;">${count}</span>
                        `;
                        dayBidangList.appendChild(badgeDiv);
                    });
                }
                
                if (dayDetailData) dayDetailData.style.display = 'block';
            } else {
                const emptyDayDisplay = document.getElementById('emptyDayDisplay');
                if (emptyDayDisplay) {
                    emptyDayDisplay.textContent = calendarSelectedDate.toLocaleDateString('id-ID', {
                        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                    });
                }
                const emptyDayMessage = document.getElementById('emptyDayMessage');
                if (emptyDayMessage) emptyDayMessage.textContent = result.message || 'Tidak ada peserta magang pada tanggal ini.';
                if (dayDetailEmpty) dayDetailEmpty.style.display = 'flex';
            }
        })
        .catch(error => {
            console.error('Error fetching detail jadwal:', error);
            if (monthDetailLoading) {
                monthDetailLoading.classList.add('d-none');
                monthDetailLoading.classList.remove('d-flex');
            }
            const emptyDayDisplay = document.getElementById('emptyDayDisplay');
            if (emptyDayDisplay) emptyDayDisplay.textContent = 'Detail tidak dapat dimuat';
            const emptyDayMessage = document.getElementById('emptyDayMessage');
            if (emptyDayMessage) emptyDayMessage.textContent = 'Silakan coba lagi beberapa saat lagi.';
            if (dayDetailEmpty) dayDetailEmpty.style.display = 'flex';
        });
}

// Event listeners untuk navigasi bulan
document.addEventListener('DOMContentLoaded', function() {
    renderCalendar(calendarCurrentDate);
    
    const prevBtn = document.getElementById('prevMonth');
    const nextBtn = document.getElementById('nextMonth');
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            calendarCurrentDate.setMonth(calendarCurrentDate.getMonth() - 1);
            renderCalendar(calendarCurrentDate);
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            calendarCurrentDate.setMonth(calendarCurrentDate.getMonth() + 1);
            renderCalendar(calendarCurrentDate);
        });
    }
});
</script>
@endsection
