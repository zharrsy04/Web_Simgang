@extends('layouts.app')

@section('title', 'Handover Sertifikat Menunggu Penerimaan')

@section('content')
<div class="container-fluid py-5">
    <div class="mb-4">
        <a href="{{ route('sekretariat.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <h2 class="dashboard-header">Handover Sertifikat Menunggu Penerimaan</h2>

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

    <div class="card shadow-sm border-0">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
            <h5 class="mb-0">
                <i class="fas fa-hourglass-half me-2"></i> Daftar Handover
            </h5>
        </div>
        <div class="card-body p-0">
            @if($handovers && count($handovers) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Bidang</th>
                                <th>Mentor Penerima</th>
                                <th>Status</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($handovers as $handover)
                                <tr>
                                    <td>
                                        <strong style="color: #667eea;">{{ $handover->sertifikat->nama_lengkap }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $handover->sertifikat->nim_nis }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $handover->sertifikat->bidang }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $handover->toUser->username ?? 'N/A' }}</small>
                                        <br>
                                        <small class="text-muted">{{ $handover->toUser->nama ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">{{ ucfirst($handover->status) }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $handover->created_at->format('d M Y H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($handover->notes)
                                            <small class="text-muted">{{ Str::limit($handover->notes, 30) }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-info" title="Lihat Detail" 
                                                data-bs-toggle="modal" data-bs-target="#detailModal{{ $handover->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('sertifikat.cetak-gabungan-v5', $handover->sertifikat->id_sertifikat) }}" 
                                               target="_blank"
                                               class="btn btn-outline-secondary" title="Cetak Sertifikat">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Detail Modal -->
                                <div class="modal fade" id="detailModal{{ $handover->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                                                <h5 class="modal-title">Detail Handover</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold">Informasi Siswa</h6>
                                                        <p class="mb-2">
                                                            <strong>Nama:</strong> {{ $handover->sertifikat->nama_lengkap }}
                                                        </p>
                                                        <p class="mb-2">
                                                            <strong>NIM/NIS:</strong> {{ $handover->sertifikat->nim_nis }}
                                                        </p>
                                                        <p class="mb-2">
                                                            <strong>Bidang:</strong> {{ $handover->sertifikat->bidang }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold">Informasi Handover</h6>
                                                        <p class="mb-2">
                                                            <strong>Status:</strong> <span class="badge bg-warning">{{ ucfirst($handover->status) }}</span>
                                                        </p>
                                                        <p class="mb-2">
                                                            <strong>Mentor Penerima:</strong> {{ $handover->toUser->username }}
                                                        </p>
                                                        <p class="mb-2">
                                                            <strong>Tanggal:</strong> {{ $handover->created_at->format('d M Y H:i') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                @if($handover->notes)
                                                    <div class="alert alert-info mb-0">
                                                        <strong>Catatan:</strong><br>
                                                        {{ $handover->notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-check-circle" style="font-size: 3rem; color: #00d97e;"></i>
                    <p class="mt-3 mb-0">Semua sertifikat sudah diterima oleh mentor</p>
                </div>
            @endif
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

    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
</style>
@endsection
