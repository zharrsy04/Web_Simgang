@extends('layouts.app')

@section('title', 'Dashboard Sekretariat')

@section('content')
<div class="container-fluid py-5">
    <h2 class="dashboard-header mb-4">Dashboard Sekretariat</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Statistics -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm stat-card" style="border-top: 4px solid #667eea;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #667eea;">{{ $totalBidang }}</h3>
                    <p class="text-muted mb-0">Total Bidang</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm stat-card" style="border-top: 4px solid #00d4ff;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #00d4ff;">{{ $totalMentor }}</h3>
                    <p class="text-muted mb-0">Total Mentor</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm stat-card" style="border-top: 4px solid #00d97e;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #00d97e;">{{ $totalSiswa }}</h3>
                    <p class="text-muted mb-0">Total Siswa</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm stat-card" style="border-top: 4px solid #ffc107;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #ffc107;">{{ $totalPengajuan }}</h3>
                    <p class="text-muted mb-0">Pengajuan Magang</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics Row -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm stat-card" style="border-top: 4px solid #667eea;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #667eea;">{{ $siswaAktif }}</h3>
                    <p class="text-muted mb-0">Siswa Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm stat-card" style="border-top: 4px solid #00d97e;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #00d97e;">{{ $siswaLulus }}</h3>
                    <p class="text-muted mb-0">Siswa Lulus</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm stat-card" style="border-top: 4px solid #ff6b6b;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #ff6b6b;">{{ $totalSertifikat }}</h3>
                    <p class="text-muted mb-0">Total Sertifikat</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengumuman Terbaru Section untuk Sekretariat -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-bullhorn me-2"></i> Pengumuman Terbaru
                        </h5>
                        <a href="{{ route('sekretariat.pengumuman') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit"></i> Kelola Pengumuman
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($pengumumans && $pengumumans->isNotEmpty())
                        <div class="row g-3">
                            @foreach($pengumumans->take(3) as $pengumuman)
                                <div class="col-md-4">
                                    <div class="card border-start border-primary border-3 h-100">
                                        <div class="card-body">
                                            <h6 class="card-title fw-bold text-primary">{{ $pengumuman->title }}</h6>
                                            @if($pengumuman->type)
                                                <span class="badge bg-primary mb-2">{{ $pengumuman->type }}</span>
                                            @endif
                                            <p class="card-text text-muted small">
                                                {{ Str::limit($pengumuman->content, 100) }}
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $pengumuman->published_at ? $pengumuman->published_at->locale('id')->translatedFormat('d F Y') : $pengumuman->created_at->locale('id')->translatedFormat('d F Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-bell-slash" style="font-size: 2rem; color: #ccc;"></i>
                            <p class="mt-2 mb-0">Belum ada pengumuman terbaru</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Bidang Overview -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-layer-group me-2"></i> Daftar Bidang
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($bidangStats && count($bidangStats) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th>Bidang</th>
                                        <th class="text-center">Mentor</th>
                                        <th class="text-center">Siswa</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bidangStats as $stat)
                                        <tr>
                                            <td>
                                                <strong style="color: #667eea;">{{ $stat['bidang'] }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $stat['mentor_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">{{ $stat['siswa_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('sekretariat.view-field', $stat['bidang']) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="mt-3">Belum ada data bidang</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Access & Pending Handovers -->
        <div class="col-lg-4">
            <!-- Pending Handovers -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-hourglass-half me-2"></i> Handover Menunggu
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($pendingHandovers && count($pendingHandovers) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($pendingHandovers->take(5) as $handover)
                                <div class="list-group-item border-0 py-3 px-3" style="border-bottom: 1px solid #e9ecef;">
                                    <h6 class="mb-1 fw-bold" style="color: #667eea;">
                                        {{ Str::limit($handover->sertifikat->nama_lengkap, 20) }}
                                    </h6>
                                    <small class="text-muted d-block">
                                        Untuk: <strong>{{ $handover->toUser->username }}</strong>
                                    </small>
                                    <small class="text-muted d-block">
                                        {{ $handover->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-check-circle" style="font-size: 2rem; color: #00d97e;"></i>
                            <p class="mt-2 mb-0">Semua sertifikat sudah diterima</p>
                        </div>
                    @endif
                </div>
                @if($pendingHandovers && count($pendingHandovers) > 0)
                    <div class="card-footer bg-light border-0">
                        <a href="{{ route('sekretariat.pending-handovers') }}" class="btn btn-outline-primary btn-sm w-100">
                            Lihat Semua
                        </a>
                    </div>
                @endif
            </div>

            <!-- Recent Certificates -->
            <div class="card shadow-sm border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i> Sertifikat Terbaru
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recentSertifikat && count($recentSertifikat) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentSertifikat->take(6) as $cert)
                                <div class="list-group-item border-0 py-2 px-3" style="border-bottom: 1px solid #e9ecef;">
                                    <small class="d-block fw-bold" style="color: #667eea;">
                                        {{ Str::limit($cert->nama_lengkap, 25) }}
                                    </small>
                                    <small class="text-muted d-block">
                                        {{ $cert->bidang }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-header {
        font-size: 1.8rem;
        font-weight: 600;
        color: #f8f9fa;
        text-align: center;
        position: relative;
        margin-bottom: 2rem;
    }

    .dashboard-header:after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 3px;
    }

    .stat-card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-2px);
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-2px);
    }

    .list-group-item {
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background-color: rgba(102, 126, 234, 0.05) !important;
    }

    .btn {
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
</style>
@endsection
