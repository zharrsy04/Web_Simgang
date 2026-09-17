@extends('layouts.app')

@section('title', 'Lembaga Pendidikan')

@section('content')
<style>
    /* Reset container padding for full-width design */
    .container-fluid {
        max-width: 1240px;
    }

    /* Student list in modal */
    .student-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    
    .student-list li {
        padding: 0.75rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .student-list li:last-child {
        border-bottom: none;
    }
    
    .student-list .student-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .student-list .student-name {
        font-weight: 600;
        color: #4f46e5;
    }
    
    .student-list .student-details {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    /* Ensure delete form is clickable */
    .student-list form {
        position: relative;
        z-index: 10;
    }
    
    .student-list form button {
        pointer-events: auto !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0 text-white fw-700">Lembaga Pendidikan</h2>
            <p class="text-white-50 mb-0">Kelola daftar sekolah, universitas, serta data peserta magang di dalamnya</p>
        </div>
        <a href="{{ route('sekretariat.instansi.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; box-shadow: 0 4px 15px rgba(102,126,234,0.4);">
            <i class="fas fa-plus me-1"></i> Tambah Lembaga Pendidikan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('sekretariat.instansi.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari lembaga pendidikan..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">Cari</button>
                </div>
                @if($search)
                    <div class="col-md-2">
                        <a href="{{ route('sekretariat.instansi.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- KELOMPOK 1: LEMBAGA PENDIDIKAN TERDAFTAR --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-600 text-muted"><i class="fas fa-university me-2 text-primary"></i> Lembaga Pendidikan Terdaftar</h5>
        </div>
        <div class="card-body p-0">
            @if($instansi && count($instansi) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="80" class="text-center">No</th>
                                <th width="100" class="text-center">Logo</th>
                                <th>Nama Sekolah / Universitas</th>
                                <th>Jumlah Siswa</th>
                                <th width="220" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instansi as $index => $item)
                                <tr>
                                    <td class="text-center">{{ ($instansi->currentPage() - 1) * $instansi->perPage() + $index + 1 }}</td>
                                    <td class="text-center">
                                        @if($item->getLogoUrl())
                                            <img src="{{ $item->getLogoUrl() }}" alt="Logo" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #eee;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px; border: 1px solid #eee;">
                                                <i class="fas fa-university text-muted" style="font-size: 16px;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong style="color: #4f46e5; font-size: 1.05rem;">{{ $item->nama }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-primary border" style="font-size: 0.85rem; padding: 6px 12px;">
                                            <i class="fas fa-users me-1"></i> {{ $item->sertifikats->count() }} Peserta
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}" title="Detail Siswa">
                                                <i class="fas fa-list me-1"></i> Detail
                                            </button>
                                            <a href="{{ route('sekretariat.instansi.edit', $item->id) }}" class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" title="Hapus" onclick="deleteInstansi({{ $item->id }}, '{{ addslashes($item->nama) }}')">
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
                <div class="card-footer bg-white border-0 d-flex justify-content-center py-3">
                    {{ $instansi->appends(request()->input())->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-university fa-3x mb-3 text-light-muted"></i>
                    <p class="mb-0">Belum ada data lembaga pendidikan terdaftar</p>
                </div>
            @endif
        </div>
    </div>

    {{-- KELOMPOK 2: LEMBAGA PENDIDIKAN BELUM TERDAFTAR --}}
    @if(isset($unlinkedSchools) && count($unlinkedSchools) > 0)
        <div class="card shadow-sm border-0 mb-4 border-start border-warning border-3">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-600 text-warning"><i class="fas fa-exclamation-triangle me-2"></i> Lembaga Pendidikan Belum Terdaftar (Ditulis Manual oleh Siswa)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="80" class="text-center">No</th>
                                <th width="100" class="text-center">Logo</th>
                                <th>Nama Sekolah / Universitas</th>
                                <th>Jumlah Siswa</th>
                                <th width="300" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unlinkedSchools as $index => $school)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px; border: 1px solid #eee;">
                                            <i class="fas fa-graduation-cap text-muted" style="font-size: 16px;"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-muted" style="font-size: 1.05rem;">{{ $school->asal_sekolah }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-warning border" style="font-size: 0.85rem; padding: 6px 12px;">
                                            <i class="fas fa-users me-1"></i> {{ $school->jumlah_siswa }} Peserta
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#unlinkedDetailModal{{ $index }}" title="Detail Siswa">
                                                <i class="fas fa-list me-1"></i> Detail
                                            </button>
                                            <a href="{{ route('sekretariat.instansi.create', ['nama' => $school->asal_sekolah]) }}" class="btn btn-success" title="Daftarkan Lembaga & Upload Logo">
                                                <i class="fas fa-plus-circle me-1"></i> Daftarkan Instansi
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- MODALS DETAIL SISWA - TERDAFTAR --}}
@foreach($instansi as $item)
    <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailModalLabel{{ $item->id }}"><i class="fas fa-graduation-cap me-2"></i> Daftar Peserta dari {{ $item->nama }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                    @if(count($item->sertifikats) > 0)
                        <ul class="student-list">
                            @foreach($item->sertifikats as $siswa)
                                <li>
                                    <div class="student-info">
                                        <span class="student-name">{{ $siswa->nama_lengkap }}</span>
                                        <div class="d-flex gap-2">
                                            <span class="badge {{ $siswa->tgl_selesai >= now() ? 'bg-success' : 'bg-danger' }}">
                                                {{ $siswa->tgl_selesai >= now() ? 'Aktif' : 'Selesai' }}
                                            </span>
                                            <a href="{{ route('mentor.edit', ['id_sertifikat' => $siswa->id_sertifikat, 'from' => 'sekolah']) }}" class="btn btn-sm btn-primary py-0 px-2" style="font-size: 0.75rem;" title="Edit Siswa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('mentor.destroy', $siswa->id_sertifikat) }}" method="POST" class="d-inline delete-student-form" data-siswa-nama="{{ $siswa->nama_lengkap }}">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="from" value="sekolah">
                                                <button type="submit" class="btn btn-sm btn-danger py-0 px-2" style="font-size: 0.75rem;" title="Hapus Siswa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="student-details">
                                        <span>NIM/NIS: {{ $siswa->nim_nis }}</span> | 
                                        <span>Bidang: {{ $siswa->bidang }}</span> | 
                                        <span>Periode: {{ optional($siswa->tgl_mulai)->format('d-m-Y') }} s/d {{ optional($siswa->tgl_selesai)->format('d-m-Y') }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center text-muted my-3">Tidak ada data siswa magang aktif atau alumni dari lembaga ini</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- MODALS DETAIL SISWA - UNLINKED --}}
@if(isset($unlinkedSchools))
    @foreach($unlinkedSchools as $index => $school)
        <div class="modal fade" id="unlinkedDetailModal{{ $index }}" tabindex="-1" aria-labelledby="unlinkedDetailModalLabel{{ $index }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="unlinkedDetailModalLabel{{ $index }}"><i class="fas fa-graduation-cap me-2"></i> Daftar Peserta dari {{ $school->asal_sekolah }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                        @if(count($school->siswa) > 0)
                            <ul class="student-list">
                                @foreach($school->siswa as $siswa)
                                    <li>
                                        <div class="student-info">
                                            <span class="student-name">{{ $siswa->nama_lengkap }}</span>
                                            <div class="d-flex gap-2">
                                                <span class="badge {{ $siswa->tgl_selesai >= now() ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $siswa->tgl_selesai >= now() ? 'Aktif' : 'Selesai' }}
                                                </span>
                                                <a href="{{ route('mentor.edit', ['id_sertifikat' => $siswa->id_sertifikat, 'from' => 'sekolah']) }}" class="btn btn-sm btn-primary py-0 px-2" style="font-size: 0.75rem;" title="Edit Siswa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('mentor.destroy', $siswa->id_sertifikat) }}" method="POST" class="d-inline delete-student-form" data-siswa-nama="{{ $siswa->nama_lengkap }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="from" value="sekolah">
                                                    <button type="submit" class="btn btn-sm btn-danger py-0 px-2" style="font-size: 0.75rem;" title="Hapus Siswa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="student-details">
                                            <span>NIM/NIS: {{ $siswa->nim_nis }}</span> | 
                                            <span>Bidang: {{ $siswa->bidang }}</span> | 
                                            <span>Periode: {{ optional($siswa->tgl_mulai)->format('d-m-Y') }} s/d {{ optional($siswa->tgl_selesai)->format('d-m-Y') }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-center text-muted my-3">Tidak ada data siswa magang aktif atau alumni dari lembaga ini</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

<script>
    // Delete Instansi confirmation (SweetAlert)
    function deleteInstansi(id, name) {
        Swal.fire({
            title: 'Hapus Lembaga Pendidikan?',
            text: `Apakah Anda yakin ingin menghapus "${name}"? Siswa yang terhubung akan kehilangan link logo.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'simgang-confirm-popup',
                title: 'simgang-confirm-title',
                htmlContainer: 'simgang-confirm-text'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/sekretariat/instansi/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessToast(data.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showErrorToast(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorToast('Gagal menghapus data instansi');
                });
            }
        });
    }

    // Delete Student inside Modal confirmation
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-student-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const namaSiswa = form.dataset.siswaNama || 'siswa ini';
                
                // Hide active modal temporarily to let showConfirm be visible properly
                const activeModalEl = form.closest('.modal');
                let activeModal = null;
                if (activeModalEl) {
                    activeModal = bootstrap.Modal.getInstance(activeModalEl);
                    if (activeModal) {
                        activeModal.hide();
                    }
                }

                showConfirm(
                    'Data "' + namaSiswa + '" akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.',
                    function() {
                        const btn = form.querySelector('button[type=submit]');
                        if (btn) { 
                            btn.disabled = true; 
                            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; 
                        }
                        form.submit();
                    },
                    'Hapus Data Siswa',
                    'Ya, Hapus',
                    'Batal'
                );
            });
        });
    });
</script>
@endsection
