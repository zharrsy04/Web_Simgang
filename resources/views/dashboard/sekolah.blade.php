@extends('layouts.app')

@section('title', 'Data Sekolah / Universitas')

@section('content')
<style>
    /* Reset container padding for full-width design */
    .container {
        padding-right: 0;
        padding-left: 0;
        max-width: 1240px;
    }

    /* Modern dashboard header */
    .dashboard-header {
        font-size: 1.8rem; /* Sesuaikan ukuran font */
        font-weight: 700; /* Lebih tebal */
        color: #ffffff; /* Warna teks lebih gelap */
        margin-bottom: 2rem; /* Tambah margin bawah */
        text-align: left; /* Align ke kiri sesuai gambar */
        margin-top: 2rem;
        letter-spacing: -0.5px; /* Sedikit rapatkan huruf */
    }
    
    /* Action buttons container */
    .action {
        display: flex;
        gap: 0.35rem;
        justify-content: center;
    }

    /* Table styling */
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 1rem;
        background-color: transparent;
        box-shadow: none;
    }

    .table-sm {
        font-size: 0.9rem;
    }

    .table-sm th,
    .table-sm td {
        padding: 0.75rem;
        vertical-align: middle;
        border: none;
        background-color: #fff;
    }

    .table-sm th {
        font-weight: 500;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    .table-sm tr {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }

    .table-sm tr:hover {
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .table-sm thead tr {
        background-color: #f3f4f6;
        box-shadow: none;
    }

    .table-sm thead th {
        background-color: #f3f4f6;
        color: #4b5563;
        border-bottom: 1px solid #e5e7eb;
        text-align: center;
    }

    /* Responsive table */
    @media (max-width: 768px) {
        table.table-sm {
            font-size: 0.8rem;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.5rem;
        }

        .btn-sm {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        .action {
            gap: 0.25rem;
        }

        .pagination-sm .page-link {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        .pagination-sm .page-item {
            margin: 0 1px;
        }
    }

    /* Card styling */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
        color: white;
        font-weight: 600;
        text-align: center;
        padding: 1.25rem;
        border-bottom: none;
        letter-spacing: 0.5px;
    }

    .card-header h4 {
        margin: 0;
        font-size: 1.25rem;
    }

    .card-body {
        padding: 1.5rem;
        background-color: #fff;
    }

    /* Text styling */
    .text-muted {
        color: #6b7280;
    }

    .text-nowrap {
        white-space: nowrap;
    }

    /* Button styling */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.5rem 0.75rem;
    }

    .btn-sm {
        padding: 0.35rem 0.65rem;
        font-size: 0.8rem;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn i {
        font-size: 0.8rem;
    }

    .btn-primary { 
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    
    .btn-warning { 
        background-color: #f59e0b;
        border-color: #f59e0b;
        color: white;
    }
    
    .btn-success { 
        background-color: #10b981;
        border-color: #10b981;
    }
    
    .btn-danger { 
        background-color: #ef4444;
        border-color: #ef4444;
    }

    .btn-info { 
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    .btn-secondary { 
        background-color: #6b7280;
        border-color: #6b7280;
    }

    /* Search form */
    .search-form {
        background-color: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        max-width: 320px;
    }

    .search-form:focus-within {
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .search-form input {
        border: none;
        box-shadow: none;
        padding: 0.75rem 1rem;
    }

    .search-form .input-group-text {
        background-color: transparent;
        border: none;
        color: #6b7280;
    }

    /* Badge styling */
    .badge {
        font-weight: 500;
        padding: 0.35rem 0.65rem;
        border-radius: 6px;
        font-size: 0.7rem;
        letter-spacing: 0.3px;
    }

    .bg-success {
        background-color: #d1fae5 !important;
        color: #065f46 !important;
    }

    .bg-danger {
        background-color: #fee2e2 !important;
        color: #b91c1c !important;
    }

    /* Modal styling */
    .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
        color: white;
        border-bottom: none;
        padding: 1.25rem;
    }

    .modal-header .btn-close {
        color: white;
        opacity: 1;
    }

    .modal-title {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1rem;
    }

    /* Form controls */
    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.65rem 1rem;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-label {
        font-weight: 500;
        color: #4b5563;
        margin-bottom: 0.5rem;
    }

    /* Pagination styling */
    .pagination {
        justify-content: center;
        margin-top: 1.5rem;
    }

    .page-item {
        margin:0.2rem;
    }

    .page-link {
        border: none;
        border-radius: 8px;
        color: #4f46e5;
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .page-link:hover {
        background-color: #eff6ff;
        color: #4338ca;
    }

    .page-item.active .page-link {
        background-color: #4f46e5;
        color: white;
    }

    /* Modal inner cards */
    .modal .card {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 1rem;
    }

    .modal .card-header {
        background: #f3f4f6;
        color: #4b5563;
        font-size: 0.9rem;
        padding: 0.75rem 1rem;
    }

    .modal .card-header h5 {
        margin: 0;
        font-weight: 600;
    }

    /* Alert styling */
    .alert {
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: none;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
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
    }
    
    .student-list .student-name {
        font-weight: 500;
    }
    
    .student-list .student-details {
        font-size: 0.85rem;
        color: #6b7280;
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


<h3 class="dashboard-header">Data Sekolah / Universitas</h3>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Data Sekolah / Universitas</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <a href="{{ route('mentor.create', ['from' => 'sekolah']) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
                <form action="{{ route('dashboard.sekolah') }}" method="GET" class="search-form ms-auto">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input name="search" type="search" class="form-control" placeholder="Cari Data..." value="{{ request('search') }}">
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table align-middle" style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr style="background-color: #f8f9fa; border-bottom: 1px solid #edf2f7;">
                            <th class="text-center text-muted py-3" style="width: 70px; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px;">NO</th>
                            <th class="text-center text-muted py-3" style="width: 80px; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px;">LOGO</th>
                            <th class="text-center text-muted py-3" style="font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px;">SEKOLAH / UNIVERSITAS</th>
                            <th class="text-center text-muted py-3" style="width: 170px; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px;">JUMLAH SISWA</th>
                            <th class="text-center text-muted py-3" style="width: 130px; font-size: 0.8rem; font-weight: 600; letter-spacing: 0.5px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sekolahList as $index => $sekolah)
                        <tr style="border-bottom: 1px solid #f1f5f9; background-color: #ffffff;">
                            <td class="text-center py-3" style="color: #334155; font-weight: 500; font-size: 0.95rem;">
                                {{ $loop->iteration + $sekolahList->firstItem() - 1 }}
                            </td>
                            <td class="text-center py-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-none" style="width: 42px; height: 42px; background-color: #f8fafc; border: 1px solid #f1f5f9;">
                                    <i class="fas fa-university" style="font-size: 1.15rem; color: #475569;"></i>
                                </div>
                            </td>
                            <td class="py-3 ps-3">
                                <span style="color: #4338ca; font-size: 0.95rem; font-weight: 700; display: inline-block;">
                                    {{ $sekolah->asal_sekolah }}
                                </span>
                            </td>
                            <td class="text-center py-3">
                                <span class="badge" style="background-color: #ffffff; color: #2563eb; border: 1px solid #e2e8f0; font-size: 0.85rem; font-weight: 600; padding: 7px 16px; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
                                    <i class="fas fa-users" style="color: #2563eb; font-size: 0.95rem;"></i> {{ $sekolah->jumlah_siswa }} Peserta
                                </span>
                            </td>
                            <td class="text-center py-3">
                                <button type="button" class="btn btn-primary btn-sm px-3 shadow-none" data-bs-toggle="modal" data-bs-target="#detailModal{{ $index }}" title="Detail" style="background-color: #2b7fff; border-color: #2b7fff; border-radius: 6px; font-weight: 500; font-size: 0.85rem; padding: 6px 16px; display: inline-flex; align-items: center; gap: 6px; color: #ffffff;">
                                    <i class="fas fa-list-ul"></i> Detail
                                </button>
                            </td>
                        </tr>

                        <!-- Modal untuk detail siswa di sekolah -->
                        <div class="modal fade" id="detailModal{{ $index }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $index }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel{{ $index }}">Daftar Siswa dari {{ $sekolah->asal_sekolah }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                        @if(count($sekolah->siswa) > 0)
                                            <ul class="student-list">
                                                @foreach($sekolah->siswa as $siswa)
                                                <li>
                                                    <div class="student-info">
                                                        <span class="student-name">{{ $siswa->nama_lengkap }}</span>
                                                        <div class="d-flex gap-2">
                                                            <span class="badge {{ $siswa->tgl_selesai >= now() ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $siswa->tgl_selesai >= now() ? 'Aktif' : 'Selesai' }}
                                                            </span>
                                                            <a href="{{ route('mentor.edit', ['id_sertifikat' => $siswa->id_sertifikat, 'from' => 'sekolah']) }}" class="btn btn-sm btn-primary py-0 px-2" style="font-size: 0.75rem;" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('mentor.destroy', $siswa->id_sertifikat) }}" method="POST" class="d-inline delete-form" data-siswa-nama="{{ $siswa->nama_lengkap }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="from" value="sekolah">
                                                                <button type="submit" class="btn btn-sm btn-danger py-0 px-2" style="font-size: 0.75rem;" title="Hapus">
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
                                            <p class="text-center text-muted">Tidak ada data siswa</p>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-school fa-2x mb-2 d-block text-secondary opacity-50"></i>
                                Tidak ada data sekolah / universitas ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                {{-- Pagination Navigation --}}
                <div class="d-flex justify-content-center align-items-center mt-4 pt-2">
                    <div class="pagination-container">
                        {{ $sekolahList->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// ===== SIMGANG: Konfirmasi Hapus Siswa (Data Sekolah / Universitas) =====
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const namaSiswa = form.dataset.siswaNama || 'siswa ini';
            showConfirm(
                'Data "' + namaSiswa + '" akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.',
                function() {
                    const btn = form.querySelector('button[type=submit]');
                    if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; }
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

@endsection