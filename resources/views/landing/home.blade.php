@extends('layouts.landing')

@section('title', 'Beranda')

@section('content')
<!-- Tambahan Animate.css dan AOS untuk animasi -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        color: #333;
        background-color: #f9fbff;
        overflow-x: hidden;
    }

    h1, h2, h3, h4 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
    }

    /* === HERO SECTION === */
    .hero {
        position: relative;
        background: linear-gradient(135deg, rgba(90,96,255,0.9), rgba(138,86,242,0.9)),
                    url('{{ asset("images/bg-cilacap.jpg") }}') center/cover no-repeat;
        color: #fff;
        border-radius: 20px;
        padding: 60px 40px;
        margin-top: 40px;
        overflow: hidden;
    }

    .hero::after {
        content: "";
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 80px;
        background: url('https://svgshare.com/i/14bA.svg') repeat-x bottom;
        opacity: 0.5;
        animation: waveMove 10s linear infinite;
    }

    @keyframes waveMove {
        from { background-position-x: 0; }
        to { background-position-x: 1000px; }
    }

    .hero-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 40px;
    }

    .hero-text {
        flex: 1;
        min-width: 300px;
    }

    .hero-text h1 {
        font-size: 3rem;
        animation: fadeInLeft 1.2s ease;
    }

    .hero-text p {
        font-size: 1.15rem;
        margin: 20px 0 30px;
        animation: fadeInLeft 1.5s ease;
        opacity: 0.95;
    }

    .hero-text .btn {
        background-color: #fff;
        color: #5a60ff;
        border-radius: 30px;
        padding: 12px 28px;
        font-weight: 600;
        transition: all 0.3s ease;
        animation: zoomIn 1.8s ease;
    }

    .hero-text .btn:hover {
        transform: translateY(-4px);
        background: #eef0ff;
    }

    .hero-image {
        flex: 1;
        min-width: 320px;
        display: flex;
        justify-content: center;
    }

    .hero-image img {
        width: 90%;
        max-width: 460px;
        border-radius: 18px;
        box-shadow: 0 12px 25px rgba(0,0,0,0.25);
        object-fit: cover;
        overflow: hidden;
    }

    /* === INFO CARD === */
    .info-card {
        border-radius: 20px;
        transition: all 0.4s ease;
        background: white;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .info-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(45deg, #5a60ff, #8a56f2);
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 0;
    }

    .info-card:hover::before {
        opacity: 0.1;
    }

    .info-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 12px 25px rgba(90,96,255,0.25);
    }

    .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 28px;
        color: white;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(90,96,255, 0.5); }
        70% { box-shadow: 0 0 0 20px rgba(90,96,255, 0); }
        100% { box-shadow: 0 0 0 0 rgba(90,96,255, 0); }
    }

    /* === ACCORDION (hyperactive version) === */
    .bidang-card {
        border-radius: 14px;
        background: linear-gradient(135deg, #ffffff, #f3f5ff);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .bidang-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 8px 22px rgba(138, 86, 242, 0.2);
    }

    .bidang-btn {
        background: linear-gradient(90deg, #5a60ff, #8a56f2);
        color: white;
        font-weight: 600;
        border-radius: 10px;
        transition: 0.3s;
    }

    .bidang-btn:not(.collapsed) {
        background: linear-gradient(90deg, #8a56f2, #5a60ff);
        box-shadow: 0 0 20px rgba(138,86,242,0.4);
    }

    .bidang-btn:hover {
        transform: scale(1.03);
    }

    .accordion-body {
        background: #fff;
        color: #555;
        font-family: 'Poppins', sans-serif;
        animation: fadeInUp 0.6s ease forwards;
    }

    /* === CTA SECTION === */
    .cta-section {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        background: linear-gradient(135deg, #5a60ff, #8a56f2);
        color: white;
        text-align: center;
        padding: 90px 20px;
        margin-top: 80px;
        animation: fadeInUp 1.5s ease;
    }

    .cta-section::before {
        content: "";
        position: absolute;
        width: 200%;
        height: 200%;
        top: -50%;
        left: -50%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        animation: rotateGlow 8s linear infinite;
    }

    @keyframes rotateGlow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .cta-section .btn {
        background-color: #fff;
        color: #5a60ff;
        border-radius: 30px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        z-index: 2;
        position: relative;
    }

    .cta-section .btn:hover {
        transform: scale(1.05);
        background-color: #eef0ff;
    }

    @media (max-width: 768px) {
        .hero-content { flex-direction: column; text-align: center; }
        .hero-text h1 { font-size: 2.2rem; }
        .hero-text p { font-size: 1rem; }
    }
</style>

<!-- === HERO SECTION === -->
<section class="hero" data-aos="fade-up">
    <div class="hero-content">
        <div class="hero-text">
            <h1>Selamat Datang di <strong>SIMGANG</strong></h1>
            <p>Sistem Informasi Magang Dinas Komunikasi dan Informatika Kabupaten Cilacap</p>
            <a href="{{ route('login') }}" class="btn shadow-lg">
                <i class="fas fa-sign-in-alt me-2"></i> Masuk Sistem
            </a>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/cilacap_bercahaya.jpg') }}?v={{ time() }}" alt="Alun-Alun Cilacap Bercahaya">
        </div>
    </div>
</section>

<div class="container mt-5 mb-5" data-aos="zoom-in">
    <div class="section-title text-center mb-5">
        <h3>Data Statistik Magang</h3>
        <p class="text-muted">Ikhtisar kegiatan magang yang berlangsung di Diskominfo Cilacap</p>
    </div>

    <div class="row g-4 justify-content-center">
        @php
            $cards = [
                ['color' => 'linear-gradient(135deg,#4568dc,#6157ff)', 'icon' => 'fa-user-graduate', 'label' => 'Peserta Aktif', 'value' => $siswaAktif ?? 0, 'text' => 'text-primary'],
                ['color' => 'linear-gradient(135deg,#ffb347,#ffcc33)', 'icon' => 'fa-hourglass-half', 'label' => 'Menunggu Konfirmasi', 'value' => $siswaMenunggu ?? 0, 'text' => 'text-warning'],
                ['color' => 'linear-gradient(135deg,#00c6ff,#0072ff)', 'icon' => 'fa-school', 'label' => 'Lembaga Pendidikan', 'value' => $sekolah ?? 0, 'text' => 'text-info'],
                ['color' => 'linear-gradient(135deg,#43e97b,#38f9d7)', 'icon' => 'fa-check-circle', 'label' => 'Lulus Magang', 'value' => $siswaLulus ?? 0, 'text' => 'text-success']
            ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 150 }}">
            <div class="card text-center info-card p-3">
                <div class="icon-circle" style="--bg: {{ $card['color'] }}; background: var(--bg);"><i class="fas {{ $card['icon'] }}"></i></div>
                <h6 class="fw-bold {{ $card['text'] }}">{{ $card['label'] }}</h6>
                <h3 class="fw-bold">{{ $card['value'] }}</h3>
            </div>
        </div>
        @endforeach
    </div> 

    <div class="row justify-content-center mt-4" data-aos="fade-up" data-aos-delay="600">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
                <div class="card-body p-4 text-center text-white" 
     style="background: {{ $sisaKuota > 0 ? 'linear-gradient(135deg, #5a60ff, #8a56f2)' : 'linear-gradient(135deg, #ff6b6b, #ee0979)' }};">
                    <h4 class="fw-bold mb-2">
                        <i class="fas fa-ticket-alt me-2"></i> Ketersediaan Kuota Magang
                    </h4>
                    
                    @if($sisaKuota > 0)
                        <h1 class="display-4 fw-bold my-2">{{ $sisaKuota }}</h1>
                        <p class="mb-0 fs-5">Kursi Tersedia dari total {{ $kapasitasMaksimal }} kapasitas</p>
                        <small style="opacity: 0.8;">Segera daftar sebelum penuh!</small>
                    @else
                        <h1 class="display-4 fw-bold my-2">PENUH</h1>
                        <p class="mb-0 fs-5">Mohon maaf, kuota magang saat ini sudah terisi penuh.</p>
                        <small style="opacity: 0.8;">Silakan cek kembali di lain waktu.</small>
                    @endif

                </div>
            </div>
        </div>
    </div>
    </div>

<!-- === INFORMASI BIDANG DISKOMINFO (hyperactive) === -->
<div class="container mt-5 mb-5" data-aos="fade-up">
    <h3 class="fw-bold text-center mb-4 animate__animated animate__pulse" style="color:#5a60ff;">
        Informasi Bidang di Diskominfo
    </h3>

    <div class="accordion" id="accordionBidang">
        @foreach ($bidangInfo as $bidang)
        <div class="accordion-item bidang-card shadow-lg mb-4 rounded-4 border-0">
            <h2 class="accordion-header" id="heading{{ $bidang['id'] }}">
                <button class="accordion-button collapsed bidang-btn" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $bidang['id'] }}"
                    aria-expanded="false" aria-controls="collapse{{ $bidang['id'] }}">
                    <i class="fas {{ $bidang['icon'] }} me-2"></i>
                    <span>{{ $bidang['title'] }}</span>
                </button>
            </h2>
            <div id="collapse{{ $bidang['id'] }}" class="accordion-collapse collapse"
                aria-labelledby="heading{{ $bidang['id'] }}" data-bs-parent="#accordionBidang">
                <div class="accordion-body bidang-body">
                    <p>{{ $bidang['desc'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- === CTA === -->
<section class="cta-section" data-aos="fade-up">
    <h2 class="fw-bold mb-3">Ingin Bergabung dalam Program Magang?</h2>
    <p class="mb-4">Raih pengalaman berharga bersama Diskominfo Cilacap dan jadi bagian dari transformasi digital daerah.</p>
    <a href="{{ route('register') }}" class="btn btn-lg shadow-lg px-4">
        <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
    </a>
</section>

<!-- AOS Animation Script -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 1000, once: true });</script>
@endsection
