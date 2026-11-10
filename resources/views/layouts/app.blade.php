<!DOCTYPE html>
<html lang="id" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('images/logo-kominfo.png') }}" type="image/png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

    <!-- Link ke file CSS dashboard -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Animate.css untuk animasi toast SweetAlert2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <style>
    /* === SIMGANG SweetAlert2 Custom Theme === */
    .simgang-toast-popup {
        border-left: 4px solid #5a60ff !important;
        border-radius: 10px !important;
        box-shadow: 0 8px 30px rgba(90, 96, 255, 0.18) !important;
        font-family: 'Poppins', sans-serif !important;
        min-width: 280px !important;
    }
    .simgang-toast-popup.swal2-icon-success {
        border-left-color: #2e7d32 !important;
    }
    .simgang-toast-popup.swal2-icon-error {
        border-left-color: #dc3545 !important;
    }
    .simgang-toast-popup.swal2-icon-warning {
        border-left-color: #f9a825 !important;
    }
    .simgang-toast-popup.swal2-icon-info {
        border-left-color: #0288d1 !important;
    }
    .swal2-timer-progress-bar {
        background: #5a60ff !important;
    }
    .simgang-confirm-popup {
        border-radius: 16px !important;
        font-family: 'Poppins', sans-serif !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18) !important;
    }
    .simgang-confirm-title {
        font-weight: 700 !important;
        color: #1f2937 !important;
        font-size: 1.2rem !important;
    }
    .simgang-confirm-text {
        color: #4b5563 !important;
        font-size: 0.95rem !important;
    }
    .swal2-actions {
        gap: 8px !important;
    }
    /* Sidebar notification badge */
    .sidebar-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 18px;
        height: 18px;
        padding: 0 5px;
        border-radius: 9px;
        background-color: #ef4444;
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        line-height: 1;
        flex-shrink: 0;
        animation: badge-pulse 2s infinite;
    }
    @keyframes badge-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    </style>

    @yield('styles')
</head>

<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <!-- Logo Section - Fixed Top -->
        <ul class="sidebar-logo-section">
            <li class="title-sidebar">
                <div class="logo">
                    <img src="{{ asset('images/LogoBaruSimgang.png') }}" alt="Logo">
                    <span class="logo-text">SIMGANG</span>
                </div>
                <!-- <button id="toggle-btn" onclick="toggleSidebar()"> -->
                <button id="toggle-btn" onclick="toggleSidebar()" aria-label="Toggle Sidebar">

                    <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                        <path d="M120-240v-80h720v80H120Zm0-240v-80h720v80H120Zm0-240v-80h720v80H120Z"/>
                    </svg>
                    <svg id="close-icon" style="display: none;" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                        <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
                    </svg>
                </button>

            </li>
        </ul>

        <!-- Navigation Section - Scrollable Middle -->
        <ul class="sidebar-nav-section">

            <!-- Checking user role -->
            @if (Auth::check() && Auth::user()->role == 'siswa')
                <!-- Sidebar for 'siswa' -->
                <li class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('siswa.dashboard') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M520-640v-160q0-17 11.5-28.5T560-840h240q17 0 28.5 11.5T840-800v160q0 17-11.5 28.5T800-600H560q-17 0-28.5-11.5T520-640ZM120-480v-320q0-17 11.5-28.5T160-840h240q17 0 28.5 11.5T440-800v320q0 17-11.5 28.5T400-440H160q-17 0-28.5-11.5T120-480Zm400 320v-320q0-17 11.5-28.5T560-520h240q17 0 28.5 11.5T840-480v320q0 17-11.5 28.5T800-120H560q-17 0-28.5-11.5T520-160Zm-400 0v-160q0-17 11.5-28.5T160-360h240q17 0 28.5 11.5T440-320v160q0 17-11.5 28.5T400-120H160q-17 0-28.5-11.5T120-160Z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('siswa.absensi.*') ? 'active' : '' }}">
                    <a href="{{ route('siswa.absensi.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z" />
                        </svg>
                        <span>Absensi</span>
                    </a>
                </li>

                {{-- Logbook Kegiatan (Siswa) --}}
                <li class="{{ request()->routeIs('siswa.logbook.*') ? 'active' : '' }}">
                    <a href="{{ route('siswa.logbook.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M300-320q17 0 28.5-11.5T340-360q0-17-11.5-28.5T300-400q-17 0-28.5 11.5T260-360q0 17 11.5 28.5T300-320Zm0-160q17 0 28.5-11.5T340-520q0-17-11.5-28.5T300-560q-17 0-28.5 11.5T260-520q0 17 11.5 28.5T300-480Zm0-160q17 0 28.5-11.5T340-680q0-17-11.5-28.5T300-720q-17 0-28.5 11.5T260-680q0 17 11.5 28.5T300-640Zm120 320h280q17 0 28.5-11.5T740-360q0-17-11.5-28.5T700-400H420q-17 0-28.5 11.5T380-360q0 17 11.5 28.5T420-320Zm0-160h280q17 0 28.5-11.5T740-520q0-17-11.5-28.5T700-560H420q-17 0-28.5 11.5T380-520q0 17 11.5 28.5T420-480Zm0-160h280q17 0 28.5-11.5T740-680q0-17-11.5-28.5T700-720H420q-17 0-28.5 11.5T380-680q0 17 11.5 28.5T420-640ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0 0v-560 560Z"/>
                        </svg>
                        <span>Logbook Kegiatan</span>
                    </a>
                </li>


                
                <li class="{{ request()->routeIs('siswa.profil', 'siswa.nilai', 'siswa.shownilai', 'siswa.tasks.*') ? 'active' : '' }}">
                    <button onclick="toggleSubMenu(this)" class="dropdown-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q66 0 130 15.5T736-378q29 15 46.5 43.5T800-272v112H160Zm80-80h480v-32q0-11-5.5-20T700-306q-54-27-109-40.5T480-360q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z" />
                        </svg>
                        <span>Siswa</span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor" class="dropdown-icon">
                            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
                        </svg>
                    </button>
                    <ul class="sub-menu">
                        <li class="{{ request()->routeIs('siswa.profil') ? 'active' : '' }}">
                            <a href="{{ route('siswa.profil') }}">
                                <i class="fas fa-user"></i>
                                <span>Profil</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('siswa.nilai', 'siswa.shownilai') ? 'active' : '' }}">
                            <a href="{{ route('siswa.nilai') }}"> <i class="fas fa-chart-line"></i>
                                <span>Nilai Sertifikat</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('siswa.tasks.index', 'siswa.tasks.show') ? 'active' : '' }}">
                            <a href="{{ route('siswa.tasks.index') }}">
                                <i class="fas fa-tasks"></i>
                                <span>Tugas Saya</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
            @elseif(Auth::check() && Auth::user()->role == 'mentor')
                @php
                    $sidebarFrom = request()->query('from') ?? old('from');
                    $isMentorFormWithFrom = request()->routeIs('mentor.create', 'mentor.edit') && filled($sidebarFrom);
                    // Pastikan $sertifikats tersedia untuk sidebar yang membutuhkannya
                    if (!isset($sertifikats)) {
                        $sertifikats = collect();
                    }
                @endphp
                <!-- Sidebar for 'mentor' -->
                <li class="{{ request()->routeIs('mentor.dashboard', 'dashboard.statistik.magang') ? 'active' : '' }}">
                    <a href="{{ route('mentor.dashboard') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M520-640v-160q0-17 11.5-28.5T560-840h240q17 0 28.5 11.5T840-800v160q0 17-11.5 28.5T800-600H560q-17 0-28.5-11.5T520-640ZM120-480v-320q0-17 11.5-28.5T160-840h240q17 0 28.5 11.5T440-800v320q0 17-11.5 28.5T400-440H160q-17 0-28.5-11.5T120-480Zm400 320v-320q0-17 11.5-28.5T560-520h240q17 0 28.5 11.5T840-480v320q0 17-11.5 28.5T800-120H560q-17 0-28.5-11.5T520-160Zm-400 0v-160q0-17 11.5-28.5T160-360h240q17 0 28.5 11.5T440-320v160q0 17-11.5 28.5T400-120H160q-17 0-28.5-11.5T120-160Z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('mentor.profile') ? 'active' : '' }}">
                    <a href="{{ route('mentor.profile') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-32q0-34 17.5-62.5T224-302q62-31 126-46.5T480-364q66 0 130 15.5T736-302q29 14 46.5 42.5T800-192v32H160Zm80-80h480v-16q0-13-7-25t-19-17q-50-25-104-37.5T480-348q-56 0-110 12.5T266-298q-12 5-19 17t-7 25v16Zm240-320q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Zm0 400Z" />
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                </li>

                {{-- Lembaga Pendidikan --}}
                <li class="{{ request()->routeIs('dashboard.sekolah') || ($isMentorFormWithFrom && $sidebarFrom === 'sekolah') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.sekolah') }}">
                        <i class="fas fa-school"></i>
                        <span>Lembaga Pendidikan</span>
                    </a>
                </li>

                {{-- Data Siswa & Pengajuan (submenu) --}}
                <li class="{{ request()->routeIs('mentor.verifikasi-pengajuan', 'mentor.detail-pengajuan', 'dashboard.siswaaktif', 'dashboard.siswalulus', 'mentor.index', 'mentor.create', 'mentor.edit') && !$isMentorFormWithFrom ? 'active' : '' }}">
                    <button onclick="toggleSubMenu(this)" class="dropdown-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M0-240v-63q0-43 44-70t116-27q13 0 25 .5t23 2.5q-14 21-21 44t-7 48v65H0Zm240 0v-65q0-32 17.5-58.5T307-410q-8-3-15.5-4t-16.5-1q-45 0-72.5 23T240-327v87H0v-63q0-43 44-70t116-27q13 0 25 .5t23 2.5q-14 21-21 44t-7 48v65h240Zm0-120q33 0 56.5-23.5T320-440q0-33-23.5-56.5T240-520q-33 0-56.5 23.5T160-440q0 33 23.5 56.5T240-360Zm480 120v-65q0-32 .5-56t3.5-43q11-2 22.5-2.5t24.5-.5q72 0 116 27t44 70v63H720Zm-240-120q33 0 56.5-23.5T560-440q0-33-23.5-56.5T480-520q-33 0-56.5 23.5T400-440q0 33 23.5 56.5T480-360Zm0-80q-17 0-28.5-11.5T440-480q0-17 11.5-28.5T480-520q17 0 28.5 11.5T520-480q0 17-11.5 28.5T480-440Zm240 200v-65q0-25-6.5-47.5T700-410q19-4 35-6t37-2q72 0 116 27t44 70v63H720ZM240-360q-17 0-28.5-11.5T200-400q0-17 11.5-28.5T240-440q17 0 28.5 11.5T280-400q0 17-11.5 28.5T240-360Z" />
                        </svg>
                        <span>Data Siswa & Pengajuan</span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor" class="dropdown-icon">
                            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
                        </svg>
                    </button>
                    <ul class="sub-menu">
                        <li class="{{ request()->routeIs('mentor.verifikasi-pengajuan', 'mentor.detail-pengajuan') ? 'active' : '' }}">
                            <a href="{{ route('mentor.verifikasi-pengajuan') }}" style="display:flex;align-items:center;gap:6px;">
                                <i class="fas fa-check-circle"></i>
                                <span>Verifikasi Pengajuan</span>
                                <span id="badge-pengajuan" class="sidebar-badge" style="display:none;"></span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('dashboard.siswaaktif') || ($isMentorFormWithFrom && $sidebarFrom === 'siswaaktif') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.siswaaktif') }}">
                                <i class="fas fa-users"></i>
                                <span>Peserta Magang Aktif</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('dashboard.siswalulus') || ($isMentorFormWithFrom && $sidebarFrom === 'siswalulus') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.siswalulus') }}">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Lulus Magang</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('mentor.index') || (request()->routeIs('mentor.create', 'mentor.edit') && !$isMentorFormWithFrom) ? 'active' : '' }}">
                            <a href="{{ route('mentor.index') }}">
                                <i class="fas fa-address-book"></i>
                                <span>Keseluruhan Peserta</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Data Mentor (submenu) --}}
                <li class="{{ request()->routeIs('mentor.data-mentor.index', 'mentor.data-mentor.create', 'mentor.data-mentor.edit', 'mentor.data-mentor.direktori') ? 'active' : '' }}">
                    <button onclick="toggleSubMenu(this)" class="dropdown-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h207q16 0 30.5 6t25.5 17l57 57h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm400-160v-40q0-17 11.5-28.5T600-320q17 0 28.5-11.5T640-360v-40h40q17 0 28.5-11.5T720-440q0-17-11.5-28.5T680-480h-40v-40q0-17-11.5-28.5T600-560q-17 0-28.5 11.5T560-520v40h-40q-17 0-28.5 11.5T480-440q0 17 11.5 28.5T520-400h40Z" />
                        </svg>
                        <span>Data Mentor</span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor" class="dropdown-icon">
                            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
                        </svg>
                    </button>
                    <ul class="sub-menu">
                        <li class="{{ request()->routeIs('mentor.data-mentor.index', 'mentor.data-mentor.create', 'mentor.data-mentor.edit') ? 'active' : '' }}">
                            <a href="{{ route('mentor.data-mentor.index') }}">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span>Data Mentor</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('mentor.data-mentor.direktori') ? 'active' : '' }}">
                            <a href="{{ route('mentor.data-mentor.direktori') }}">
                                <i class="fas fa-address-book"></i>
                                <span>Direktori Mentor</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Tugas (standalone) --}}
                <li class="{{ request()->routeIs('mentor.tasks.daftar-tugas', 'mentor.tasks.tugas-siswa') ? 'active' : '' }}">
                    <a href="{{ route('mentor.tasks.daftar-tugas') }}" style="display:flex;align-items:center;gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h207q16 0 30.5 6t25.5 17l57 57h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm400-160v-40q0-17 11.5-28.5T600-320q17 0 28.5-11.5T640-360v-40h40q17 0 28.5-11.5T720-440q0-17-11.5-28.5T680-480h-40v-40q0-17-11.5-28.5T600-560q-17 0-28.5 11.5T560-520v40h-40q-17 0-28.5 11.5T480-440q0 17 11.5 28.5T520-400h40Z" />
                        </svg>
                        <span>Daftar Tugas</span>
                        <span id="badge-tugas" class="sidebar-badge" style="display:none;"></span>
                    </a>
                </li>

                {{-- Absensi Siswa (standalone) --}}
                <li class="{{ request()->routeIs('mentor.absensi.*') ? 'active' : '' }}">
                    <a href="{{ route('mentor.absensi.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z" />
                        </svg>
                        <span>Absensi Siswa</span>
                    </a>
                </li>

                {{-- Logbook Siswa (standalone) --}}
                <li class="{{ request()->routeIs('mentor.logbook.*') ? 'active' : '' }}">
                    <a href="{{ route('mentor.logbook.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M300-320q17 0 28.5-11.5T340-360q0-17-11.5-28.5T300-400q-17 0-28.5 11.5T260-360q0 17 11.5 28.5T300-320Zm0-160q17 0 28.5-11.5T340-520q0-17-11.5-28.5T300-560q-17 0-28.5 11.5T260-520q0 17 11.5 28.5T300-480Zm0-160q17 0 28.5-11.5T340-680q0-17-11.5-28.5T300-720q-17 0-28.5 11.5T260-680q0 17 11.5 28.5T300-640Zm120 320h280q17 0 28.5-11.5T740-360q0-17-11.5-28.5T700-400H420q-17 0-28.5 11.5T380-360q0 17 11.5 28.5T420-320Zm0-160h280q17 0 28.5-11.5T740-520q0-17-11.5-28.5T700-560H420q-17 0-28.5 11.5T380-520q0 17 11.5 28.5T420-480Zm0-160h280q17 0 28.5-11.5T740-680q0-17-11.5-28.5T700-720H420q-17 0-28.5 11.5T380-680q0 17 11.5 28.5T420-640ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0 0v-560 560Z"/>
                        </svg>
                        <span>Logbook Siswa</span>
                    </a>
                </li>

                {{-- Monitoring Presensi (standalone) --}}
                <li class="{{ request()->routeIs('presensi.monitoring') ? 'active' : '' }}">
                    <a href="{{ route('presensi.monitoring') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z" />
                        </svg>
                        <span>Monitoring Presensi</span>
                    </a>
                </li>

                {{-- Sistem (submenu) --}}
                <li class="{{ request()->routeIs('user.*', 'penilaian.*', 'siswa.shownilai', 'siswa.nilai', 'siswa.nilai.show') ? 'active' : '' }}">
                    <button onclick="toggleSubMenu(this)" class="dropdown-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M480-120q-75 0-140.5-28.5t-114-77q-48.5-48.5-77-114T120-480q0-75 28.5-140.5t77-114q48.5-48.5 114-77T480-840q75 0 140.5 28.5t114 77q48.5 48.5 77 114T840-480q0 75-28.5 140.5t-77 114q-48.5 48.5-114 77T480-120Zm-66-210L306-470q-11-11-28-11t-28 11q-11 11-11 28t11 28l134 134q12 12 28 12t28-12l262-262q11-11 11-28t-11-28q-11-11-28-11t-28 11L414-330Z" />
                        </svg>
                        <span>Sistem</span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor" class="dropdown-icon">
                            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
                        </svg>
                    </button>
                    <ul class="sub-menu">
                        <li class="{{ request()->routeIs('user.*') ? 'active' : '' }}">
                            <a href="{{ route('user.index') }}">
                                <i class="fas fa-users-cog"></i>
                                <span>Pengguna</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('penilaian.index', 'penilaian.create', 'penilaian.store', 'penilaian.show', 'penilaian.edit', 'penilaian.update', 'penilaian.destroy', 'siswa.shownilai', 'siswa.nilai', 'siswa.nilai.show') ? 'active' : '' }}">
                            <a href="{{ route('penilaian.index') }}">
                                <i class="fas fa-chart-line"></i>
                                <span>Penilaian</span>
                            </a>
                        </li>
                    </ul>
                </li>

            @elseif(Auth::check() && Auth::user()->role == 'sekretariat')
                @php
                    $sidebarFrom = request()->query('from') ?? old('from');
                    $isMentorFormWithFrom = request()->routeIs('mentor.create', 'mentor.edit') && filled($sidebarFrom);
                    // Pastikan $sertifikats tersedia untuk sidebar yang membutuhkannya
                    if (!isset($sertifikats)) {
                        $sertifikats = collect();
                    }
                @endphp
                <!-- Sidebar for 'sekretariat' (same structure as mentor) -->
                <li class="{{ request()->routeIs('sekretariat.dashboard', 'dashboard.statistik.magang') ? 'active' : '' }}">
                    <a href="{{ route('sekretariat.dashboard') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M520-640v-160q0-17 11.5-28.5T560-840h240q17 0 28.5 11.5T840-800v160q0 17-11.5 28.5T800-600H560q-17 0-28.5-11.5T520-640ZM120-480v-320q0-17 11.5-28.5T160-840h240q17 0 28.5 11.5T440-800v320q0 17-11.5 28.5T400-440H160q-17 0-28.5-11.5T120-480Zm400 320v-320q0-17 11.5-28.5T560-520h240q17 0 28.5 11.5T840-480v320q0 17-11.5 28.5T800-120H560q-17 0-28.5-11.5T520-160Zm-400 0v-160q0-17 11.5-28.5T160-360h240q17 0 28.5 11.5T440-320v160q0 17-11.5 28.5T400-120H160q-17 0-28.5-11.5T120-160Z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Antrian Sertifikat (Sekretariat only) --}}
                <li class="{{ request()->routeIs('sekretariat.antrian-sertifikat') ? 'active' : '' }}">
                    <a href="{{ route('sekretariat.antrian-sertifikat') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h207q16 0 30.5 6t25.5 17l57 57h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm400-160v-40q0-17 11.5-28.5T600-320q17 0 28.5-11.5T640-360v-40h40q17 0 28.5-11.5T720-440q0-17-11.5-28.5T680-480h-40v-40q0-17-11.5-28.5T600-560q-17 0-28.5 11.5T560-520v40h-40q-17 0-28.5 11.5T480-440q0 17 11.5 28.5T520-400h40Z" />
                        </svg>
                        <span>Antrian Sertifikat</span>
                    </a>
                </li>

                {{-- Lembaga Pendidikan (Sekretariat) --}}
                <li class="{{ request()->routeIs('dashboard.sekolah') || ($isMentorFormWithFrom && $sidebarFrom === 'sekolah') || request()->routeIs('sekretariat.instansi.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.sekolah') }}">
                        <i class="fas fa-school"></i>
                        <span>Lembaga Pendidikan</span>
                    </a>
                </li>

                {{-- Data Siswa & Pengajuan (submenu) --}}
                <li class="{{ request()->routeIs('mentor.verifikasi-pengajuan', 'mentor.detail-pengajuan', 'dashboard.siswaaktif', 'dashboard.siswalulus', 'mentor.index', 'mentor.create', 'mentor.edit') && !$isMentorFormWithFrom ? 'active' : '' }}">
                    <button onclick="toggleSubMenu(this)" class="dropdown-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M0-240v-63q0-43 44-70t116-27q13 0 25 .5t23 2.5q-14 21-21 44t-7 48v65H0Zm240 0v-65q0-32 17.5-58.5T307-410q-8-3-15.5-4t-16.5-1q-45 0-72.5 23T240-327v87H0v-63q0-43 44-70t116-27q13 0 25 .5t23 2.5q-14 21-21 44t-7 48v65h240Zm0-120q33 0 56.5-23.5T320-440q0-33-23.5-56.5T240-520q-33 0-56.5 23.5T160-440q0 33 23.5 56.5T240-360Zm480 120v-65q0-32 .5-56t3.5-43q11-2 22.5-2.5t24.5-.5q72 0 116 27t44 70v63H720Zm-240-120q33 0 56.5-23.5T560-440q0-33-23.5-56.5T480-520q-33 0-56.5 23.5T400-440q0 33 23.5 56.5T480-360Zm0-80q-17 0-28.5-11.5T440-480q0-17 11.5-28.5T480-520q17 0 28.5 11.5T520-480q0 17-11.5 28.5T480-440Zm240 200v-65q0-25-6.5-47.5T700-410q19-4 35-6t37-2q72 0 116 27t44 70v63H720ZM240-360q-17 0-28.5-11.5T200-400q0-17 11.5-28.5T240-440q17 0 28.5 11.5T280-400q0 17-11.5 28.5T240-360Z" />
                        </svg>
                        <span>Data Siswa & Pengajuan</span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor" class="dropdown-icon">
                            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
                        </svg>
                    </button>
                    <ul class="sub-menu">
                        <li class="{{ request()->routeIs('mentor.verifikasi-pengajuan', 'mentor.detail-pengajuan') ? 'active' : '' }}">
                            <a href="{{ route('mentor.verifikasi-pengajuan') }}">
                                <i class="fas fa-check-circle"></i>
                                <span>Verifikasi Pengajuan</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('dashboard.siswaaktif') || ($isMentorFormWithFrom && $sidebarFrom === 'siswaaktif') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.siswaaktif') }}">
                                <i class="fas fa-users"></i>
                                <span>Peserta Magang Aktif</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('dashboard.siswalulus') || ($isMentorFormWithFrom && $sidebarFrom === 'siswalulus') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.siswalulus') }}">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Lulus Magang</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('mentor.index') || (request()->routeIs('mentor.create', 'mentor.edit') && !$isMentorFormWithFrom) ? 'active' : '' }}">
                            <a href="{{ route('mentor.index') }}">
                                <i class="fas fa-address-book"></i>
                                <span>Keseluruhan Peserta</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Data Mentor (submenu) --}}
                <li class="{{ request()->routeIs('mentor.data-mentor.index', 'mentor.data-mentor.create', 'mentor.data-mentor.edit', 'mentor.data-mentor.direktori') ? 'active' : '' }}">
                    <button onclick="toggleSubMenu(this)" class="dropdown-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h207q16 0 30.5 6t25.5 17l57 57h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm400-160v-40q0-17 11.5-28.5T600-320q17 0 28.5-11.5T640-360v-40h40q17 0 28.5-11.5T720-440q0-17-11.5-28.5T680-480h-40v-40q0-17-11.5-28.5T600-560q-17 0-28.5 11.5T560-520v40h-40q-17 0-28.5 11.5T480-440q0 17 11.5 28.5T520-400h40Z" />
                        </svg>
                        <span>Data Mentor</span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor" class="dropdown-icon">
                            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
                        </svg>
                    </button>
                    <ul class="sub-menu">
                        <li class="{{ request()->routeIs('mentor.data-mentor.index', 'mentor.data-mentor.create', 'mentor.data-mentor.edit') ? 'active' : '' }}">
                            <a href="{{ route('mentor.data-mentor.index') }}">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span>Data Mentor</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('mentor.data-mentor.direktori') ? 'active' : '' }}">
                            <a href="{{ route('mentor.data-mentor.direktori') }}">
                                <i class="fas fa-address-book"></i>
                                <span>Direktori Mentor</span>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Tugas (standalone) --}}
                <li class="{{ request()->routeIs('mentor.tasks.daftar-tugas', 'mentor.tasks.tugas-siswa') ? 'active' : '' }}">
                    <a href="{{ route('mentor.tasks.daftar-tugas') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h207q16 0 30.5 6t25.5 17l57 57h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm400-160v-40q0-17 11.5-28.5T600-320q17 0 28.5-11.5T640-360v-40h40q17 0 28.5-11.5T720-440q0-17-11.5-28.5T680-480h-40v-40q0-17-11.5-28.5T600-560q-17 0-28.5 11.5T560-520v40h-40q-17 0-28.5 11.5T480-440q0 17 11.5 28.5T520-400h40Z" />
                        </svg>
                        <span>Daftar Tugas</span>
                    </a>
                </li>

                {{-- Absensi Siswa (standalone) --}}
                <li class="{{ request()->routeIs('mentor.absensi.*') ? 'active' : '' }}">
                    <a href="{{ route('mentor.absensi.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z" />
                        </svg>
                        <span>Absensi Siswa</span>
                    </a>
                </li>

                {{-- Logbook Siswa (standalone) --}}
                <li class="{{ request()->routeIs('mentor.logbook.*') ? 'active' : '' }}">
                    <a href="{{ route('mentor.logbook.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M300-320q17 0 28.5-11.5T340-360q0-17-11.5-28.5T300-400q-17 0-28.5 11.5T260-360q0 17 11.5 28.5T300-320Zm0-160q17 0 28.5-11.5T340-520q0-17-11.5-28.5T300-560q-17 0-28.5 11.5T260-520q0 17 11.5 28.5T300-480Zm0-160q17 0 28.5-11.5T340-680q0-17-11.5-28.5T300-720q-17 0-28.5 11.5T260-680q0 17 11.5 28.5T300-640Zm120 320h280q17 0 28.5-11.5T740-360q0-17-11.5-28.5T700-400H420q-17 0-28.5 11.5T380-360q0 17 11.5 28.5T420-320Zm0-160h280q17 0 28.5-11.5T740-520q0-17-11.5-28.5T700-560H420q-17 0-28.5 11.5T380-520q0 17 11.5 28.5T420-480Zm0-160h280q17 0 28.5-11.5T740-680q0-17-11.5-28.5T700-720H420q-17 0-28.5 11.5T380-680q0 17 11.5 28.5T420-640ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0 0v-560 560Z"/>
                        </svg>
                        <span>Logbook Siswa</span>
                    </a>
                </li>

                {{-- Monitoring Presensi (standalone) --}}
                <li class="{{ request()->routeIs('presensi.monitoring') ? 'active' : '' }}">
                    <a href="{{ route('presensi.monitoring') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Zm280 240q-17 0-28.5-11.5T440-440q0-17 11.5-28.5T480-480q17 0 28.5 11.5T520-440q0 17-11.5 28.5T480-400Zm-160 0q-17 0-28.5-11.5T280-440q0-17 11.5-28.5T320-480q17 0 28.5 11.5T360-440q0 17-11.5 28.5T320-400Zm320 0q-17 0-28.5-11.5T600-440q0-17 11.5-28.5T640-480q17 0 28.5 11.5T680-440q0 17-11.5 28.5T640-400ZM480-240q-17 0-28.5-11.5T440-280q0-17 11.5-28.5T480-320q17 0 28.5 11.5T520-280q0 17-11.5 28.5T480-240Zm-160 0q-17 0-28.5-11.5T280-280q0-17 11.5-28.5T320-320q17 0 28.5 11.5T360-280q0 17-11.5 28.5T320-240Zm320 0q-17 0-28.5-11.5T600-280q0-17 11.5-28.5T640-320q17 0 28.5 11.5T680-280q0 17-11.5 28.5T640-240Z" />
                        </svg>
                        <span>Monitoring Presensi</span>
                    </a>
                </li>

                {{-- Sistem (submenu) --}}
                <li class="{{ request()->routeIs('user.*', 'penilaian.*', 'siswa.shownilai', 'siswa.nilai', 'siswa.nilai.show') ? 'active' : '' }}">
                    <button onclick="toggleSubMenu(this)" class="dropdown-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M480-120q-75 0-140.5-28.5t-114-77q-48.5-48.5-77-114T120-480q0-75 28.5-140.5t77-114q48.5-48.5 114-77T480-840q75 0 140.5 28.5t114 77q48.5 48.5 77 114T840-480q0 75-28.5 140.5t-77 114q-48.5 48.5-114 77T480-120Zm-66-210L306-470q-11-11-28-11t-28 11q-11 11-11 28t11 28l134 134q12 12 28 12t28-12l262-262q11-11 11-28t-11-28q-11-11-28-11t-28 11L414-330Z" />
                        </svg>
                        <span>Sistem</span>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor" class="dropdown-icon">
                            <path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z" />
                        </svg>
                    </button>
                    <ul class="sub-menu">
                        <li class="{{ request()->routeIs('user.*') ? 'active' : '' }}">
                            <a href="{{ route('user.index') }}">
                                <i class="fas fa-users-cog"></i>
                                <span>Pengguna</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('penilaian.index', 'penilaian.create', 'penilaian.store', 'penilaian.show', 'penilaian.edit', 'penilaian.update', 'penilaian.destroy', 'siswa.shownilai', 'siswa.nilai', 'siswa.nilai.show') ? 'active' : '' }}">
                            <a href="{{ route('penilaian.index') }}">
                                <i class="fas fa-chart-line"></i>
                                <span>Penilaian</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @auth
                <!-- Placeholder: role-specific menus already above -->
            @endauth
        </ul>

        <!-- Footer Section - Fixed Bottom -->
        <ul class="sidebar-footer-section">
            <!-- Bottom Navigation: Beranda and Logout -->
            @auth
                <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="currentColor">
                            <path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-360l320-240 320 240v360H520v-240h-80v240H160Zm320-350Z" />
                        </svg>
                        <span>Beranda</span>
                    </a>
                </li>
                
                <li class="sidebar-footer">
                    <a href="{{ route('keluar') }}">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            @endauth
        </ul>

            <!-- User Authentication Links - only for guests -->
            @guest
                <li>
                    <a href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                </li>
            @endguest
        </ul>
        <div id="sidebar-resize-handle"></div>
    </nav>

    <!-- Main Content -->
    <main class="content">
        @yield('content')
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
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
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');
            
            sidebar.classList.toggle('collapsed');
            
            // Update icons - make sure close icon is visible when collapsed
            if (sidebar.classList.contains('collapsed')) {
                menuIcon.style.display = 'none';
                closeIcon.style.display = 'block';
                sidebar.style.width = ''; // clear width so it collapses to CSS width
            } else {
                menuIcon.style.display = 'block';
                closeIcon.style.display = 'none';
                // Restore saved width if any, or default to 240px
                const savedWidth = localStorage.getItem('sidebar-width');
                sidebar.style.width = savedWidth ? savedWidth + 'px' : '240px';
            }
        }

        // Sidebar manual resize functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const handle = document.getElementById('sidebar-resize-handle');
            
            if (!sidebar || !handle) return;
            
            // Restore saved width from localStorage if exists and sidebar is not collapsed
            if (!sidebar.classList.contains('collapsed')) {
                const savedWidth = localStorage.getItem('sidebar-width');
                if (savedWidth) {
                    sidebar.style.width = savedWidth + 'px';
                }
            }
            
            let isResizing = false;
            
            handle.addEventListener('mousedown', function(e) {
                isResizing = true;
                handle.classList.add('active');
                document.body.classList.add('sidebar-resizing');
                
                // Disable smooth transition while dragging
                sidebar.style.transition = 'none';
                e.preventDefault();
            });
            
            document.addEventListener('mousemove', function(e) {
                if (!isResizing) return;
                
                let newWidth = e.clientX;
                
                // Constrain the width
                if (newWidth < 180) {
                    newWidth = 180;
                } else if (newWidth > 500) {
                    newWidth = 500;
                }
                
                sidebar.style.width = newWidth + 'px';
                
                // If width is expanded, ensure it's not marked collapsed
                if (newWidth > 180 && sidebar.classList.contains('collapsed')) {
                    sidebar.classList.remove('collapsed');
                    const menuIcon = document.getElementById('menu-icon');
                    const closeIcon = document.getElementById('close-icon');
                    if (menuIcon) menuIcon.style.display = 'block';
                    if (closeIcon) closeIcon.style.display = 'none';
                }
            });
            
            document.addEventListener('mouseup', function() {
                if (!isResizing) return;
                
                isResizing = false;
                handle.classList.remove('active');
                document.body.classList.remove('sidebar-resizing');
                
                // Restore transition property
                sidebar.style.transition = '';
                
                // Save custom width to localStorage
                localStorage.setItem('sidebar-width', parseInt(sidebar.style.width));
            });
        });

        function toggleSubMenu(button) {
            const subMenu = button.nextElementSibling;
            const isOpen = subMenu.style.display === 'block';
            const sidebar = document.getElementById('sidebar');
            
            // If sidebar is collapsed, expand it first
            if (sidebar.classList.contains('collapsed')) {
                toggleSidebar();
                setTimeout(() => {
                    openThisSubmenu(button);
                }, 300); // Wait for transition to complete
            } else {
                openThisSubmenu(button);
            }
            
            function openThisSubmenu(btn) {
                // ACCORDION BEHAVIOR: Close all other submenus in the nav section
                const navSection = btn.closest('.sidebar-nav-section');
                if (navSection) {
                    const allSubMenus = navSection.querySelectorAll('.sub-menu');
                    allSubMenus.forEach(menu => {
                        if (menu !== subMenu) {
                            menu.style.display = 'none';
                        }
                    });
                    
                    // Reset all dropdown buttons in nav section
                    const allDropdownBtns = navSection.querySelectorAll('.dropdown-btn');
                    allDropdownBtns.forEach(dropBtn => {
                        if (dropBtn !== btn) {
                            dropBtn.classList.remove('rotate');
                            dropBtn.closest('li').classList.remove('active');
                        }
                    });
                }
                
                // Toggle this submenu
                subMenu.style.display = isOpen ? 'none' : 'block';
                
                // Update button class for rotation
                if (!isOpen) {
                    btn.classList.add('rotate');
                    btn.closest('li').classList.add('active'); // Add active to parent li
                } else {
                    btn.classList.remove('rotate');
                    // Only remove active if no submenu items are active
                    if (!subMenu.querySelector('li.active')) {
                        btn.closest('li').classList.remove('active');
                    }
                }
            }
        }

        // Define the missing setActiveMenuItem function
        function setActiveMenuItem(clickedLink) {
            // Remove active class from all menu items
            const allMenuItems = document.querySelectorAll('#sidebar li');
            allMenuItems.forEach(item => item.classList.remove('active'));
            
            // Add active class to the clicked item's parent li
            const parentLi = clickedLink.closest('li');
            if (parentLi) {
                parentLi.classList.add('active');
                
                // If it's in a submenu, also activate the parent dropdown
                const parentSubMenu = parentLi.closest('.sub-menu');
                if (parentSubMenu) {
                    const parentDropdownLi = parentSubMenu.closest('li');
                    if (parentDropdownLi) {
                        parentDropdownLi.classList.add('active');
                    }
                }
            }
        }

        // Setup event handlers on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add click handlers to all regular menu links
            const menuLinks = document.querySelectorAll('#sidebar a:not(.dropdown-btn)');
            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Only process if it's not a dropdown button
                    if (!this.classList.contains('dropdown-btn')) {
                        setActiveMenuItem(this);
                    }
                });
            });
            
            // Automatically open submenus that contain active menu items (Accordion: only one at a time)
            const activeSubMenuItems = document.querySelectorAll('.sidebar-nav-section .sub-menu li.active');
            let hasOpenedGroup = false;
            
            activeSubMenuItems.forEach(item => {
                // Only open the first active group to maintain accordion behavior
                if (!hasOpenedGroup) {
                    const subMenu = item.closest('.sub-menu');
                    if (subMenu) {
                        subMenu.style.display = 'block';
                        const button = subMenu.previousElementSibling;
                        if (button && button.classList.contains('dropdown-btn')) {
                            button.classList.add('rotate');
                            button.closest('li').classList.add('active');
                        }
                        hasOpenedGroup = true;
                    }
                }
            });

            // Jangan override highlight sidebar server-side pada form mentor dengan ?from=
            const urlParams = new URLSearchParams(window.location.search);
            const fromParam = urlParams.get('from');
            const hasDashboardContextActive = document.querySelector(
                '#sidebar > ul > li.active > a[href*="/dashboard/"]'
            );

            if (fromParam || hasDashboardContextActive) {
                return;
            }
            
            // Check for active menu items based on current URL (exact match only)
            const currentPath = window.location.pathname;
            const allLinks = document.querySelectorAll('#sidebar a[href]');
            allLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && href.split('?')[0] === currentPath) {
                    setActiveMenuItem(link);
                }
            });
        });
    </script>
    
    @yield('scripts')

    {{-- Badge Notification Polling (khusus role mentor) --}}
    @if(Auth::check() && Auth::user()->role === 'mentor')
    <script>
    (function() {
        const badgeUrl = "{{ route('mentor.tasks.api.badge-counts') }}";

        function updateBadge(elementId, count) {
            const el = document.getElementById(elementId);
            if (!el) return;
            if (count > 0) {
                el.textContent = count > 99 ? '99+' : count;
                el.style.display = 'inline-flex';
            } else {
                el.style.display = 'none';
            }
        }

        function fetchBadgeCounts() {
            fetch(badgeUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(function(r) { return r.ok ? r.json() : null; })
            .then(function(data) {
                if (!data) return;
                updateBadge('badge-pengajuan', data.pengajuan_count || 0);
                updateBadge('badge-tugas', data.tugas_count || 0);
            })
            .catch(function() { /* silent fail */ });
        }

        // Jalankan segera saat halaman dimuat
        fetchBadgeCounts();

        // Poll setiap 60 detik
        setInterval(fetchBadgeCounts, 60000);
    })();
    </script>
    @endif
</body>

</html>