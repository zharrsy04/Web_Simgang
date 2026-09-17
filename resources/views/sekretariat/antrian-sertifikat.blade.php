@extends('layouts.app')

@section('title', 'Antrian Sertifikat')

@section('content')
<div class="container-fluid py-5">
    <h2 class="dashboard-header mb-4">Antrian Sertifikat</h2>

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

    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm stat-card" style="border-top: 4px solid #667eea;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #667eea;">{{ $stats['total'] }}</h3>
                    <p class="text-muted mb-0">Total Antrian</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm stat-card" style="border-top: 4px solid #ffc107;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #ffc107;">{{ $stats['draft'] }}</h3>
                    <p class="text-muted mb-0">Draft</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm stat-card" style="border-top: 4px solid #00d4ff;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #00d4ff;">{{ $stats['ready_for_handover'] }}</h3>
                    <p class="text-muted mb-0">Siap Diserahkan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0 shadow-sm stat-card" style="border-top: 4px solid #00d97e;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #00d97e;">{{ $stats['handed_over'] }}</h3>
                    <p class="text-muted mb-0">Sudah Diserahkan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('sekretariat.antrian-sertifikat') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="bidang" class="form-label fw-600">Filter Bidang</label>
                        <select name="bidang" id="bidang" class="form-select">
                            <option value="">Semua Bidang</option>
                            @foreach($bidangList as $bidang)
                                <option value="{{ $bidang }}" {{ $bidangFilter === $bidang ? 'selected' : '' }}>{{ $bidang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label fw-600">Filter Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ $statusFilter === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Certificate Queue Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
            <h5 class="mb-0">
                <i class="fas fa-file-certificate me-2"></i> Daftar Sertifikat
            </h5>
        </div>
        <div class="card-body p-0">
            @if($sertifikats->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th>No. Sertifikat</th>
                                <th>Nama Siswa</th>
                                <th>Bidang</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sertifikats as $sertifikat)
                                <tr>
                                    <td>{{ $sertifikat->no_sertifikat ?? '-' }}</td>
                                    <td>
                                        <strong>{{ $sertifikat->nama_lengkap }}</strong><br>
                                        <small class="text-muted">{{ $sertifikat->nim_nis }}</small>
                                    </td>
                                    <td>
                                        @if($sertifikat->bidang)
                                            @if($sertifikat->bidang === 'Sekretariat')
                                                <span class="badge" style="background-color: #6c757d; color: white;">{{ $sertifikat->bidang }}</span>
                                            @elseif($sertifikat->bidang === 'Bidang Informatika')
                                                <span class="badge" style="background-color: #0d6efd; color: white;">{{ $sertifikat->bidang }}</span>
                                            @elseif($sertifikat->bidang === 'Bidang Statistik dan Persandian')
                                                <span class="badge" style="background-color: #198754; color: white;">{{ $sertifikat->bidang }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $sertifikat->bidang }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sertifikat->sertifikat_status === 'draft')
                                            <span class="badge bg-warning">Draft</span>
                                        @elseif($sertifikat->sertifikat_status === 'ready_for_handover')
                                            <span class="badge bg-info">Siap Diserahkan ke Mentor</span>
                                        @elseif($sertifikat->sertifikat_status === 'handed_over')
                                            <span class="badge bg-success">Sudah Diserahkan ke Mentor</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $sertifikat->sertifikat_status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $sertifikat->tgl_sertifikat ? $sertifikat->tgl_sertifikat->format('d M Y') : ($sertifikat->tgl_mulai ? $sertifikat->tgl_mulai->format('d M Y') : '-') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('sekretariat.edit-sertifikat', $sertifikat->id_sertifikat) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('sertifikat.cetak-gabungan-v5', $sertifikat->id_sertifikat) }}" 
                                               target="_blank"
                                               class="btn btn-sm btn-outline-success" title="Cetak">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @if($sertifikat->sertifikat_status === 'draft' && $sertifikat->created_by_role === 'sekretariat')
                                                <button onclick="prepareHandover({{ $sertifikat->id_sertifikat }})" 
                                                        class="btn btn-sm btn-outline-info" title="Siapkan untuk Serah Terima">
                                                    <i class="fas fa-hand-holding"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $sertifikats->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada sertifikat dalam antrian</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function prepareHandover(id) {
    if (confirm('Apakah Anda yakin ingin menandai sertifikat ini sebagai siap untuk diserahkan ke Mentor?')) {
        fetch(`{{ route('sekretariat.prepare-handover', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessToast(data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showErrorToast(data.message);
            }
        })
        .catch(error => {
            showErrorToast('Terjadi kesalahan saat memproses permintaan');
        });
    }
}
</script>
@endsection
