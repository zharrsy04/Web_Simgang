@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Status Card -->
            <div class="card shadow-lg border-0 rounded-4 text-center">
                <div class="card-body p-5">
                    <!-- Icon Animasi -->
                    <div class="mb-4">
                        <div style="display: inline-block; position: relative; width: 100px; height: 100px; background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-hourglass-half" style="font-size: 3rem; color: #667eea; animation: spin 2s linear infinite;"></i>
                        </div>
                    </div>

                    <!-- Greeting -->
                    <h4 class="mb-4 text-center" style="color: #2c3e50; font-weight: 700;">Selamat Datang, {{ ucwords(strtolower($pengajuan->nama)) }}!</h4>

                    <!-- Status Title -->
                    <h2 class="mb-2" style="color: #2c3e50; font-weight: 700;">Menunggu Verifikasi</h2>
                    <p class="text-muted mb-4" style="font-size: 1.05rem;">Pengajuan magang Anda sedang ditinjau oleh mentor</p>

                    <!-- Detail Info -->
                    <div class="alert alert-info text-start mb-4" style="background: #e0e7ff; border: none;">
                        <strong style="color: #667eea;">
                            <i class="fas fa-info-circle me-2"></i>Informasi Pengajuan
                        </strong>
                        <div class="mt-3">
                            <div class="row mb-2">
                                <div class="col-5 text-start">
                                    <small class="text-muted">Nama:</small>
                                </div>
                                <div class="col-7 text-start">
                                    <strong style="color: #2c3e50;">{{ $pengajuan->nama }}</strong>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-start">
                                    <small class="text-muted">Bidang:</small>
                                </div>
                                <div class="col-7 text-start">
                                    <span class="badge bg-primary">{{ $pengajuan->bidang }}</span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-start">
                                    <small class="text-muted">Periode:</small>
                                </div>
                                <div class="col-7 text-start">
                                    <small style="color: #2c3e50;">
                                        {{ $pengajuan->tanggal_mulai ? $pengajuan->tanggal_mulai->translatedFormat('d F Y') : '-' }} - 
                                        {{ $pengajuan->tanggal_selesai ? $pengajuan->tanggal_selesai->translatedFormat('d F Y') : '-' }}
                                    </small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5 text-start">
                                    <small class="text-muted">Diajukan:</small>
                                </div>
                                <div class="col-7 text-start">
                                    <small style="color: #2c3e50;">{{ $pengajuan->created_at->format('d M Y H:i') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Steps -->
                    <div class="mb-4">
                        <div class="row g-0">
                            <div class="col">
                                <div style="text-align: center;">
                                    <div style="width: 40px; height: 40px; background: #667eea; color: white; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 8px;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <small style="display: block; color: #2c3e50; font-weight: 600;">Pengajuan Dikirim</small>
                                </div>
                            </div>
                            <div class="col d-flex align-items-center justify-content-center px-2">
                                <div style="flex: 1; height: 2px; background: #667eea;"></div>
                            </div>
                            <div class="col">
                                <div style="text-align: center;">
                                    <div style="width: 40px; height: 40px; background: #ffc107; color: white; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 8px; animation: pulse 2s infinite;">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                    <small style="display: block; color: #2c3e50; font-weight: 600;">Sedang Diverifikasi</small>
                                </div>
                            </div>
                            <div class="col d-flex align-items-center justify-content-center px-2">
                                <div style="flex: 1; height: 2px; background: #e0e0e0;"></div>
                            </div>
                            <div class="col">
                                <div style="text-align: center;">
                                    <div style="width: 40px; height: 40px; background: #e0e0e0; color: #999; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-bottom: 8px;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <small style="display: block; color: #999; font-weight: 600;">Verifikasi Selesai</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Message -->
                    <div class="alert alert-warning text-start mb-4" style="background: #fff8e1; border: none;">
                        <strong style="color: #ff6500;">
                            <i class="fas fa-clock me-2"></i>Estimasi Waktu
                        </strong>
                        <p class="mt-2 mb-0" style="color: #666;">
                            Proses verifikasi biasanya membutuhkan <strong>1-3 hari kerja</strong>. Anda akan menerima notifikasi via email setelah mentor selesai melakukan verifikasi.
                        </p>
                    </div>

                    <!-- What to Expect -->
                    <div class="text-start mb-4">
                        <h6 style="color: #2c3e50; font-weight: 700; margin-bottom: 12px;">
                            <i class="fas fa-list-check text-primary me-2"></i>Apa yang Akan Terjadi Selanjutnya?
                        </h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>✓ Diterima:</strong> Anda bisa langsung mengakses Dashboard Siswa
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-times-circle text-danger me-2"></i>
                                <strong>✗ Ditolak:</strong> Anda bisa mengajukan kembali setelah memperbaiki data
                            </li>
                            <li>
                                <i class="fas fa-bell text-info me-2"></i>
                                <strong>🔔 Notifikasi:</strong> Email notifikasi akan dikirim segera setelah ada perubahan
                            </li>
                        </ul>
                    </div>

                    <!-- Kontak Support -->
                    <div class="alert alert-light text-start" style="border: 2px solid #667eea;">
                        <strong style="color: #667eea;">
                            <i class="fas fa-phone-alt me-2"></i>Butuh Bantuan?
                        </strong>
                        <p class="mt-2 mb-0 text-muted">
                            Jika ada pertanyaan tentang pengajuan Anda, silakan hubungi <strong>Sekretariat Diskominfo</strong> atau <strong>Mentor Program</strong>.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('pengajuan.detailSiswa') }}" class="btn btn-outline-secondary btn-lg" style="font-weight: 600;">
                            <i class="fas fa-eye me-2"></i>Lihat Detail Pengajuan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .card {
        border: none;
        border-radius: 10px;
    }

    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>
@endsection
