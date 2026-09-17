@extends('layouts.app')

@section('title', 'Profil Siswa')

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
    --mentor: #8b5cf6;
    --background: #f8fafc;
    --card-bg: #ffffff;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --border-color: #e2e8f0;
}

/* Base styling */
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

.profile-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 25px;
}

/* ==================================== */
/* Header Profil             */
/* ==================================== */
.profile-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: 16px;
    padding: 30px;
    color: white;
    margin-bottom: 30px;
    box-shadow: 0 10px 15px -3px rgba(80, 57, 235, 0.15);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center; /* Menambahkan ini untuk teks di tengah */
}

/* Efek latar belakang (lingkaran) */
.profile-header::before,
.profile-header::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    z-index: 1;
}

.profile-header::before {
    top: -50px;
    right: -50px;
    width: 200px;
    height: 200px;
}

.profile-header::after {
    bottom: -100px;
    left: 15%;
    width: 250px;
    height: 250px;
    background: rgba(255, 255, 255, 0.05); /* Mengatur opacity agar berbeda */
}

/* Konten utama dalam header */
.header-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column; /* Mengatur tata letak vertikal untuk responsivitas */
    align-items: center;
    justify-content: center;
    gap: 20px;
    width: 100%;
}

.profile-info-wrapper {
    display: flex;
    flex-direction: column; /* Mengatur tata letak vertikal */
    align-items: center;
    gap: 20px;
}

.profile-avatar-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

/* ==================================== */
/* Avatar Profil            */
/* ==================================== */
.profile-avatar {
    width: 250px;
    height: 250px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: bold;
    position: relative;
    z-index: 2;
    border: 3px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.2s ease-in-out;
    position: relative; /* Ini sangat penting! */
    overflow: hidden;
    cursor: pointer;
}

.profile-avatar:hover {
    transform: scale(1.05);
}

/* Gambar di dalam avatar */
.profile-avatar img.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

/* Placeholder inisial nama */
.profile-avatar .avatar-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Overlay saat hover */
.avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.profile-avatar:hover .avatar-overlay {
    opacity: 1;
}

.avatar-overlay i {
    color: white;
    font-size: 1.8rem;
}

/* ==================================== */
/* Informasi Profil           */
/* ==================================== */
.profile-info {
    text-align: center; /* Menyelaraskan teks di tengah */
}

.profile-info h1 {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 5px;
    position: relative;
    z-index: 2;
}

.profile-info p {
    font-size: 1.05rem;
    opacity: 0.9;
    margin-bottom: 0;
    position: relative;
    z-index: 2;
}

/* ==================================== */
/* Media Queries             */
/* ==================================== */
@media (min-width: 768px) {
    .header-content {
        flex-direction: row; /* Mengubah tata letak menjadi horizontal di layar besar */
        justify-content: space-around; /* Menambahkan ruang antar elemen */
    }

    .profile-info-wrapper {
        flex-direction: row;
        align-items: center;
        gap: 30px;
    }
    
    .profile-info {
        text-align: left; /* Mengatur perataan teks ke kiri */
    }
}

    /* Action Buttons - Modern Style */
    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 20px; /* Added spacing from profile info */
    }

    .btn-modern {
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        color: white;
    }

    .btn-modern:hover {
        transform: translateY(-3px); /* Increased lift on hover */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Stronger shadow on hover */
        text-decoration: none;
        color: white;
    }

    .btn-primary-modern {
        background: rgba(255, 255, 255, 0.25); /* Slightly more opaque */
    }

    .btn-success-modern {
        background: rgba(255, 255, 255, 0.3); /* Slightly more opaque */
    }

    /* Cards Grid */
    .info-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 25px;
        margin-bottom: 25px;
    }

    .info-card {
        background: var(--card-bg);
        border-radius: 16px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px; /* Slightly thicker border */
        background: var(--card-gradient);
        z-index: 1;
    }

    .info-card:hover {
        transform: translateY(-8px) scale(1.01); /* Enhanced hover effect */
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1); /* Stronger shadow on hover */
    }

    .info-card.personal::before {
        --card-gradient: var(--primary);
    }

    .info-card.academic::before {
        --card-gradient: var(--secondary);
    }

    .info-card.internship::before {
        --card-gradient: var(--info);
    }

    .info-card.mentor::before {
        --card-gradient: var(--mentor);
    }

    .info-card.certificate::before {
        --card-gradient: var(--success);
    }

    .card-header {
        display: flex;
        align-items: center;
        padding: 20px 25px;
        border-bottom: 1px solid var(--border-color);
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .card-icon {
        width: 50px; /* Slightly larger icon */
        height: 50px; /* Slightly larger icon */
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 18px; /* More spacing */
        font-size: 22px; /* Larger icon font size */
        color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Stronger icon shadow */
    }

    .card-icon.personal {
        background: var(--primary);
    }

    .card-icon.academic {
        background: var(--secondary);
    }

    .card-icon.internship {
        background: var(--info);
    }

    .card-icon.mentor {
        background: var(--mentor);
    }

    .card-icon.certificate {
        background: var(--success);
    }

    .card-title {
        font-size: 20px; /* Larger title */
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .card-content {
        padding: 25px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }

    .info-item:last-child {
        margin-bottom: 0;
        border-bottom: none;
    }

    .info-item:hover {
        background: #f8fafc;
        margin: 0 -15px 15px -15px; /* Increase hover padding */
        padding: 15px 15px; /* Increase hover padding */
        border-radius: 8px;
    }

    .info-label {
        font-weight: 500;
        color: var(--text-secondary);
        flex: 0 0 40%; /* Adjusted flex basis for label */
        font-size: 15px; /* Slightly larger label font */
        text-transform: uppercase;
        letter-spacing: 0.7px; /* Increased letter spacing */
    }

    .info-value {
        color: var(--text-primary);
        flex: 1;
        text-align: right;
        font-size: 15px; /* Slightly larger value font */
        font-weight: 600;
        word-break: break-word;
    }

    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 7px 14px; /* Slightly larger padding */
        border-radius: 25px; /* More rounded */
        font-size: 12px; /* Slightly larger font */
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Added subtle shadow to badges */
    }

    .status-completed {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .status-approved {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }

    .status-taken {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }

    .status-pending {
        background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
        color: #991b1b;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-secondary);
        display: flex; /* Flex for centering content */
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 150px; /* Ensure a minimum height for empty state */
    }

    .empty-state i {
        font-size: 56px; /* Larger icon */
        margin-bottom: 20px; /* More spacing */
        opacity: 0.6;
        color: var(--text-secondary); /* Ensure consistent color */
    }

    .empty-state h4 {
        font-size: 18px; /* Larger heading */
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--text-primary);
    }

    .empty-state p {
        font-size: 15px; /* Larger paragraph */
        margin: 0;
    }

    /* Upload Loading Overlay */
    .upload-loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .loading-content {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Modal Styling (for Inbox) */
    .modal-content {
        border-radius: 18px;
        overflow: hidden;
        border: none;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        border-bottom: none;
        padding: 20px 26px;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
    }

    .modal-header .btn-close {
        filter: invert(1);
    }

    .modal-title {
        font-weight: 600;
        font-size: 1.15rem;
        display: flex;
        align-items: center;
    }

    .modal-body {
        padding: 24px;
        color: var(--text-primary);
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 16px 24px;
        background: #fafafa;
    }

    /* Inbox Tabs */
    #inboxTabs {
        border-bottom: 2px solid #e2e8f0;
        gap: 8px;
    }

    #inboxTabs .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        font-size: 0.92rem;
        padding: 10px 18px;
        border-radius: 10px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: transparent;
    }

    #inboxTabs .nav-link:hover {
        color: #4f46e5;
        background: #f1f5f9;
    }

    #inboxTabs .nav-link.active {
        color: #4f46e5;
        background: #eef2ff;
        border-bottom: 2px solid #4f46e5;
        margin-bottom: -2px;
    }

    /* Message & PDF List Item Modern Styling */
    .inbox-item-card {
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 14px 18px !important;
        margin-bottom: 12px !important;
        background: #ffffff;
        transition: all 0.2s ease !important;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .inbox-item-card:hover {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }

    .inbox-item-card.is-read {
        background: #f8fafc;
        opacity: 0.88;
    }

    .inbox-item-title {
        font-size: 0.98rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .inbox-item-desc {
        font-size: 0.87rem;
        color: #475569;
        margin-bottom: 6px;
        line-height: 1.4;
    }

    .inbox-item-meta {
        font-size: 0.78rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .inbox-item-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        gap: 8px;
        flex-shrink: 0;
    }

    /* Modern Soft Badges */
    .badge-soft-unread {
        background-color: #fef3c7;
        color: #b45309;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        letter-spacing: 0.2px;
        border: 1px solid #fde68a;
    }

    .badge-soft-read {
        background-color: #f1f5f9;
        color: #64748b;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid #e2e8f0;
    }

    /* Modern Action Buttons */
    .btn-mark-read {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .btn-mark-read:hover {
        background: #10b981;
        color: #ffffff;
        border-color: #10b981;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.25);
        transform: translateY(-1px);
    }

    .btn-view-pdf {
        background: #eef2ff;
        color: #4f46e5;
        border: 1px solid #c7d2fe;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-view-pdf:hover {
        background: #4f46e5;
        color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
        transform: translateY(-1px);
    }

    .btn-delete-pdf {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-delete-pdf:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.25);
        transform: translateY(-1px);
    }

    .btn-modal-close {
        background: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.88rem;
        padding: 7px 20px;
        transition: all 0.2s ease;
    }

    .btn-modal-close:hover {
        background: #f1f5f9;
        color: #1e293b;
        border-color: #94a3b8;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .info-cards {
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); /* Adjusted minmax */
        }
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 15px;
        }
        
        .header-content {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-info-wrapper {
            flex-direction: column;
            text-align: center;
        }
        
        .action-buttons {
            width: 100%;
            justify-content: center;
            flex-direction: column; /* Stack buttons on small screens */
        }
        
        .btn-modern {
            flex: 1;
            justify-content: center;
        }
        
        .info-cards {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        .info-label {
            margin-bottom: 0;
        }
        
        .info-value {
            text-align: left;
        }

        .profile-info h1 {
            font-size: 1.8rem;
        }

        .profile-info p {
            font-size: 0.95rem;
        }
    }

    @media (max-width: 480px) {
        .profile-header {
            padding: 20px 15px;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }

        .card-title {
            font-size: 18px;
        }

        .info-cards {
            gap: 15px;
        }

        .card-content {
            padding: 20px;
        }

        .info-label, .info-value {
            font-size: 14px;
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

    /* Remove specific animation delays to let general JS handle it */
    /* .info-card:nth-child(1) { animation-delay: 0.1s; }
    .info-card:nth-child(2) { animation-delay: 0.2s; }
    .info-card:nth-child(3) { animation-delay: 0.3s; }
    .info-card:nth-child(4) { animation-delay: 0.4s; }
    .info-card:nth-child(5) { animation-delay: 0.5s; } */
    
</style>
@endsection
@section('content')
<div class="profile-container">
    <div class="profile-header fade-in">
        <div class="header-content">
            <div class="profile-info-wrapper">
                <div class="profile-avatar-container">
                    <div class="profile-avatar" id="profileAvatarTrigger"> {{-- Added id for JS targeting --}}
                        @if($siswa && $siswa->foto_profil_url)
                            <img src="{{ asset('storage/' . $siswa->foto_profil_url) }}" alt="Foto Profil" class="avatar-img" id="avatarImage">
                        @else
                            <div class="avatar-placeholder" id="avatarPlaceholder">
                                {{ $siswa ? strtoupper(substr($siswa->nama_lengkap ?? 'S', 0, 1)) : 'S' }}
                            </div>
                        @endif
                        <div class="avatar-overlay">
                        </div>
                    </div>
                    <button type="button" id="changeProfilePicBtn" class="btn-modern btn-primary-modern">
                        <i class="fas fa-camera me-1"></i> Ganti Foto Profil
                    </button>
                </div>
                
                {{-- This profile-info was misplaced before --}}
                <div class="profile-info">
                    <h1>{{ $siswa->nama_lengkap ?? 'Nama Siswa' }}</h1>
                    <p>{{ $siswa->jurusan ?? 'Jurusan' }} - {{ $siswa->asal_sekolah ?? 'Asal Sekolah' }}</p>
                </div>
            </div>
            
            {{-- This action-buttons was misplaced before --}}
            <div class="action-buttons">
                {{-- Integrated conditional logic for Inbox Dokumen PDF button --}}
                <button type="button" class="btn-modern btn-primary-modern" data-bs-toggle="modal" data-bs-target="#inboxPdfModal" id="inboxButton" data-nim-nis="{{ $siswa->nim_nis ?? '' }}">
                <i class="fas fa-inbox"></i> Inbox
                </button>



                @if($siswa->no_sertifikat && $siswa->approve_kadis == 1)
                    <a href="{{ route('siswa.nilai', ['id_sertifikat' => $siswa->id_sertifikat]) }}" class="btn-modern btn-success-modern">
                        <i class="fas fa-chart-line"></i> Lihat Nilai
                    </a>
                @endif
            </div>
        </div> {{-- Correct closing of .header-content --}}
    </div> {{-- Correct closing of .profile-header --}}

    <form id="profilePicUploadForm" action="{{ route('siswa.uploadPhoto') }}" method="POST" enctype="multipart/form-data" style="display: none;">
        @csrf
        <input type="file" name="profile_picture" id="profilePicInput" accept="image/jpeg,image/png,image/jpg,image/gif">
    </form>

    <div id="uploadLoadingOverlay" class="upload-loading-overlay" style="display: none;">
        <div class="loading-content">
            <div class="spinner"></div>
            <p>Mengunggah foto profil...</p>
        </div>
    </div>
    {{-- Profile Image Modal - CORRECT PLACEMENT --}}
    <div id="profileImageModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            @if($siswa && $siswa->foto_profil_url)
                <img src="{{ asset('storage/' . $siswa->foto_profil_url) }}" alt="Foto Profil Besar" class="modal-image">
            @else
                <div class="avatar-placeholder modal-placeholder">
                    {{ $siswa ? strtoupper(substr($siswa->nama_lengkap ?? 'S', 0, 1)) : 'S' }}
                </div>
            @endif
        </div>
    </div>

   {{-- Modal: Inbox Dokumen & Pesan (Bertab) --}}
<div class="modal" id="inboxPdfModal" tabindex="-1" aria-labelledby="inboxPdfModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-t-lg">
                <h5 class="modal-title" id="inboxPdfModalLabel">
                    <i class="fas fa-inbox me-2"></i> Inbox Dokumen & Pesan <span id="inboxStudentName"></span>
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-3" id="inboxTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pdf-tab" data-bs-toggle="tab" data-bs-target="#pdf-content" type="button" role="tab" aria-controls="pdf-content" aria-selected="true">
                            <i class="fas fa-file-pdf me-1"></i> Dokumen PDF
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages-content" type="button" role="tab" aria-controls="messages-content" aria-selected="false">
                            <i class="fas fa-comments me-1"></i> Pesan Masuk
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="inboxTabContent">
                    {{-- Tab Pane untuk Dokumen PDF --}}
                    <div class="tab-pane fade show active" id="pdf-content" role="tabpanel" aria-labelledby="pdf-tab">
                        <div id="pdfListContainer">
                            <div class="empty-inbox" id="loadingInbox">
                                <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                                <h4 class="text-gray-700">Memuat dokumen...</h4>
                                <p class="text-gray-500">Harap tunggu sebentar.</p>
                            </div>
                            <div class="empty-inbox" id="noPdfFound" style="display: none;">
                                <i class="fas fa-folder-open fa-3x text-warning mb-3"></i>
                                <h4 class="text-gray-700">Tidak ada dokumen PDF ditemukan.</h4>
                                <p class="text-gray-500">Inbox Anda kosong.</p>
                            </div>
                            <ul class="list-group list-group-flush" id="pdfList">
                                {{-- PDF documents will be loaded here via JavaScript --}}
                            </ul>
                        </div>
                    </div>
                    {{-- Tab Pane untuk Pesan Masuk --}}
                    <div class="tab-pane fade" id="messages-content" role="tabpanel" aria-labelledby="messages-tab">
                        <div id="messageListContainer">
                            <div class="empty-inbox" id="loadingMessages">
                                <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                                <h4 class="text-gray-700">Memuat pesan...</h4>
                                <p class="text-gray-500">Harap tunggu sebentar.</p>
                            </div>
                            <div class="empty-inbox" id="noMessagesFound" style="display: none;">
                                <i class="fas fa-comment-alt fa-3x text-warning mb-3"></i>
                                <h4 class="text-gray-700">Tidak ada pesan ditemukan.</h4>
                                <p class="text-gray-500">Inbox pesan Anda kosong.</p>
                            </div>
                            <ul class="list-group list-group-flush" id="messageList">
                                {{-- Messages will be loaded here via JavaScript --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
{{-- NEW: Modal for Message Detail --}}
<div class="modal" id="messageDetailModal" tabindex="-1" aria-labelledby="messageDetailModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-to-r from-teal-500 to-green-600 text-white rounded-t-lg">
                <h5 class="modal-title" id="messageDetailModalLabel">
                    <i class="fas fa-envelope-open-text me-2"></i> Detail Pesan
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="text-primary mb-3" id="detailMessageSubject"></h4>
                <p class="text-muted mb-2">Dari: <strong id="detailMessageSender"></strong></p>
                <p class="text-muted mb-4">Pada: <span id="detailMessageDate"></span></p>
                <hr>
                <div class="message-content-box p-3 border rounded bg-light" style="white-space: pre-wrap;" id="detailMessageContent">
                    {{-- Message content will be loaded here --}}
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <small class="text-muted" id="detailMessageReadStatus"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Konfirmasi Penghapusan (untuk PDF) --}}
<div class="modal" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white rounded-t-lg">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">
                    <i class="fas fa-trash-alt me-2 animate__animated animate__shakeX"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Apakah Anda yakin ingin menghapus dokumen "<strong id="pdfToDeleteTitle"></strong>"?</p>
                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-modal-close" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger px-4" style="border-radius: 8px; font-weight: 600;" id="confirmDeleteBtn">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

    <div class="info-cards">
        <div class="info-card personal fade-in">
            <div class="card-header">
                <div class="card-icon personal">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="card-title">Informasi Pribadi</h3>
            </div>
            <div class="card-content">
                <div class="info-item">
                    <span class="info-label">Nama Lengkap</span>
                    <span class="info-value">{{ $siswa->nama_lengkap ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">NIM/NIS</span>
                    <span class="info-value">{{ $siswa->nim_nis ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tempat Lahir</span>
                    <span class="info-value">{{ $siswa->tempat_lahir ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Lahir</span>
                    <span class="info-value">{{ $siswa->tgl_lahir ? \Carbon\Carbon::parse($siswa->tgl_lahir)->locale('id')->isoFormat('D MMMM Y') : '-' }}</span>
                </div>
            </div>
        </div>

        <div class="info-card academic fade-in">
            <div class="card-header">
                <div class="card-icon academic">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="card-title">Informasi Akademik</h3>
            </div>
            <div class="card-content">
                <div class="info-item">
                    <span class="info-label">Asal Sekolah</span>
                    <span class="info-value">{{ $siswa->asal_sekolah ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jurusan</span>
                    <span class="info-value">{{ $siswa->jurusan ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Bidang</span>
                    <span class="info-value">{{ $siswa->bidang ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="info-card internship fade-in">
            <div class="card-header">
                <div class="card-icon internship">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3 class="card-title">Informasi Magang</h3>
            </div>
            <div class="card-content">
                <div class="info-item">
                    <span class="info-label">Tanggal Mulai</span>
                    <span class="info-value">{{ $siswa->tgl_mulai ? \Carbon\Carbon::parse($siswa->tgl_mulai)->locale('id')->isoFormat('D MMMM Y') : '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Selesai</span>
                    <span class="info-value">{{ $siswa->tgl_selesai ? \Carbon\Carbon::parse($siswa->tgl_selesai)->locale('id')->isoFormat('D MMMM Y') : '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nama Penanggung Jawab</span>
                    <span class="info-value">{{ $siswa->nm_kadis ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">NIP Penanggung jawab</span>
                    <span class="info-value">{{ $siswa->nip_kadis ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jabatan Penanggung jawab</span>
                    <span class="info-value">{{ $siswa->jabatan_pegawai ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status Persetujuan</span>
                    <span class="info-value">
                        @if($siswa->approve_kadis == 1)
                            <span class="status-badge status-approved">Disetujui</span>
                        @else
                            <span class="status-badge status-pending">Menunggu</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="info-card mentor fade-in">
            <div class="card-header">
                <div class="card-icon mentor">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="card-title">Informasi Pembimbing</h3>
            </div>
            <div class="card-content">
                @if($siswa->nm_pembimbing)
                    <div class="info-item">
                        <span class="info-label">Nama Pembimbing</span>
                        <span class="info-value">{{ $siswa->nm_pembimbing }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">NIP Pembimbing</span>
                        <span class="info-value">{{ $siswa->nik_pembimbing ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jabatan</span>
                        <span class="info-value">{{ $siswa->jabatan_pembimbing ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status Persetujuan</span>
                        <span class="info-value">
                            @if($siswa->aprove_pembimbing == 1)
                                <span class="status-badge status-approved">Disetujui</span>
                            @else
                                <span class="status-badge status-pending">Menunggu</span>
                            @endif
                        </span>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-user-tie"></i>
                        <h4>Belum Ada Pembimbing</h4>
                        <p>Informasi pembimbing belum tersedia</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="info-card certificate fade-in">
            <div class="card-header">
                <div class="card-icon certificate">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3 class="card-title">Informasi Sertifikat</h3>
            </div>
            <div class="card-content">
                <div class="info-item">
                    <span class="info-label">Tanggal Sertifikat</span>
                    <span class="info-value">{{ $siswa->tgl_sertifikat ? \Carbon\Carbon::parse($siswa->tgl_sertifikat)->format('d F Y') : '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nomor Sertifikat</span>
                    <span class="info-value">{{ $siswa->no_sertifikat ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status Sertifikat</span>
                    <span class="info-value">
                        @if($siswa->sts_diambil == 1)
                            <span class="status-badge status-taken">Sudah Diambil</span>
                        @else
                            <span class="status-badge status-completed">Belum Diambil</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- Bootstrap Bundle (termasuk Popper) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
{{-- Moment.js untuk formatting tanggal --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
{{-- Moment.js locale untuk Bahasa Indonesia --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the modal
        var modal = document.getElementById("profileImageModal");

        // Get the image that opens the modal
        var imgTrigger = document.getElementById("profileAvatarTrigger");

        // Get the <span> element that closes the modal
        var span = document.querySelector("#profileImageModal .close-button"); // More specific selector

        // When the user clicks on the image, open the modal
        if (imgTrigger) { // Check if the element exists
            imgTrigger.onclick = function() {
                modal.style.display = "flex"; // Use flex to center the content
            }
        }

        // When the user clicks on <span> (x), close the modal
        if (span) { // Check if the element exists
            span.onclick = function() {
                modal.style.display = "none";
            }
        }

        // When the user clicks anywhere outside of the modal content, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const changeProfilePicBtn = document.getElementById('changeProfilePicBtn');
        const profilePicInput = document.getElementById('profilePicInput');
        const profilePicUploadForm = document.getElementById('profilePicUploadForm');
        const uploadLoadingOverlay = document.getElementById('uploadLoadingOverlay');
        const profileAvatar = document.querySelector('.profile-avatar');

        // Event listener for the "Ganti Foto Profil" button
        changeProfilePicBtn.addEventListener('click', function() {
            profilePicInput.click();
        });

        // Event listener for the avatar (alternative way to click)
        // Ensure this doesn't conflict with the modal trigger if both are active on the same element
        // For now, I'm keeping it as a separate click event for file input.
        // If profileAvatarTrigger is supposed to open the modal, remove this line:
        // profileAvatar.addEventListener('click', function() {
        //     profilePicInput.click();
        // });


        // Event listener for file input change
        profilePicInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            
            if (file) {
                // File validation
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                const maxSize = 2048 * 1024; // 2MB in bytes

                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Tidak Valid',
                        text: 'Harap pilih file gambar dengan format JPEG, PNG, JPG, atau GIF.',
                        confirmButtonColor: '#007bff'
                    });
                    return;
                }

                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar',
                        text: 'Ukuran file maksimal adalah 2MB.',
                        confirmButtonColor: '#007bff'
                    });
                    return;
                }

                // Upload confirmation
                Swal.fire({
                    title: 'Konfirmasi Upload',
                    text: 'Apakah Anda yakin ingin mengganti foto profil?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Ganti!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Image preview before upload
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const avatarImg = document.getElementById('avatarImage');
                            const avatarPlaceholder = document.getElementById('avatarPlaceholder');
                            
                            if (avatarImg) {
                                avatarImg.src = e.target.result;
                            } else if (avatarPlaceholder) {
                                // Replace placeholder with image
                                avatarPlaceholder.outerHTML = `<img src="${e.target.result}" alt="Foto Profil" class="avatar-img" id="avatarImage">`;
                            }
                        };
                        reader.readAsDataURL(file);

                        // Show loading overlay
                        uploadLoadingOverlay.style.display = 'flex';

                        // Submit form
                        profilePicUploadForm.submit();
                    } else {
                        // Reset file input
                        profilePicInput.value = '';
                    }
                });
            }
        });

        // Handle form submission errors
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal Upload',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#007bff'
            });
        @endif

        // Handle success message
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#007bff',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        // Handle error message
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#007bff'
            });
        @endif
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        // Add fade-in animation to all cards
        const cards = document.querySelectorAll('.fade-in');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });

        // Enhanced card hover effects
        const infoCards = document.querySelectorAll('.info-card');
        infoCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Button ripple effects
        const buttons = document.querySelectorAll('.btn-modern');
        buttons.forEach(btn => {
            btn.addEventListener('click', function(e) {
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

        // Smooth scroll to sections
        // IMPORTANT: If 'profileAvatarTrigger' is used for modal, this part might conflict.
        // Decide if clicking the avatar should open modal OR scroll.
        // I've commented out the avatar scroll event listener to prioritize modal.
        // If you want both, you'll need more complex logic.
        /*
        const profileAvatar = document.querySelector('.profile-avatar');
        if (profileAvatar) {
            profileAvatar.addEventListener('click', function() {
                document.querySelector('.info-cards').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        }
        */
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

    // PDF loading function
    // Fungsi untuk memuat PDF individual (sudah ada, dimodifikasi)
// Fungsi untuk memuat PDF individual (tidak berubah dari sebelumnya)
// Memastikan script dijalankan setelah DOM sepenuhnya dimuat
// Memastikan script dijalankan setelah DOM sepenuhnya dimuat
$(document).ready(function() {
    // Set locale Moment.js ke Bahasa Indonesia
    moment.locale('id');

    // --- Variabel Blade untuk Siswa yang Sedang Login ---
    const loggedInStudentIdSertifikat = '{{ $siswa->id_sertifikat ?? null }}';
    const loggedInStudentName = '{{ $siswa->nama_lengkap ?? "Anda" }}';
    
    console.log('Logged-in Student ID Sertifikat:', loggedInStudentIdSertifikat);
    console.log('Logged-in Student Name:', loggedInStudentName);

    // --- Elemen DOM ---
    const loadingInbox = $('#loadingInbox');
    const noPdfFound = $('#noPdfFound');
    const pdfList = $('#pdfList');
    
    const loadingMessages = $('#loadingMessages');
    const noMessagesFound = $('#noMessagesFound');
    const messageList = $('#messageList');

    const deleteConfirmationModal = $('#deleteConfirmationModal');
    const pdfToDeleteTitle = $('#pdfToDeleteTitle');
    const confirmDeleteBtn = $('#confirmDeleteBtn');

    const messageDetailModal = $('#messageDetailModal');
    const detailMessageSubject = $('#detailMessageSubject');
    const detailMessageSender = $('#detailMessageSender');
    const detailMessageDate = $('#detailMessageDate');
    const detailMessageContent = $('#detailMessageContent');
    const detailMessageReadStatus = $('#detailMessageReadStatus');

    const inboxPdfModal = $('#inboxPdfModal');

    // --- Variabel untuk menyimpan ID PDF yang akan dihapus ---
    let pdfToDeleteId = null;

    // --- Event listener pada modal konfirmasi hapus ---
    $(document).on('click', '.delete-pdf-btn', function() {
        pdfToDeleteId = $(this).data('id');
        const pdfTitle = $(this).data('title');
        pdfToDeleteTitle.text(pdfTitle);
    });

    confirmDeleteBtn.on('click', function() {
        if (pdfToDeleteId) {
            executeDeletePdf(pdfToDeleteId);
        } else {
            alert('Error: ID dokumen tidak ditemukan.');
            deleteConfirmationModal.modal('hide');
        }
    });

    // --- Fungsi untuk Memuat PDF Individual (Inbox Dokumen) ---
    function loadIndividualPdfs(idSertifikat) {
        loadingInbox.show();
        pdfList.empty();
        noPdfFound.hide();

        const apiUrl = `/api/pdfs/individual?id_sertifikat=${encodeURIComponent(idSertifikat)}`;

        $.ajax({
            url: apiUrl,
            method: 'GET',
            dataType: 'json',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            success: function(data) {
                loadingInbox.hide();
                if (data.success && data.data.length > 0) {
                    pdfList.empty();
                    data.data.forEach(pdf => {
                        const listItem = `
                            <li class="inbox-item-card" id="pdf-item-${pdf.id}">
                                <div class="flex-grow-1">
                                    <h6 class="inbox-item-title"><i class="fas fa-file-pdf text-danger me-2"></i>${pdf.title}</h6>
                                    <p class="inbox-item-desc">${pdf.description || 'Tidak ada deskripsi.'}</p>
                                    <div class="inbox-item-meta">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        <span>Diunggah pada: ${moment(pdf.uploaded_at).format('DD MMMM YYYY HH:mm')}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                    <a href="${pdf.pdf_url}" target="_blank" class="btn-view-pdf">
                                        <i class="fas fa-eye"></i> Lihat PDF
                                    </a>
                                    <button type="button" class="btn-delete-pdf delete-pdf-btn"
                                            data-id="${pdf.id}"
                                            data-title="${pdf.title}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteConfirmationModal">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </li>
                        `;
                        pdfList.append(listItem);
                    });
                } else {
                    noPdfFound.show();
                    noPdfFound.find('h4').text('Tidak ada dokumen PDF ditemukan.');
                    noPdfFound.find('p').text('Inbox Anda kosong.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                loadingInbox.hide();
                noPdfFound.show();
                let errorMessage = 'Terjadi kesalahan saat mengambil dokumen.';
                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    errorMessage = jqXHR.responseJSON.message;
                } else if (jqXHR.status === 404) {
                    errorMessage = 'Endpoint API tidak ditemukan. Periksa rute Laravel Anda.';
                } else {
                    errorMessage = `Server merespons dengan status ${jqXHR.status}.`;
                }
                noPdfFound.find('h4').text('Gagal memuat dokumen.');
                noPdfFound.find('p').text(errorMessage);
                console.error('Error fetching PDFs:', textStatus, errorThrown, jqXHR.responseText);
            }
        });
    }

    // --- Fungsi Menghapus PDF ---
    function executeDeletePdf(pdfId) {
        confirmDeleteBtn.prop('disabled', true).text('Menghapus...');

        $.ajax({
            url: `/api/pdfs/${pdfId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    alert('Berhasil: ' + response.message);
                    $(`#pdf-item-${pdfId}`).remove();
                    if (pdfList.children().length === 0) {
                        noPdfFound.show();
                    }
                } else {
                    alert('Gagal menghapus dokumen: ' + (response.message || 'Terjadi kesalahan.'));
                }
            },
            error: function(jqXHR) {
                let errorMessage = 'Gagal menghapus dokumen. Terjadi kesalahan jaringan atau server.';
                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    errorMessage = jqXHR.responseJSON.message;
                }
                alert(errorMessage);
                console.error('Error deleting PDF:', jqXHR.responseText);
            },
            complete: function() {
                deleteConfirmationModal.modal('hide');
                confirmDeleteBtn.prop('disabled', false).text('Ya, Hapus');
                pdfToDeleteId = null;
            }
        });
    }

    // --- Fungsi untuk Mengambil dan Menampilkan Pesan Masuk ---
    function loadMessagesForStudent(idSertifikat) {
        loadingMessages.show();
        messageList.empty();
        noMessagesFound.hide();
        const apiUrl = `/api/messages/student?id_sertifikat=${encodeURIComponent(idSertifikat)}`;
        $.ajax({
            url: apiUrl,
            method: 'GET',
            dataType: 'json',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            success: function(response) {
                loadingMessages.hide();
                if (response.success && response.data.length > 0) {
                    messageList.empty();
                    response.data.forEach(message => {
                        const isRead = message.read_at !== null;
                        const readStatusBadge = isRead 
                            ? '<span class="badge-soft-read"><i class="fas fa-check-double"></i> Dibaca</span>' 
                            : '<span class="badge-soft-unread"><i class="fas fa-circle" style="font-size:7px;"></i> Belum Dibaca</span>';
                        const markAsReadButton = isRead 
                            ? '' 
                            : `<button type="button" class="btn-mark-read mark-as-read-btn" data-task-id="${message.id}"><i class="fas fa-check"></i> Tandai Dibaca</button>`;
                        const listItem = `
                            <li class="inbox-item-card message-item ${isRead ? 'is-read' : ''}"
                                id="message-item-${message.id}"
                                data-bs-toggle="modal" data-bs-target="#messageDetailModal"
                                data-message-id="${message.id}"
                                data-subject="${message.subject}"
                                data-content="${message.content}"
                                data-sender="${message.sender_name}"
                                data-created-at="${message.created_at}"
                                data-read-at="${isRead ? 'true' : 'false'}">
                                <div class="flex-grow-1">
                                    <h6 class="inbox-item-title">${message.subject}</h6>
                                    <p class="inbox-item-desc text-break">${message.content.substring(0, 100)}${message.content.length > 100 ? '...' : ''}</p>
                                    <div class="inbox-item-meta">
                                        <i class="fas fa-user-circle me-1"></i>
                                        <span>Dari: <strong>${message.sender_name || 'Sistem'}</strong></span>
                                        <span class="mx-1">•</span>
                                        <i class="far fa-clock me-1"></i>
                                        <span>${moment(message.created_at).format('DD MMMM YYYY HH:mm')}</span>
                                    </div>
                                </div>
                                <div class="inbox-item-actions">
                                    ${readStatusBadge}
                                    ${markAsReadButton}
                                </div>
                            </li>
                        `;
                        messageList.append(listItem);
                    });
                    messageList.off('click', '.mark-as-read-btn').on('click', '.mark-as-read-btn', function(e) {
                        e.stopPropagation();
                        const taskId = $(this).data('task-id');
                        markMessageAsRead(taskId);
                    });
                    messageList.off('click', '.message-item').on('click', '.message-item', function() {
                        const messageId = $(this).data('message-id');
                        const subject = $(this).data('subject');
                        const content = $(this).data('content');
                        const sender = $(this).data('sender');
                        const createdAt = $(this).data('created-at');
                        const readAt = $(this).data('read-at') === 'true';
                        detailMessageSubject.text(subject);
                        detailMessageSender.text(sender);
                        detailMessageDate.text(moment(createdAt).format('DD MMMM YYYY HH:mm'));
                        detailMessageContent.text(content);
                        detailMessageReadStatus.text(readAt ? 'Status: Sudah Dibaca' : 'Status: Belum Dibaca');
                        if (!readAt) {
                            markMessageAsRead(messageId);
                        }
                    });
                } else {
                    noMessagesFound.show();
                    noMessagesFound.find('h4').text('Tidak ada pesan ditemukan.');
                    noMessagesFound.find('p').text(response.message || 'Inbox pesan Anda kosong.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                loadingMessages.hide();
                noMessagesFound.show();
                let errorMessage = 'Terjadi kesalahan saat mengambil pesan.';
                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    errorMessage = jqXHR.responseJSON.message;
                }
                noMessagesFound.find('h4').text('Gagal memuat pesan.');
                noMessagesFound.find('p').text(errorMessage);
                console.error('Error fetching messages:', textStatus, errorThrown, jqXHR.responseText);
            }
        });
    }

    // --- Fungsi untuk Menandai Pesan (Task) sebagai Sudah Dibaca ---
    function markMessageAsRead(taskId) {
        $.ajax({
            url: `/api/tasks/${taskId}/mark-as-read`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    const messageItem = $(`#message-item-${taskId}`);
                    messageItem.find('.badge-soft-unread').replaceWith('<span class="badge-soft-read"><i class="fas fa-check-double"></i> Dibaca</span>');
                    messageItem.find('.mark-as-read-btn').fadeOut(200, function() { $(this).remove(); });
                    messageItem.addClass('is-read');
                    messageItem.data('read-at', 'true');
                    if (messageDetailModal.hasClass('show') && messageItem.data('message-id') == taskId) {
                        detailMessageReadStatus.text('Status: Sudah Dibaca');
                    }
                } else {
                    alert('Gagal menandai pesan sebagai dibaca: ' + (response.message || 'Terjadi kesalahan.'));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                let errorMessage = 'Gagal menandai pesan sebagai dibaca. Terjadi kesalahan jaringan atau server.';
                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    errorMessage = jqXHR.responseJSON.message;
                }
                alert(errorMessage);
                console.error('Error marking message as read:', textStatus, errorThrown, jqXHR.responseText);
            }
        });
    }

    // --- Event Listener untuk Modal Inbox PDF/Pesan (inboxPdfModal) ---
    if (inboxPdfModal.length > 0) {
        inboxPdfModal.on('show.bs.modal', function (event) {
            const studentIdSertifikat = loggedInStudentIdSertifikat;
            const studentName = loggedInStudentName;

            if (!studentIdSertifikat || studentIdSertifikat === 'null') {
                alert('ID Sertifikat Anda tidak ditemukan. Tidak dapat membuka Inbox.');
                inboxPdfModal.modal('hide');
                console.error('ID Sertifikat siswa yang login tidak tersedia.');
                return;
            }

            $('#inboxPdfModalLabel').html(`<i class="fas fa-inbox me-2"></i> Inbox Dokumen & Pesan untuk ${studentName}`);

            $(this).data('current-student-id', studentIdSertifikat);
            
            // Atur tab PDF sebagai default aktif saat modal dibuka
            const pdfTabButton = document.getElementById('pdf-tab');
            if (pdfTabButton) {
                const pdfTabInstance = new bootstrap.Tab(pdfTabButton);
                pdfTabInstance.show();
            }

            // PANGGIL FUNGSI UNTUK MEMUAT PDF LANGSUNG DI SINI
            // Ini adalah perbaikan utama yang Anda butuhkan.
            loadIndividualPdfs(studentIdSertifikat);
        });
        
        // Event listener untuk saat tab diubah.
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            const targetTabId = $(e.target).attr('id');
            const currentStudentId = inboxPdfModal.data('current-student-id');

            if (targetTabId === 'pdf-tab') {
                // Tidak perlu dipanggil lagi karena sudah dipanggil di `show.bs.modal`
                // loadIndividualPdfs(currentStudentId); 
            } else if (targetTabId === 'messages-tab') {
                loadMessagesForStudent(currentStudentId);
            }
        });
    }
});
</script>
@endsection