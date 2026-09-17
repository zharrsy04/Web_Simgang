@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('mentor.verifikasi-pengajuan') }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h2>
                <i class="fas fa-file-alt"></i> Detail Pengajuan Magang
            </h2>
            <small class="text-muted">Verifikasi data pengajuan siswa</small>
        </div>
    </div>


    <div class="row">
        <!-- Data Pengajuan -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle"></i> Informasi Siswa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted"><strong>Nama Lengkap</strong></label>
                            <p class="form-control-plaintext">{{ $pengajuan->nama }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted"><strong>Email</strong></label>
                            <p class="form-control-plaintext">{{ $pengajuan->user->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted"><strong>NIM/NIS</strong></label>
                            <p class="form-control-plaintext">{{ $pengajuan->nim_nis }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted"><strong>Asal Sekolah/Universitas</strong></label>
                            <div class="d-flex align-items-center gap-3 mt-1">
                                @if($pengajuan->instansi && $pengajuan->instansi->getLogoUrl())
                                    <img src="{{ $pengajuan->instansi->getLogoUrl() }}" alt="Logo Sekolah" class="rounded border p-1 shadow-sm" style="width: 70px; height: 70px; object-fit: contain; background-color: #fff;">
                                @else
                                    <div class="rounded border p-2 d-flex flex-column align-items-center justify-content-center bg-light text-muted" style="width: 70px; height: 70px;" title="Logo tidak diupload">
                                        <i class="fas fa-university fa-lg mb-1"></i>
                                        <span style="font-size: 0.65rem; text-align: center; line-height: 1;">Tanpa Logo</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="form-control-plaintext mb-0 fw-bold fs-6">{{ $pengajuan->asal_sekolah }}</p>
                                    @if($pengajuan->instansi && $pengajuan->instansi->getLogoUrl())
                                        <small class="text-success"><i class="fas fa-check-circle me-1"></i>Logo Tersedia</small>
                                    @else
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Logo tidak diupload</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted"><strong>Jurusan</strong></label>
                            <p class="form-control-plaintext">{{ $pengajuan->jurusan }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted"><strong>Bidang Magang</strong></label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary">{{ $pengajuan->bidang }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Periode Magang -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt"></i> Periode Magang
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted"><strong>Tanggal Mulai</strong></label>
                            <p class="form-control-plaintext">
                                {{ $pengajuan->tanggal_mulai ? $pengajuan->tanggal_mulai->translatedFormat('l, d F Y') : '-' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted"><strong>Tanggal Selesai</strong></label>
                            <p class="form-control-plaintext">
                                {{ $pengajuan->tanggal_selesai ? $pengajuan->tanggal_selesai->translatedFormat('l, d F Y') : '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label text-muted"><strong>Durasi Magang</strong></label>
                            <p class="form-control-plaintext">
                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($pengajuan->tanggal_selesai)) }} hari
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-upload"></i> Dokumen Pendukung
                    </h5>
                </div>
                <div class="card-body">
                    @if($pengajuan->dokumen_path)
                    <div class="alert alert-info">
                        <i class="fas fa-file-pdf"></i> Dokumen tersedia
                    </div>
                    <a href="{{ route('mentor.pengajuan.download', $pengajuan->id) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="fas fa-download"></i> Download Dokumen
                    </a>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Siswa tidak mengunggah dokumen pendukung
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Aksi Verifikasi -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Status Pengajuan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-3">
                        <strong><i class="fas fa-hourglass-half"></i> Menunggu Verifikasi</strong>
                        <br>
                        <small>Diajukan pada: {{ $pengajuan->created_at->format('d M Y H:i') }}</small>
                    </div>
                </div>
            </div>

            @if($pengajuan->status === 'pending')
            <!-- Approval Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-check-circle"></i> Terima Pengajuan
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Klik tombol di bawah untuk menerima pengajuan ini. Siswa akan segera dapat mengakses dashboard.
                    </p>
                    <form action="{{ route('mentor.pengajuan.approve', $pengajuan->id) }}" method="POST" class="approve-form">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-thumbs-up"></i> Terima Pengajuan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Rejection Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-times-circle"></i> Tolak Pengajuan
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Tolak jika bidang penuh atau syarat tidak terpenuhi. Berikan alasan penolakan.
                    </p>
                    <form action="{{ route('mentor.pengajuan.reject', $pengajuan->id) }}" method="POST" class="reject-form">
                        @csrf
                        <div class="mb-3">
                            <label for="alasan_reject" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alasan_reject" name="alasan_reject" rows="3" required placeholder="Contoh: Kuota penuh, format dokumen salah, dll."></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-thumbs-down"></i> Tolak Pengajuan
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="card shadow-sm mb-4">
                <div class="card-header {{ $pengajuan->status === 'approved' ? 'bg-success' : 'bg-danger' }} text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Status Pengajuan
                    </h5>
                </div>
                <div class="card-body text-center">
                    @if($pengajuan->status === 'approved')
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <h5 class="text-success">Telah Diterima</h5>
                        <p class="text-muted">Pengajuan ini sudah diverifikasi dan diterima.</p>
                    @else
                        <i class="fas fa-times-circle text-danger fa-3x mb-3"></i>
                        <h5 class="text-danger">Telah Ditolak</h5>
                        <p class="text-muted">Alasan: {{ $pengajuan->alasan_reject }}</p>
                    @endif
                    <a href="{{ route('mentor.verifikasi-pengajuan') }}" class="btn btn-secondary mt-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 0.5rem;
    }

    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
        padding: 1rem;
    }

    .form-control-plaintext {
        font-size: 0.95rem;
        color: #333;
    }

    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
    }
</style>

<script>
// Handle approve/reject forms with SweetAlert2 confirmation
document.addEventListener('DOMContentLoaded', function() {
    const approveForm = document.querySelector('.approve-form');
    if (approveForm) {
        approveForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const nama = '{{ addslashes($pengajuan->nama) }}';
            showConfirmPositive('Apakah Anda yakin ingin menerima pengajuan magang dari "' + nama + '"?', function() {
                const btn = approveForm.querySelector('button[type=submit]');
                if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...'; }
                approveForm.submit();
            }, 'Terima Pengajuan', 'Ya, terima', 'Batal');
        });
    }
    
    const rejectForm = document.querySelector('.reject-form');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            const alasan = document.getElementById('alasan_reject').value.trim();
            if (!alasan) {
                showWarningToast('Mohon isi alasan penolakan');
                return;
            }
            e.preventDefault();
            const nama = '{{ addslashes($pengajuan->nama) }}';
            showConfirm('Apakah Anda yakin ingin menolak pengajuan magang dari "' + nama + '"?', function() {
                const btn = rejectForm.querySelector('button[type=submit]');
                if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...'; }
                rejectForm.submit();
            }, 'Tolak Pengajuan', 'Ya, tolak', 'Batal');
        });
    }
});
</script>
@endsection
