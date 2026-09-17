<!DOCTYPE html>
<html lang="id" translate="no">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Landing')</title>
  <link rel="icon" href="{{ asset('images/logo-kominfo.png') }}" type="image/png">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet" />
  <!-- Animate.css -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />

  <style>
  /* 🌈 Global */
  body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    font-family: 'Quicksand', 'Poppins', sans-serif;
    background: linear-gradient(135deg, #e6e9ff, #f8faff);
    overflow-x: hidden;
    scroll-behavior: smooth;
  }

  main {
    flex: 1;
    animation: fadeInUp 1s ease;
  }

  /* ✨ Navbar futuristik glassmorphism */
  .navbar-custom {
    background: rgba(90, 96, 255, 0.85);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
    position: sticky;
    top: 0;
    z-index: 50;
    transition: all 0.4s ease;
  }

  .navbar-custom.scrolled {
    background: rgba(50, 56, 180, 0.95);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  }

  .navbar-brand {
    font-weight: 700;
    font-size: 1.4rem;
    letter-spacing: 1px;
    color: #fff !important;
    text-shadow: 0 2px 6px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
    gap: 10px;
  }

  /* 🔥 Logo SIMGANG dengan ukuran yang sesuai */
  .navbar-brand img {
    height: 60px;
    max-height: 60px;
    object-fit: contain;
    margin-right: 8px;
  }

  .navbar-custom .nav-link {
    color: #fff !important;
    font-weight: 600;
    position: relative;
    overflow: hidden;
    padding: 10px 16px;
    transition: all 0.3s ease;
  }

  .navbar-custom .nav-link::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%) scaleX(0);
    transform-origin: center;
    width: 60%;
    height: 2px;
    background: #ffd369;
    transition: transform 0.3s ease;
  }

  .navbar-custom .nav-link:hover::after {
    transform: translateX(-50%) scaleX(1);
  }

  .navbar-custom .nav-link:hover {
    color: #ffd369 !important;
    transform: translateY(-2px);
  }

  /* Active nav link underline */
  .navbar-custom .nav-link.active {
    color: #ffd369 !important;
  }

  .navbar-custom .nav-link.active::after {
    transform: translateX(-50%) scaleX(1);
  }

  /* 🎨 Dropdown menu styling */
  .dropdown-menu {
    background: rgba(90, 96, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 8px 0;
  }

  .dropdown-menu .dropdown-item {
    color: #fff;
    padding: 10px 16px;
    transition: all 0.3s ease;
  }

  .dropdown-menu .dropdown-item:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #ffd369;
    transform: translateX(4px);
  }

  .dropdown-menu .dropdown-item.text-danger:hover {
    background: rgba(255, 107, 107, 0.3);
  }

  .dropdown-divider {
    border-color: rgba(255, 255, 255, 0.15);
  }
  .btn-primary {
    background: linear-gradient(135deg, #5a60ff, #8a56f2);
    border: none;
    border-radius: 12px;
    transition: all 0.4s ease-in-out;
    font-weight: 600;
    padding: 10px 20px;
  }

  .btn-primary:hover {
    background: linear-gradient(135deg, #8a56f2, #5a60ff);
    transform: scale(1.08) rotate(-1deg);
    box-shadow: 0 0 15px rgba(90, 96, 255, 0.5);
  }

  /* ✨ Animasi lembut */
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(25px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Footer */
  footer {
    background: radial-gradient(circle at 30% 90%, #5a60ff, #8a56f2);
    color: #fff;
    padding: 60px 0 25px 0;
    position: relative;
    overflow: hidden;
    animation: fadeInUp 1.2s ease;
  }

  footer::before {
    content: "";
    position: absolute;
    top: -30%;
    left: -20%;
    width: 150%;
    height: 200%;
    background: conic-gradient(from 180deg, rgba(255,255,255,0.05), transparent 60%);
    animation: rotate 12s linear infinite;
  }

  @keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }

  footer .footer-content {
    position: relative;
    z-index: 2;
  }

  footer img {
    transition: transform 0.4s ease, filter 0.4s ease;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
  }

  footer img:hover {
    transform: scale(1.15);
    filter: brightness(1.3);
  }

  footer p {
    font-size: 0.95rem;
    color: #f1f1f1;
    letter-spacing: 0.5px;
  }

  [data-animate] {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.8s ease-out;
  }

  [data-animate].active {
    opacity: 1;
    transform: translateY(0);
  }

  /* Responsive dropdown */
  @media (max-width: 992px) {
    .dropdown-menu {
      background: rgba(90, 96, 255, 0.98);
      border-radius: 8px;
    }

    .navbar-toggler {
      border-color: rgba(255, 255, 255, 0.5);
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255,255,255,1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
  }

</style>
</head>
<body>

  <!-- 🌐 Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom animate__animated animate__fadeInDown">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ asset('images/simaganglogo.png') }}" alt="Logo SIMGANG" height="60" class="me-2" />
        SIMGANG
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('history') ? 'active' : '' }}" href="{{ route('history') }}">Riwayat</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('landing-calendar') ? 'active' : '' }}" href="{{ route('landing-calendar') }}"><i class="fas fa-calendar-alt me-1"></i>Kalender Magang</a></li>
          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->username }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                @if(Auth::user()->role === 'siswa')
                  <li><a class="dropdown-item" href="{{ route('siswa.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                @elseif(Auth::user()->role === 'mentor')
                  <li><a class="dropdown-item" href="{{ route('mentor.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                @else
                  <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="{{ route('keluar') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
              </ul>
            </li>
          @else
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>

  <!-- ✨ Content -->
  <main class="container mt-5">
    @yield('content')
  </main>

  <!-- 🌈 Footer -->
  <footer class="text-center mt-5">
    <div class="footer-content">
      <div class="d-flex justify-content-center align-items-center gap-4 mb-3">
        <img src="{{ asset('images/logo-dinascilacap.png') }}" alt="Dinas Cilacap" height="55" />
        <img src="{{ asset('images/logo-kominfo.png') }}" alt="Kominfo" height="55" />
      </div>
      <p>&copy; 2026 Diskominfo Cilacap — <strong>Sistem Informasi Magang</strong></p>
    </div>
  </footer>

  <!-- Script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- SweetAlert2 untuk notifikasi yang lebih baik -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <!-- Helper Notifikasi -->
  <script src="{{ asset('js/notif-helper.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showSuccessToast("{!! addslashes(session('success')) !!}");
        @endif
        @if(session('error'))
            showErrorToast("{!! addslashes(session('error')) !!}");
        @endif
        @if(session('warning'))
            showWarningToast("{!! addslashes(session('warning')) !!}");
        @endif
        @if(session('info'))
            showInfoToast("{!! addslashes(session('info')) !!}");
        @endif
    });
  </script>

  <script>
    // ✨ Navbar scroll effect
    window.addEventListener('scroll', () => {
      const nav = document.querySelector('.navbar-custom');
      nav.classList.toggle('scrolled', window.scrollY > 60);
    });

    // ✨ Scroll animation trigger
    const animateElements = document.querySelectorAll('[data-animate]');
    const scrollAnimate = () => {
      animateElements.forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) el.classList.add('active');
      });
    };
    window.addEventListener('scroll', scrollAnimate);
    scrollAnimate();
  </script>
</body>
</html>
