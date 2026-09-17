@extends('layouts.landing')

@section('title', 'Portofolio Magang')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;600&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(180deg, #f7faff 0%, #ffffff 100%);
    }

    .portfolio-section {
        padding: 60px 0;
        animation: fadeInUp 1s ease;
    }

    .portfolio-title {
        font-weight: 700;
        color: #5a60ff;
        text-align: center;
        font-size: 2.6rem;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .portfolio-subtitle {
        text-align: center;
        color: #6c757d;
        margin-bottom: 3rem;
        font-size: 1.05rem;
    }

    .portfolio-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(90, 96, 255, 0.1);
        overflow: hidden;
        transition: all 0.4s ease;
        border: none;
        height: 100%;
    }

    .portfolio-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(90, 96, 255, 0.2);
    }

    .portfolio-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(90, 96, 255, 0.9);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        backdrop-filter: blur(5px);
    }

    .portfolio-img-container {
        position: relative;
        height: 200px;
        background: linear-gradient(135deg, #5a60ff, #8a56f2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }

    .card-body {
        padding: 25px;
    }

    .card-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: #333;
        margin-bottom: 10px;
    }

    .student-name {
        font-size: 0.9rem;
        color: #5a60ff;
        font-weight: 600;
        margin-bottom: 15px;
        display: block;
    }

    .card-text {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    @keyframes fadeInUp {
        from {opacity: 0; transform: translateY(40px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>

<div class="portfolio-section container">
    <h2 class="portfolio-title">Portofolio Magang 🚀</h2>
    <p class="portfolio-subtitle">Karya dan proyek inovatif dari para peserta magang Diskominfo Cilacap.</p>

    <div class="row g-4">
        <!-- Project 1 -->
        <div class="col-md-4">
            <div class="card portfolio-card">
                <div class="portfolio-img-container">
                    <i class="fas fa-laptop-code"></i>
                    <span class="portfolio-badge">Web Dev</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Sistem Informasi Magang (SIMGANG)</h5>
                    <span class="student-name">Oleh: Tim Magang IT</span>
                    <p class="card-text">Aplikasi berbasis web untuk mempermudah pendaftaran, manajemen absensi, tugas, dan sertifikat bagi peserta magang di Diskominfo Cilacap.</p>
                </div>
            </div>
        </div>

        <!-- Project 2 -->
        <div class="col-md-4">
            <div class="card portfolio-card">
                <div class="portfolio-img-container" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                    <i class="fas fa-chart-bar"></i>
                    <span class="portfolio-badge">Statistik</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Visualisasi Data Sektoral</h5>
                    <span class="student-name">Oleh: Rian & Amalia</span>
                    <p class="card-text">Dashboard interaktif visualisasi data statistik sektoral Kabupaten Cilacap guna mendukung pengambilan kebijakan berbasis data.</p>
                </div>
            </div>
        </div>

        <!-- Project 3 -->
        <div class="col-md-4">
            <div class="card portfolio-card">
                <div class="portfolio-img-container" style="background: linear-gradient(135deg, #ff9a9e, #fecfef);">
                    <i class="fas fa-bullhorn"></i>
                    <span class="portfolio-badge">Desain & Media</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Konten Edukasi Keamanan Informasi</h5>
                    <span class="student-name">Oleh: Safira Putri</span>
                    <p class="card-text">Rangkaian konten kreatif, infografis, dan video edukasi mengenai pentingnya menjaga kerahasiaan data dan cyber security untuk masyarakat umum.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
