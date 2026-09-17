@extends('layouts.app')

@section('title', 'Data Sertifikat')

@section('content')
<div class="container-fluid py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('sekretariat.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
        <div>
            <a href="{{ route('sekretariat.create-sertifikat') }}" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                <i class="fas fa-plus me-1"></i> Tambah Sertifikat
            </a>
        </div>
    </div>

    <h2 class="dashboard-header">Data Sertifikat</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
            <h5 class="mb-0">
                <i class="fas fa-certificate me-2"></i> Daftar Sertifikat
            </h5>
        </div>
        <div class="card-body p-0">
            @if($sertifikat && count($sertifikat) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Bidang</th>
                                <th>Dibuat Oleh</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sertifikat as $item)
                                <tr>
                                    <td>
                                        <strong style="color: #667eea;">{{ $item->nama_lengkap }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $item->nim_nis }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $item->bidang }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $item->createdBy->nama ?? $item->createdBy->username }}</small>
                                        <br>
                                        <small class="text-muted">
                                            @if($item->created_by_role == 'sekretariat')
                                                <span class="badge bg-info">Sekretariat</span>
                                            @else
                                                <span class="badge bg-secondary">Mentor</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <small>{{ $item->tgl_mulai->format('d M Y') }} - {{ $item->tgl_selesai->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = 'bg-secondary';
                                            $statusText = $item->sertifikat_status ?? 'Draft';
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ ucfirst($statusText) }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('sekretariat.edit-sertifikat', $item->id_sertifikat) }}" class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('sertifikat.cetak-gabungan-v5', $item->id_sertifikat) }}" target="_blank" class="btn btn-outline-success" title="Cetak">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" title="Hapus" onclick="deleteSertifikat({{ $item->id_sertifikat }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-light border-0">
                    {{ $sertifikat->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="mt-3">Belum ada data sertifikat</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function deleteSertifikat(id) {
        if (confirm('Apakah Anda yakin ingin menghapus sertifikat ini?')) {
            fetch(`/sekretariat/sertifikat/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Redirect ke halaman daftar sertifikat setelah berhasil menghapus
                    window.location.href = "{{ route('sekretariat.data-sertifikat') }}";
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menghapus sertifikat');
            });
        }
    }
</script>

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

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
</style>
@endsection
