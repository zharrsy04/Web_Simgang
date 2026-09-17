@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-clipboard-list"></i> Daftar Pengajuan Magang</h1>
    
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
    
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM/NIS</th>
                            <th>Asal Sekolah</th>
                            <th>Jurusan</th>
                            <th>Bidang</th>
                            <th>Periode</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuans as $index => $item)
                            <tr>
                                <td>{{ $pengajuans->firstItem() + $index }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nim_nis }}</td>
                                <td>{{ $item->asal_sekolah }}</td>
                                <td>{{ $item->jurusan }}</td>
                                <td><span class="badge bg-info">{{ $item->bidang }}</span></td>
                                <td class="small">
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}<br>
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                                </td>
                                <td class="small">{{ $item->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($item->status === 'pending')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($item->status === 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status === 'pending')
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-sm btn-success" 
                                                onclick="approveConfirm({{ $item->id }}, '{{ addslashes($item->nama) }}')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                onclick="showRejectModal({{ $item->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @if($item->dokumen_path)
                                                <a href="{{ route('mentor.pengajuan.download', $item->id) }}" 
                                                   class="btn btn-sm btn-info" target="_blank">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">Sudah diproses</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">Belum ada pengajuan magang</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $pengajuans->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-times-circle"></i> Tolak Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alasan_reject" rows="4" 
                            placeholder="Jelaskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Approve (hidden) -->
<form id="approveForm" method="POST" style="display:none;">
    @csrf
</form>

<script>
function approveConfirm(id, namaSiswa) {
    const nameLabel = namaSiswa ? ' dari "' + namaSiswa + '"' : '';
    showConfirmPositive('Apakah Anda yakin ingin menyetujui pengajuan magang' + nameLabel + '?', function() {
        const form = document.getElementById('approveForm');
        form.action = `/mentor/pengajuan/${id}/approve`;
        form.submit();
    }, 'Setujui Pengajuan', 'Ya, setujui', 'Batal');
}

function showRejectModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    const form = document.getElementById('rejectForm');
    form.action = `/mentor/pengajuan/${id}/reject`;
    modal.show();
}
</script>

<style>
.table th {
    font-weight: 600;
    color: #495057;
    white-space: nowrap;
}

.card {
    border: none;
    border-radius: 10px;
}
</style>
@endsection
