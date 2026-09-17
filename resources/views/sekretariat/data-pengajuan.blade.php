@extends('layouts.app')

@section('title', 'Data Pengajuan Magang')

@section('content')
<div class="container-fluid py-5">
    <div class="mb-4">
        <a href="{{ route('sekretariat.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <h2 class="dashboard-header">Data Pengajuan Magang</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
            <h5 class="mb-0">
                <i class="fas fa-file-alt me-2"></i> Daftar Pengajuan Magang
            </h5>
        </div>
        <div class="card-body p-0">
            @if($pengajuan && count($pengajuan) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Email</th>
                                <th>Asal Sekolah</th>
                                <th>Status</th>
                                <th>Tanggal Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengajuan as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->instansi && $item->instansi->getLogoUrl())
                                                <img src="{{ $item->instansi->getLogoUrl() }}" alt="Logo" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;" title="{{ $item->instansi->nama }}">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;" title="Sekolah Belum Terdaftar di Master Data">
                                                    <i class="fas fa-graduation-cap text-muted" style="font-size: 16px;"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong style="color: #667eea;">{{ $item->user->nama ?? 'N/A' }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $item->user->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $item->asal_sekolah ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = 'bg-warning';
                                            $statusText = 'Menunggu';
                                            if ($item->status == 'approved') {
                                                $statusClass = 'bg-success';
                                                $statusText = 'Disetujui';
                                            } elseif ($item->status == 'rejected') {
                                                $statusClass = 'bg-danger';
                                                $statusText = 'Ditolak';
                                            }
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $item->created_at->format('d M Y H:i') }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-light border-0">
                    {{ $pengajuan->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="mt-3">Belum ada data pengajuan magang</p>
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

    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
</style>
@endsection
