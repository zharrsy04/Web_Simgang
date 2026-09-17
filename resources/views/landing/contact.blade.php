@extends('layouts.landing')

@section('title', 'Kontak Kami')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;600&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(180deg, #f7faff 0%, #ffffff 100%);
        overflow-x: hidden;
    }

    /* === SECTION === */
    .contact-section {
        padding: 90px 0;
        animation: fadeInUp 1s ease;
    }

    .contact-title {
        font-weight: 700;
        color: #5a60ff;
        text-align: center;
        font-size: 2.6rem;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .contact-subtitle {
        text-align: center;
        color: #6c757d;
        margin-bottom: 3rem;
        font-size: 1.05rem;
    }

    /* === CARD STYLE === */
    .contact-card {
        background: linear-gradient(145deg, #ffffff, #f5f7ff);
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(90, 96, 255, 0.15);
        padding: 28px;
        transition: all 0.5s ease;
        position: relative;
        overflow: hidden;
        transform: translateY(30px);
        opacity: 0;
        z-index: 2; 
    }

    .contact-card.visible {
        transform: translateY(0);
        opacity: 1;
    }

    .contact-card:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 12px 28px rgba(90, 96, 255, 0.25);
    }

    .contact-card::before {
        content: "";
        position: absolute;
        top: -60%;
        left: -60%;
        width: 220%;
        height: 220%;
        background: radial-gradient(circle, rgba(90, 96, 255, 0.1), transparent 70%);
        transform: rotate(45deg);
        transition: all 0.6s ease;
        pointer-events: none;
    }

    .contact-card:hover::before {
        top: -40%;
        left: -40%;
        background: radial-gradient(circle, rgba(138, 86, 242, 0.15), transparent 70%);
    }

    .card-title {
        font-weight: 700;
        color: #5a60ff;
        font-size: 1.25rem;
        margin-bottom: 15px;
    }

    .contact-info p {
        margin-bottom: 0.7rem;
        color: #444;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
    }

    .contact-info i {
        width: 25px;
        color: #5a60ff;
        margin-right: 10px;
        font-size: 1.05rem;
        transition: none;
    }

    .contact-link a {
        color: #5a60ff;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .contact-link a:hover {
        color: #8a56f2;
        text-decoration: underline;
    }

    /* === MAP SECTION === */
    .map-wrapper {
        margin-top: 3rem;
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        transform: scale(0.9);
        opacity: 0;
        transition: all 0.8s ease;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .map-wrapper.visible {
        transform: scale(1);
        opacity: 1;
    }

    .map-wrapper iframe {
        width: 100%;
        height: 380px;
        border: none;
        transition: transform 0.6s ease;
    }

    .map-wrapper.zoom iframe {
        transform: scale(1.2);
        height: 480px;
    }

    .map-hint {
        position: absolute;
        bottom: 10px;
        right: 15px;
        background: rgba(0, 0, 0, 0.65);
        color: #fff;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 0.85rem;
        backdrop-filter: blur(3px);
    }

    /* === ANIMATIONS === */
    @keyframes fadeInUp {
        from {opacity: 0; transform: translateY(40px);}
        to {opacity: 1; transform: translateY(0);}
    }

    @keyframes fadeIn {
        from {opacity: 0;}
        to {opacity: 1;}
    }

    @keyframes neonGlow {
        0% {text-shadow: 0 0 10px rgba(90,96,255,0.4);}
        100% {text-shadow: 0 0 20px rgba(138,86,242,0.8);}
    }



    @media (max-width: 768px) {
        .contact-title { font-size: 2rem; }
        .map-wrapper iframe { height: 300px; }
    }
</style>

<div class="contact-section container">
    <h2 class="contact-title">Hubungi Kami 💬</h2>
    <p class="contact-subtitle">Kami siap membantu Anda mendapatkan informasi terkait Diskominfo Cilacap.</p>

    <div class="row g-4">
        <!-- === ALAMAT === -->
        <div class="col-md-6">
            <div class="card contact-card p-4 h-100">
                <h5 class="card-title"><i class="fas fa-map-marker-alt text-danger me-2"></i>Alamat Kantor</h5>
                <div class="contact-info">
                    <p><i class="fas fa-location-arrow"></i> Jalan Sindoro Nomor 36</p>
                    <p><i class="fas fa-city"></i> Kelurahan Sidanegara, Kecamatan Cilacap Tengah</p>
                    <p><i class="fas fa-map"></i> Kabupaten Cilacap, Provinsi Jawa Tengah</p>
                    <p><i class="fas fa-mail-bulk"></i> Kode Pos: <strong>53223</strong></p>
                </div>
            </div>
        </div>

        <!-- === KONTAK === -->
        <div class="col-md-6">
            <div class="card contact-card p-4 h-100">
                <h5 class="card-title"><i class="fas fa-phone-alt text-success me-2"></i>Contact Info</h5>
                <div class="contact-info">
                    <p><i class="fas fa-phone"></i> 0282 - 5563111</p>
                    <p><i class="fas fa-clock"></i> Senin - Kamis: 07.15 - 15.45 WIB</p>
                    <p><i class="fas fa-clock"></i> Jum'at: 07.00 - 15.00 WIB</p>
                    <p class="contact-link">
                        <i class="fas fa-globe"></i>
                        <a href="https://kominfo.cilacapkab.go.id" target="_blank">
                            kominfo.cilacapkab.go.id
                        </a>
                    </p>
                    <p><i class="fas fa-envelope"></i> kominfo@cilacapkab.go.id</p>
                    <p><i class="fab fa-twitter text-info"></i> @kominfo_cilacap</p>
                </div>
            </div>
        </div>
    </div>

    <!-- === MAP === -->
    <div class="text-center mt-5">
        <h5 class="fw-bold text-primary mb-3 animate__animated animate__fadeInUp">
            <i class="fas fa-map-location-dot me-2"></i> Lokasi Kantor Diskominfo Cilacap
        </h5>
        <div class="map-wrapper" id="mapWrapper">
            <iframe
                id="mapFrame"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.6371397665644!2d109.00603787380417!3d-7.722024576513159!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e651284d9f66a4f%3A0x7fa471d5a38e57e7!2sDinas%20Komunikasi%20dan%20Informatika%20Kabupaten%20Cilacap!5e0!3m2!1sid!2sid!4v1759884568767!5m2!1sid!2sid"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
            <div class="map-hint">Klik untuk memperbesar peta</div>
        </div>
    </div>
</div>

<!-- Animate Scroll Reveal -->
<script>
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('visible');
        });
    }, { threshold: 0.2 });

    document.querySelectorAll('.contact-card, .map-wrapper').forEach(el => observer.observe(el));

    const mapWrapper = document.getElementById('mapWrapper');
    mapWrapper.addEventListener('click', () => mapWrapper.classList.toggle('zoom'));
</script>

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection
