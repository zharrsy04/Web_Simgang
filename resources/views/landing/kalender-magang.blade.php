@extends('layouts.landing')

@section('title', 'Kalender Magang')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <h1 class="display-4 mb-3">
            <i class="fas fa-calendar-alt me-3"></i>Kalender Magang
        </h1>
        <p class="lead text-muted">Lihat jadwal magang dan ketersediaan kuota per bidang di DISKOMINFO Cilacap</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Kuota Section -->
    <div class="card mb-5" style="border: none; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
            <h5 class="mb-0">
                <i class="fas fa-chart-pie me-2"></i>Ketersediaan Kuota Per Bidang
            </h5>
        </div>
        <div class="card-body p-4">
            @forelse($kuotaBidang as $kuota)
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">
                            <i class="fas fa-building me-2"></i>{{ $kuota['nama_bidang'] }}
                        </h6>
                        <small class="text-muted">
                            {{ $kuota['terisi'] }} / {{ $kuota['kuota'] }} siswa
                        </small>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="progress" style="height: 25px; border-radius: 10px; background-color: #e9ecef;">
                        <div class="progress-bar" style="width: {{ $kuota['persentase'] }}%; background: linear-gradient(135deg, #667eea, #764ba2);" role="progressbar" aria-valuenow="{{ $kuota['persentase'] }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $kuota['persentase'] }}%
                        </div>
                    </div>
                    
                    <!-- Kuota Info -->
                    <div class="mt-2">
                        <small class="text-muted">
                            Sisa Kuota: <strong class="text-{{ $kuota['sisa'] > 0 ? 'success' : 'danger' }}">{{ $kuota['sisa'] }} siswa</strong>
                        </small>
                    </div>
                </div>
            @empty
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>Belum ada data kuota. Silakan hubungi Mentor untuk mengatur kuota magang.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Calendar Info -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="info-card p-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
                <i class="fas fa-users fa-2x mb-3"></i>
                <h6 class="mb-1">Total Peserta Aktif</h6>
                <h3 class="mb-0">{{ $semuaMagang->count() }}</h3>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="info-card p-4" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
                <i class="fas fa-briefcase fa-2x mb-3"></i>
                <h6 class="mb-1">Bidang Tersedia</h6>
                <h3 class="mb-0">{{ $kuotaBidang->count() }}</h3>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="info-card p-4" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
                <i class="fas fa-chart-bar fa-2x mb-3"></i>
                <h6 class="mb-1">Total Kuota</h6>
                <h3 class="mb-0">{{ $kuotaBidang->sum('kuota') }}</h3>
            </div>
        </div>
    </div>

    <!-- Kalender -->
    <div class="card" style="border: none; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Daftar Peserta Magang
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="padding: 1rem;">No</th>
                            <th style="padding: 1rem;">Nama</th>
                            <th style="padding: 1rem;">NIM/NIS</th>
                            <th style="padding: 1rem;">Asal Sekolah</th>
                            <th style="padding: 1rem;">Bidang Penempatan</th>
                            <th style="padding: 1rem;">Periode</th>
                            <th style="padding: 1rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($semuaMagang as $magang)
                            <tr>
                                <td style="padding: 1rem;">{{ $loop->iteration }}</td>
                                <td style="padding: 1rem;">
                                    <strong>{{ $magang->nama_lengkap }}</strong>
                                </td>
                                <td style="padding: 1rem;">{{ $magang->nim_nis }}</td>
                                <td style="padding: 1rem;">{{ $magang->asal_sekolah }}</td>
                                <td style="padding: 1rem;">
                                    <span class="badge badge-primary">{{ $magang->bidang }}</span>
                                </td>
                                <td style="padding: 1rem;">
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($magang->tgl_mulai)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($magang->tgl_selesai)->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td style="padding: 1rem;">
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $tglMulai = \Carbon\Carbon::parse($magang->tgl_mulai);
                                        $tglSelesai = \Carbon\Carbon::parse($magang->tgl_selesai);
                                    @endphp
                                    
                                    @if($now < $tglMulai)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock me-1"></i>Menunggu
                                        </span>
                                    @elseif($now > $tglSelesai)
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-check-circle me-1"></i>Selesai
                                        </span>
                                    @else
                                        <span class="badge badge-success">
                                            <i class="fas fa-play-circle me-1"></i>Aktif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    <p>Belum ada peserta magang yang terdaftar</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Login Info -->
    <div class="alert alert-info mt-5" role="alert" style="border-left: 4px solid #667eea;">
        <h6 class="alert-heading">
            <i class="fas fa-lightbulb me-2"></i>Informasi Penting
        </h6>
        <p class="mb-0">
            Jika Anda tertarik untuk mengikuti program magang di DISKOMINFO Cilacap, 
            silakan <a href="{{ route('login') }}" class="alert-link">login</a> atau 
            <a href="{{ route('register') }}" class="alert-link">daftar</a> terlebih dahulu 
            untuk mengajukan permohonan magang.
        </p>
    </div>
</div>

<style>
.info-card {
    transition: all 0.3s ease;
    transform: translateY(0);
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}

.page-header {
    text-align: center;
    padding-top: 2rem;
}

.page-header h1 {
    font-weight: 700;
    color: #333;
}

.page-header .lead {
    font-size: 1.1rem;
}

.table tbody tr {
    border-bottom: 1px solid #dee2e6;
    transition: background-color 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    padding: 0.5rem 0.75rem;
    font-size: 0.85rem;
}
</style>
@endsection
