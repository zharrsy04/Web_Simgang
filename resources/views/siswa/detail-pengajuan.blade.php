@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px;">
                <h1 class="mb-2"><i class="fas fa-file-alt me-2"></i>Detail Pengajuan Magang</h1>
                <p class="mb-0 opacity-90">Data yang telah Anda ajukan (Read-only)</p>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Data Diri & Pengajuan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Nama Lengkap</label>
                            <input type="text" class="form-control bg-light" value="{{ $pengajuan->nama }}" disabled>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">NIM/NIS</label>
                            <input type="text" class="form-control bg-light" value="{{ $pengajuan->nim_nis }}" disabled>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Tempat Lahir</label>
                            <input type="text" class="form-control bg-light" value="{{ $pengajuan->tempat_lahir }}" disabled>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Tanggal Lahir</label>
                            <input type="text" class="form-control bg-light" value="{{ !empty($pengajuan->tgl_lahir) ? $pengajuan->tgl_lahir->translatedFormat('l, d F Y') : '-' }}" disabled>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-600">Asal Sekolah/Universitas</label>
                        <input type="text" class="form-control bg-light" value="{{ $pengajuan->asal_sekolah }}" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-600">Jurusan</label>
                        <input type="text" class="form-control bg-light" value="{{ $pengajuan->jurusan }}" disabled>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-600">Bidang Magang</label>
                        <input type="text" class="form-control bg-light" value="{{ $pengajuan->bidang }}" disabled>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Tanggal Mulai</label>
                            <input type="text" class="form-control bg-light" value="{{ !empty($pengajuan->tanggal_mulai) ? $pengajuan->tanggal_mulai->translatedFormat('l, d F Y') : '-' }}" disabled>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Tanggal Selesai</label>
                            <input type="text" class="form-control bg-light" value="{{ !empty($pengajuan->tanggal_selesai) ? $pengajuan->tanggal_selesai->translatedFormat('l, d F Y') : '-' }}" disabled>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">Dokumen Pendukung</label>
                        @if($pengajuan->dokumen_path)
                            <div class="p-3 border rounded bg-light">
                                <i class="fas fa-file-alt text-primary me-2"></i> Dokumen telah diupload.
                                <a href="{{ Storage::url($pengajuan->dokumen_path) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">Lihat Dokumen</a>
                            </div>
                        @else
                            <input type="text" class="form-control bg-light" value="Tidak ada dokumen yang diupload" disabled>
                        @endif
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('siswa.dashboard') }}" class="btn btn-lg btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Halaman Menunggu Verifikasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection
