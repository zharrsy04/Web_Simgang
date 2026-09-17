@extends('layouts.app')

@section('title', 'Kelola Kuota Bidang')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="dashboard-header mb-4">Kelola Kuota Bidang Magang</h2>
        </div>
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

    <div class="row">
        <!-- Quota List -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i> Daftar Kuota Bidang
                    </h5>
                </div>
                <div class="card-body">
                    @if($kuotaBidang && count($kuotaBidang) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th>Bidang</th>
                                        <th class="text-center">Kuota</th>
                                        <th class="text-center">Terisi</th>
                                        <th class="text-center">Sisa</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kuotaBidang as $kuota)
                                        <tr>
                                            <td>
                                                <strong style="color: #5a60ff;">{{ $kuota['nama_bidang'] }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                                    {{ $kuota['kuota'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">
                                                    {{ $kuota['terisi'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">
                                                    {{ $kuota['sisa'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editQuotaModal"
                                                        onclick="editQuota({{ $kuota['id'] }}, '{{ $kuota['nama_bidang'] }}', {{ $kuota['kuota'] }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteQuota({{ $kuota['id'] }}, '{{ $kuota['nama_bidang'] }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Belum ada data kuota bidang</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Add New Quota -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Bidang Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form id="addQuotaForm">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_bidang" class="form-label fw-bold">Nama Bidang</label>
                            <input type="text" class="form-control" id="nama_bidang" name="nama_bidang" 
                                   placeholder="Contoh: Bidang Informatika" required>
                            <small class="text-danger" id="nama_bidang_error" style="display: none;"></small>
                        </div>

                        <div class="mb-3">
                            <label for="kuota" class="form-label fw-bold">Kuota</label>
                            <input type="number" class="form-control" id="kuota" name="kuota" 
                                   min="1" max="999" placeholder="Masukkan jumlah kuota" required>
                            <small class="text-danger" id="kuota_error" style="display: none;"></small>
                        </div>

                        <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                            <i class="fas fa-plus me-2"></i> Tambah Bidang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3" style="color: #5a60ff;">
                        <i class="fas fa-info-circle me-2"></i> Informasi
                    </h6>
                    <ul class="small text-muted">
                        <li>Kuota adalah kapasitas maksimal peserta untuk setiap bidang</li>
                        <li>Terisi menunjukkan jumlah peserta aktif pada periode saat ini</li>
                        <li>Sisa adalah kuota yang masih tersedia</li>
                        <li>Anda dapat mengubah atau menghapus bidang sesuai kebutuhan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Quota Modal -->
<div class="modal fade" id="editQuotaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i> Edit Kuota Bidang
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editQuotaForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_quota_id" name="id">
                    
                    <div class="mb-3">
                        <label for="edit_nama_bidang" class="form-label fw-bold">Nama Bidang</label>
                        <input type="text" class="form-control" id="edit_nama_bidang" disabled>
                        <small class="text-muted">Nama bidang tidak dapat diubah</small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_kuota" class="form-label fw-bold">Kuota</label>
                        <input type="number" class="form-control" id="edit_kuota" name="kuota" 
                               min="0" max="999" required>
                        <small class="text-danger" id="edit_kuota_error" style="display: none;"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;" 
                        onclick="submitEditQuota()">
                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                </button>
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

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-2px);
    }

    .table-responsive {
        max-height: 600px;
        overflow-y: auto;
    }

    .btn {
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }
</style>

<script>
    function editQuota(id, namaBidang, kuota) {
        document.getElementById('edit_quota_id').value = id;
        document.getElementById('edit_nama_bidang').value = namaBidang;
        document.getElementById('edit_kuota').value = kuota;
    }

    function submitEditQuota() {
        const id = document.getElementById('edit_quota_id').value;
        const kuota = document.getElementById('edit_kuota').value;

        fetch(`/api/kuota-bidang/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content || 
                               document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ kuota: kuota })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessToast('Kuota berhasil diperbarui!');
                setTimeout(() => location.reload(), 1500);
            } else {
                showErrorToast(data.message || 'Gagal memperbarui kuota');
                if (data.errors) {
                    const firstError = Object.values(data.errors)[0][0];
                    document.getElementById('edit_kuota_error').textContent = firstError;
                    document.getElementById('edit_kuota_error').style.display = 'block';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorToast('Terjadi kesalahan saat memperbarui kuota');
        });
    }

    function deleteQuota(id, namaBidang) {
        showConfirm(
            `Bidang "${namaBidang}" akan dihapus permanen beserta semua data kuotanya.`,
            function() {
                fetch(`/api/kuota-bidang/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content || 
                                       document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessToast('Bidang berhasil dihapus!');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showErrorToast(data.message || 'Gagal menghapus bidang');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorToast('Terjadi kesalahan saat menghapus bidang');
                });
            },
            `Hapus Bidang "${namaBidang}"`,
            'Ya, Hapus',
            'Batal'
        );
    }

    document.getElementById('addQuotaForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const namaBidang = document.getElementById('nama_bidang').value;
        const kuota = document.getElementById('kuota').value;

        fetch('/api/kuota-bidang', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content || 
                               document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ nama_bidang: namaBidang, kuota: kuota })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessToast(`Bidang "${namaBidang}" berhasil ditambahkan!`);
                document.getElementById('addQuotaForm').reset();
                setTimeout(() => location.reload(), 1500);
            } else {
                showErrorToast(data.message || 'Gagal menambahkan bidang');
                if (data.errors) {
                    Object.entries(data.errors).forEach(([field, messages]) => {
                        const errorEl = document.getElementById(`${field}_error`);
                        if (errorEl) {
                            errorEl.textContent = messages[0];
                            errorEl.style.display = 'block';
                        }
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorToast('Terjadi kesalahan saat menambahkan bidang');
        });
    });
</script>
@endsection
