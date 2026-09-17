@extends('layouts.app')

@section('title', 'Data Siswa dan Mahasiswa')

@section('content')
<div class="container-fluid py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('sekretariat.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
        <div>
            <a href="{{ route('sekretariat.create-siswa') }}" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                <i class="fas fa-plus me-1"></i> Tambah Siswa
            </a>
        </div>
    </div>

    <h2 class="dashboard-header">Data Siswa dan Mahasiswa</h2>

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
                <i class="fas fa-users me-2"></i> Data Siswa / Mahasiswa PKL Keseluruhan
            </h5>
        </div>

        <div class="card-body p-0">
            @if($siswa && count($siswa) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>NIM/NIS</th>
                                <th>Sekolah</th>
                                <th>Bidang</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $item)
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
                                                <strong style="color: #667eea;">{{ $item->nama_lengkap }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $item->nim_nis }}</small>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($item->asal_sekolah, 30) }}</small>
                                        @if(!$item->instansi_id)
                                            <br>
                                            <button type="button" class="btn btn-xs btn-outline-warning mt-1" style="font-size: 10px; padding: 2px 6px;" onclick="openQuickAddModal({{ $item->id_sertifikat }}, '{{ addslashes($item->asal_sekolah) }}')">
                                                <i class="fas fa-plus-circle me-1"></i> Tambah ke Master Data
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $item->bidang }}</small>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $item->tgl_mulai->format('d M Y') }} - {{ $item->tgl_selesai->format('d M Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = 'bg-warning';
                                            $statusText = 'Aktif';
                                            if ($item->sts_diambil) {
                                                $statusClass = 'bg-success';
                                                $statusText = 'Selesai';
                                            } elseif ($item->tgl_selesai < now()) {
                                                $statusClass = 'bg-danger';
                                                $statusText = 'Expired';
                                            }
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if(!$item->instansi_id)
                                                <button type="button" class="btn btn-outline-warning" title="Tambah ke Master Data" onclick="openQuickAddModal({{ $item->id_sertifikat }}, '{{ addslashes($item->asal_sekolah) }}')">
                                                    <i class="fas fa-plus-circle"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('sekretariat.edit-siswa', $item->id_sertifikat) }}" class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('penilaian.create', ['id_sertifikat' => $item->id_sertifikat]) }}" class="btn btn-outline-success" title="Nilai">
                                                <i class="fas fa-star"></i> Nilai
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" title="Hapus" onclick="deleteSiswa({{ $item->id_sertifikat }})">
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
                    {{ $siswa->links() }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="mt-3">Belum ada data siswa magang</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Add Master Data Instansi Modal -->
<div class="modal fade" id="quickAddModal" tabindex="-1" aria-labelledby="quickAddModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                <h5 class="modal-title" id="quickAddModalLabel">
                    <i class="fas fa-university me-2"></i> Tambah ke Master Data
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sekretariat.instansi.quick-add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_sertifikat" id="modal_id_sertifikat">
                    
                    <div class="mb-3">
                        <label class="form-label fw-600 text-muted">Nama Sekolah / Universitas</label>
                        <input type="text" class="form-control bg-light" id="modal_nama_sekolah" readonly>
                        <div class="form-text text-info">
                            <i class="fas fa-info-circle me-1"></i> Nama instansi akan diambil langsung dari data input manual siswa.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_logo" class="form-label fw-600">Unggah Logo Resmi <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="logo" id="modal_logo" accept="image/*" required>
                        <div class="form-text">Format gambar: PNG, JPG, JPEG, SVG, WEBP. Maksimal 2MB.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" style="background: #667eea; border-color: #667eea;">
                        <i class="fas fa-save me-1"></i> Simpan & Hubungkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openQuickAddModal(idSertifikat, namaSekolah) {
        document.getElementById('modal_id_sertifikat').value = idSertifikat;
        document.getElementById('modal_nama_sekolah').value = namaSekolah;
        
        var myModal = new bootstrap.Modal(document.getElementById('quickAddModal'));
        myModal.show();
    }

    function deleteSiswa(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data siswa ini?')) {
            fetch(`/sekretariat/siswa/${id}`, {
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
                    // Redirect ke halaman daftar siswa setelah berhasil menghapus
                    window.location.href = "{{ route('sekretariat.data-siswa') }}";
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menghapus data siswa');
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
