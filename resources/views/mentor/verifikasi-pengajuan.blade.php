@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-check-circle text-primary"></i> Verifikasi Pengajuan Magang
                    </h2>
                    <small class="text-muted">Kelola dan verifikasi pengajuan magang dari siswa</small>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4 g-3">
                <div class="col-md-3">
                    <div class="card text-white shadow-sm border-0" style="background-color: #f59e0b; border-radius: 8px;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-text text-white fw-semibold mb-1" style="font-size: 0.9rem; opacity: 0.95;">Menunggu Verifikasi</h6>
                                    <h2 class="mb-0 text-white fw-bold" style="font-size: 2.2rem;">{{ $totalPending }}</h2>
                                </div>
                                <i class="fas fa-hourglass-half fa-3x" style="color: rgba(255, 255, 255, 0.4);"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white shadow-sm border-0" style="background-color: #198754; border-radius: 8px;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-text text-white fw-semibold mb-1" style="font-size: 0.9rem; opacity: 0.95;">Diterima</h6>
                                    <h2 class="mb-0 text-white fw-bold" style="font-size: 2.2rem;">{{ $totalApproved }}</h2>
                                </div>
                                <i class="fas fa-check-circle fa-3x" style="color: rgba(255, 255, 255, 0.4);"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white shadow-sm border-0" style="background-color: #dc3545; border-radius: 8px;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-text text-white fw-semibold mb-1" style="font-size: 0.9rem; opacity: 0.95;">Ditolak</h6>
                                    <h2 class="mb-0 text-white fw-bold" style="font-size: 2.2rem;">{{ $totalRejected }}</h2>
                                </div>
                                <i class="fas fa-times-circle fa-3x" style="color: rgba(255, 255, 255, 0.4);"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white shadow-sm border-0" style="background-color: #00c0ef; border-radius: 8px;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-text text-white fw-semibold mb-1" style="font-size: 0.9rem; opacity: 0.95;">Total</h6>
                                    <h2 class="mb-0 text-white fw-bold" style="font-size: 2.2rem;">{{ $totalPending + $totalApproved + $totalRejected }}</h2>
                                </div>
                                <i class="fas fa-file-alt fa-3x" style="color: rgba(255, 255, 255, 0.4);"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        </div>
    </div>

    <!-- Pengajuan List -->
    <div class="card shadow-sm border-0" style="border-radius: 8px; overflow: hidden;">
        <div class="card-header text-white" style="background-color: #0d6efd; padding: 14px 20px; border-radius: 8px 8px 0 0;">
            <h5 class="mb-0 fw-semibold" style="font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-list-ul"></i> Daftar Pengajuan Menunggu Verifikasi ({{ $pengajuans->count() }})
            </h5>
        </div>
        <div class="card-body p-0">
            @if($pengajuans->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Asal Sekolah</th>
                            <th>Jurusan</th>
                            <th>Bidang Magang</th>
                            <th>Periode</th>
                            <th>Dokumen</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $index => $pengajuan)
                        <tr>
                            <td>{{ ($pengajuans->currentPage() - 1) * $pengajuans->perPage() + $index + 1 }}</td>
                            <td>
                                <strong>{{ $pengajuan->nama }}</strong><br>
                                <small class="text-muted">{{ $pengajuan->user->email }}</small>
                            </td>
                            <td>{{ $pengajuan->asal_sekolah }}</td>
                            <td>{{ $pengajuan->jurusan }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $pengajuan->bidang }}</span>
                            </td>
                            <td>
                                <small>
                                    {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }} -
                                    {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}
                                </small>
                            </td>
                            <td>
                                @if($pengajuan->dokumen_path)
                                <a href="{{ route('mentor.pengajuan.download', $pengajuan->id) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Download dokumen">
                                    <i class="fas fa-file-download"></i>
                                </a>
                                @else
                                <span class="text-muted"><small>Tidak ada</small></span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $pengajuan->created_at->format('d M Y H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('mentor.detail-pengajuan', $pengajuan->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3" style="display: block;"></i>
                                <p class="text-muted">Tidak ada pengajuan menunggu verifikasi</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pengajuans->hasPages())
            <div class="d-flex justify-content-center p-3">
                {{ $pengajuans->links('pagination::bootstrap-4') }}
            </div>
            @endif
            @else
            <div class="text-center py-5">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-none" style="width: 76px; height: 76px; background-color: #198754;">
                    <i class="fas fa-check text-white" style="font-size: 2.3rem;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.15rem;">Semua Pengajuan Terverifikasi</h5>
                <p class="text-muted mb-0" style="font-size: 0.95rem;">Tidak ada pengajuan yang menunggu verifikasi saat ini</p>
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
    }

    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }

    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
</style>
@endsection
