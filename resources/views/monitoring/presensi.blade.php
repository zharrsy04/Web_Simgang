@extends('layouts.app')

@section('title', 'Monitoring Presensi Siswa')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="dashboard-header mb-0">
            <i class="fas fa-clipboard-check me-2"></i> Monitoring Presensi Siswa
        </h2>
    </div>

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

    <!-- Filter & Search Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('presensi.monitoring') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-search me-1"></i> Cari Siswa
                    </label>
                    <input type="text" name="search" class="form-control" placeholder="Nama, NIM/NIS, atau Sekolah" value="{{ $search }}">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-layer-group me-1"></i> Bidang
                    </label>
                    <select name="bidang" class="form-select">
                        <option value="">Semua Bidang</option>
                        @foreach($bidangList as $bidang)
                            <option value="{{ $bidang }}" {{ $bidangFilter == $bidang ? 'selected' : '' }}>
                                {{ $bidang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar me-1"></i> Bulan
                    </label>
                    <select name="month" class="form-select">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate((int)$currentYear, (int)$m, 1)->locale('id')->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar-alt me-1"></i> Tahun
                    </label>
                    <select name="year" class="form-select">
                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Periode -->
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Periode:</strong> {{ \Carbon\Carbon::createFromDate((int)$currentYear, (int)$currentMonth, 1)->locale('id')->translatedFormat('F') }} {{ $currentYear }} | 
        <strong>Total Siswa (Periode Ini):</strong> {{ $siswaList->count() }} siswa
    </div>

    <!-- Daftar Siswa dengan Ringkasan Presensi -->
    @if($siswaList->count() > 0)
        <div class="row g-4">
            @foreach($siswaList as $siswa)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0 h-100 presensi-card">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                            <h6 class="mb-0 fw-bold">
                                <i class="fas fa-user-graduate me-2"></i>{{ $siswa['nama_lengkap'] }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted d-block">
                                    <i class="fas fa-id-card me-1"></i> NIM/NIS: <strong>{{ $siswa['nim_nis'] }}</strong>
                                </small>
                                <small class="text-muted d-block">
                                    <i class="fas fa-school me-1"></i> {{ $siswa['asal_sekolah'] }}
                                </small>
                                <small class="text-muted d-block">
                                    <i class="fas fa-layer-group me-1"></i> Bidang: <strong class="text-primary">{{ $siswa['bidang'] }}</strong>
                                </small>
                            </div>

                            <hr>

                            <!-- Ringkasan Presensi -->
                            <div class="presensi-summary">
                                <h6 class="text-center mb-3 fw-bold">Statistik Presensi</h6>
                                
                                <div class="row text-center mb-3">
                                    <div class="col-6 mb-2">
                                        <div class="stat-box" style="background: #e8f5e9;">
                                            <div class="stat-number text-success fw-bold" style="font-size: 1.5rem;">
                                                {{ $siswa['presensi']['presentDays'] }}
                                            </div>
                                            <div class="stat-label text-muted small">Hadir</div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <div class="stat-box" style="background: #ffebee;">
                                            <div class="stat-number text-danger fw-bold" style="font-size: 1.5rem;">
                                                {{ $siswa['presensi']['absentDays'] }}
                                            </div>
                                            <div class="stat-label text-muted small">Alpha</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box" style="background: #fff3e0;">
                                            <div class="stat-number text-warning fw-bold" style="font-size: 1.5rem;">
                                                {{ $siswa['presensi']['permissionDays'] }}
                                            </div>
                                            <div class="stat-label text-muted small">Izin</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box" style="background: #e3f2fd;">
                                            <div class="stat-number text-info fw-bold" style="font-size: 1.5rem;">
                                                {{ $siswa['presensi']['totalDays'] }}
                                            </div>
                                            <div class="stat-label text-muted small">Total Hari</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mb-2">
                                    <small class="text-muted">Kehadiran</small>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $siswa['presensi']['presentPercentage'] }}%">
                                            {{ $siswa['presensi']['presentPercentage'] }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <button class="btn btn-primary btn-sm w-100" onclick="viewDetail({{ $siswa['id_sertifikat'] }}, '{{ $siswa['nama_lengkap'] }}')">
                                <i class="fas fa-list me-1"></i> Lihat Riwayat Lengkap
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="fas fa-user-slash" style="font-size: 4rem; color: #ccc;"></i>
                <p class="mt-3 text-muted">Tidak ada siswa yang sesuai dengan filter</p>
            </div>
        </div>
    @endif
</div>

<!-- Modal Detail Presensi -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Riwayat Presensi - <span id="modalSiswaName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-header {
        font-size: 1.8rem;
        font-weight: 600;
        color: #2d3748;
    }

    .presensi-card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .presensi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }

    .stat-box {
        padding: 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        transform: scale(1.05);
    }

    .card-header {
        border: none;
    }

    .btn {
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
    }
</style>

<script>
let currentIdSertifikat = null;
let detailModal = null;

document.addEventListener('DOMContentLoaded', function() {
    detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
});

function viewDetail(idSertifikat, namaSiswa) {
    currentIdSertifikat = idSertifikat;
    document.getElementById('modalSiswaName').textContent = namaSiswa;
    document.getElementById('modalContent').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat data...</p>
        </div>
    `;
    
    detailModal.show();
    
    // Fetch detail data via AJAX
    const month = {{ $currentMonth }};
    const year = {{ $currentYear }};
    
    fetch(`/presensi/monitoring/${idSertifikat}/detail?month=${month}&year=${year}`, {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayDetailContent(data);
        } else {
            showError(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Gagal memuat data presensi');
    });
}

function displayDetailContent(data) {
    let html = '';
    
    // Info Siswa
    html += `
        <div class="alert alert-info mb-4">
            <div class="row">
                <div class="col-md-6">
                    <strong>Nama:</strong> ${data.siswa.nama_lengkap}<br>
                    <strong>NIM/NIS:</strong> ${data.siswa.nim_nis}
                </div>
                <div class="col-md-6">
                    <strong>Bidang:</strong> ${data.siswa.bidang}<br>
                    <strong>Asal Sekolah:</strong> ${data.siswa.asal_sekolah}
                </div>
            </div>
        </div>
    `;
    
    if (data.attendances && data.attendances.length > 0) {
        html += `
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Status</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        data.attendances.forEach(att => {
            let statusBadge = '';
            if (att.status === 'Hadir') {
                statusBadge = '<span class="badge bg-success badge-status">Hadir</span>';
            } else if (att.status === 'Izin') {
                statusBadge = '<span class="badge bg-warning badge-status">Izin</span>';
            } else {
                statusBadge = '<span class="badge bg-danger badge-status">Alpha</span>';
            }
            
            html += `
                <tr>
                    <td><strong>${att.date}</strong></td>
                    <td>${att.day}</td>
                    <td>${statusBadge}</td>
                    <td>${att.check_in}</td>
                    <td>${att.check_out}</td>
                    <td><small>${att.location}</small></td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
    } else {
        html += `
            <div class="text-center py-5">
                <i class="fas fa-calendar-times" style="font-size: 3rem; color: #ccc;"></i>
                <p class="mt-3 text-muted">${data.message || 'Belum ada data presensi'}</p>
            </div>
        `;
    }
    
    document.getElementById('modalContent').innerHTML = html;
}

function showError(message) {
    document.getElementById('modalContent').innerHTML = `
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>${message}
        </div>
    `;
}
</script>
@endsection
