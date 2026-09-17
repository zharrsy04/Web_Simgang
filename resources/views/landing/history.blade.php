@extends('layouts.landing')

@section('title', 'Riwayat Magang')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;600;700&display=swap');

    .history-header {
        position: relative;
        background: linear-gradient(135deg, rgba(90, 96, 255, 0.9), rgba(138, 86, 242, 0.9));
        color: white;
        border-radius: 20px;
        padding: 60px 40px;
        margin-bottom: 40px;
        overflow: hidden;
    }

    .history-header::before {
        content: "";
        position: absolute;
        inset: 0;
        background: url('https://svgshare.com/i/14bA.svg') repeat;
        opacity: 0.05;
    }

    .history-header h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }

    .history-header p {
        font-size: 1.1rem;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }

    .stat-card {
        border-radius: 15px;
        background: white;
        padding: 25px;
        text-align: center;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
        border-top: 4px solid #5a60ff;
        margin-bottom: 20px;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(90, 96, 255, 0.2);
    }

    .stat-card .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #5a60ff;
        margin: 10px 0;
    }

    .stat-card .stat-label {
        color: #666;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .filter-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .filter-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        align-items: flex-end;
    }

    .filter-form > .action-buttons {
        grid-column: 1 / -1;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #555;
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #5a60ff;
        box-shadow: 0 0 0 3px rgba(90, 96, 255, 0.1);
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .btn-filter, .btn-reset, .btn-export, .btn-download, .btn-create {
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        white-space: nowrap;
        text-decoration: none;
    }

    .btn-filter {
        background: linear-gradient(135deg, #5a60ff, #8a56f2);
        color: white;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(90, 96, 255, 0.3);
        color: white;
    }

    .btn-reset {
        background: #f0f0f0;
        color: #333;
    }

    .btn-reset:hover {
        background: #e0e0e0;
        color: #333;
    }

    .btn-export {
        background: #43e97b;
        color: white;
    }

    .btn-export:hover {
        background: #38d47a;
        transform: translateY(-2px);
        color: white;
    }

    .btn-download {
        background: #ff6b9d;
        color: white;
    }

    .btn-download:hover {
        background: #ff5a8a;
        transform: translateY(-2px);
        color: white;
    }

    .btn-create {
        background: #00c6ff;
        color: white;
    }

    .btn-create:hover {
        background: #00b5e8;
        transform: translateY(-2px);
        color: white;
    }

    .table-container {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .table {
        margin: 0;
        font-size: 0.95rem;
    }

    .table thead {
        background: linear-gradient(135deg, #5a60ff, #8a56f2);
        color: white;
    }

    .table thead th {
        border: none;
        padding: 16px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9ff;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .badge-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: #d4f4dd;
        color: #2d7a4a;
    }

    .peserta-name {
        font-weight: 600;
        color: #333;
    }

    .periode-text {
        color: #666;
        font-size: 0.9rem;
    }

    .action-cell {
        display: flex;
        gap: 6px;
        justify-content: center;
    }

    .btn-sm {
        padding: 6px 10px;
        font-size: 0.85rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }

    .btn-edit {
        background: #4ecdc4;
        color: white;
    }

    .btn-edit:hover {
        background: #45b8b8;
        transform: scale(1.05);
        color: white;
    }

    .btn-delete {
        background: #ff6b6b;
        color: white;
    }

    .btn-delete:hover {
        background: #ff5555;
        transform: scale(1.05);
        color: white;
    }

    .pagination {
        justify-content: center;
        gap: 5px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .pagination .page-link {
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        color: #5a60ff;
        padding: 8px 12px;
        background: white;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        border-color: #5a60ff;
        background-color: #f8f9ff;
        color: #5a60ff;
    }

    .pagination .page-item.active .page-link {
        background-color: #5a60ff;
        border-color: #5a60ff;
        color: white;
        font-weight: 600;
    }

    .no-data {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .no-data i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }

    .no-data p {
        font-size: 1.1rem;
        color: #666;
    }

    .alert {
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
        border: none;
    }

    .alert-success {
        background: #d4f4dd;
        color: #2d7a4a;
    }

    .alert-error {
        background: #fdd4d4;
        color: #7a2d2d;
    }

    @media (max-width: 768px) {
        .history-header h1 {
            font-size: 1.8rem;
        }

        .history-header p {
            font-size: 0.95rem;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
            width: 100%;
        }

        .action-buttons > * {
            width: 100%;
            justify-content: center;
        }

        .stat-card {
            margin-bottom: 15px;
        }

        .table {
            font-size: 0.85rem;
        }

        .table thead th,
        .table tbody td {
            padding: 10px 8px;
        }

        .action-cell {
            flex-direction: column;
        }

        .btn-sm {
            width: 100%;
            justify-content: center;
        }
    }
</style>


@if ($errors->any())
    <div class="alert alert-error">
        <strong>Error:</strong>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-error">
        <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
    </div>
@endif

<section class="history-header">
    <h1><i class="fas fa-history me-2"></i> Riwayat Magang</h1>
    <p>Daftar lengkap peserta magang yang telah dan sedang menjalankan program magang di Diskominfo Cilacap</p>
</section>

<div class="row mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <i class="fas fa-users" style="font-size: 2rem; color: #5a60ff;"></i>
            <div class="stat-number">{{ $totalPeserta }}</div>
            <div class="stat-label">Total Peserta</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <i class="fas fa-check-circle" style="font-size: 2rem; color: #43e97b;"></i>
            <div class="stat-number">{{ $pesertaSelesai }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <i class="fas fa-spinner" style="font-size: 2rem; color: #ffa500;"></i>
            <div class="stat-number">{{ $pesertaAktif }}</div>
            <div class="stat-label">Sedang Berjalan</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <i class="fas fa-school" style="font-size: 2rem; color: #00c6ff;"></i>
            <div class="stat-number">{{ $lembagaUnik }}</div>
            <div class="stat-label">Lembaga Pendidikan</div>
        </div>
    </div>
</div>


<div class="filter-section" id="filter-section">
    <div class="filter-title">
        <i class="fas fa-filter"></i> Filter Data
    </div>
    <form method="GET" action="{{ route('history') }}#filter-section" class="filter-form">
        <div class="form-group">
            <label for="search">Cari Nama</label>
            <input type="text" id="search" name="search" placeholder="Masukkan nama peserta" value="{{ request('search') }}">
        </div>

        <div class="form-group">
            <label for="bidang">Bidang</label>
            <select id="bidang" name="bidang">
                <option value="">-- Semua Bidang --</option>
                @foreach ($bidangList as $bid)
                    <option value="{{ $bid }}" @selected(request('bidang') === $bid)>{{ $bid }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="sekolah">Asal Sekolah/PT</label>
            <input type="text" id="sekolah" name="sekolah" placeholder="Cari sekolah..." value="{{ request('sekolah') }}">
        </div>

        <div class="action-buttons">
            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Cari
            </button>
            <a href="{{ route('history') }}#filter-section" class="btn-reset">
                <i class="fas fa-redo"></i> Reset
            </a>
            <a href="{{ route('history.export', request()->query()) }}" class="btn-export">
                <i class="fas fa-download"></i> Export
            </a>
            <a href="{{ route('certificate.download-form') }}" class="btn-download">
                <i class="fas fa-certificate"></i> Download Sertifikat
            </a>
            @auth
                @if (Auth::user()->role === 'mentor')
                    <a href="{{ route('history.create') }}" class="btn-create">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                @endif
            @endauth
        </div>
    </form>
</div>


<div class="table-container">
    @if ($peserta->count() > 0)
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Peserta</th>
                    <th style="width: 25%;">Asal Sekolah/PT</th>
                    <th style="width: 15%;">Bidang</th>
                    <th style="width: 20%;">Periode Magang</th>
                    <th style="width: 10%; text-align: center;">Status</th>
                    @auth
                        @if (Auth::user()->role === 'mentor')
                            <th style="width: 8%; text-align: center;">Aksi</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach ($peserta as $index => $item)
                    <tr>
                        <td>{{ ($peserta->currentPage() - 1) * $peserta->perPage() + $loop->iteration }}</td>
                        <td><div class="peserta-name">{{ $item->nama_lengkap }}</div></td>
                        <td>{{ $item->asal_sekolah }}</td>
                        <td>
                            <span style="display: inline-block; background: #f0f0f0; padding: 6px 12px; border-radius: 6px; font-weight: 500;">
                                {{ $item->bidang }}
                            </span>
                        </td>
                        <td>
                            <div class="periode-text">
                                <i class="fas fa-calendar-alt me-1" style="color: #5a60ff;"></i>
                                {{ $item->tgl_mulai->format('d/m/Y') }} - {{ $item->tgl_selesai->format('d/m/Y') }}
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge-status">
                                <i class="fas fa-check-circle me-1"></i> Selesai
                            </span>
                        </td>
                        @auth
                            @if (Auth::user()->role === 'mentor')
                                <td style="text-align: center;">
                                    <div class="action-cell">
                                        <a href="{{ route('history.edit', $item->id_sertifikat) }}" class="btn-sm btn-edit" title="Edit Data">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('history.destroy', $item->id_sertifikat) }}" method="POST" style="display: inline;" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm btn-delete" title="Hapus Data">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        @endauth
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4 mb-3">
            {{ $peserta->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="no-data">
            <i class="fas fa-inbox"></i>
            <p>Tidak ada data peserta magang yang sesuai dengan filter Anda</p>
            <small style="color: #bbb;">Coba ubah filter atau lakukan pencarian dengan kriteria lain</small>
        </div>
    @endif
</div>

<script>
// Handle delete forms with SweetAlert2 confirmation
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            showConfirm('Yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.', function() {
                form.submit();
            });
        });
    });
});
</script>

@endsection
