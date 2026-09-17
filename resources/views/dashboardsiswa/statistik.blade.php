@extends('layouts.app')

@section('title', 'Statistik Magang')

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
        font-size: 1.8rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 2rem;
        text-align: left;
        margin-top: 2rem;
        letter-spacing: -0.5px;
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

    /* Modal styling - IMPROVED */
    .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom: none;
        padding: 1.5rem 2rem;
        position: relative;
    }

    .modal-header::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #ff6b6b, #feca57, #48dbfb, #ff9ff3);
    }

    .modal-header .btn-close {
        color: white;
        opacity: 1;
        font-size: 1.2rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        padding: 0.5rem;
        transition: all 0.2s ease;
    }

    .modal-header .btn-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    .modal-title {
        font-weight: 700;
        letter-spacing: 0.5px;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-title::before {
        content: '👥';
        font-size: 1.5rem;
    }

    .modal-body {
        padding: 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .modal-footer {
        border-top: none;
        padding: 1.5rem 2rem;
        background-color: #f8fafc;
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
        margin: 0.2rem;
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
    
    /* IMPROVED Student list in modal */
    .student-list {
        list-style-type: none;
        padding: 1.5rem;
        margin: 0;
        max-height: 500px;
        overflow-y: auto;
    }

    .student-list::-webkit-scrollbar {
        width: 6px;
    }

    .student-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .student-list::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 10px;
    }
    
    .student-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .student-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #ff6b6b, #feca57, #48dbfb, #ff9ff3);
    }

    .student-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        border-left-color: #667eea;
    }

    .student-card.active {
        border-left-color: #10b981;
    }

    .student-card.inactive {
        border-left-color: #ef4444;
    }
    
    .student-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .student-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .student-name::before {
        content: '🎓';
        font-size: 1.2rem;
    }

    .student-status {
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .student-status.active {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .student-status.active::before {
        content: '✅';
    }

    .student-status.inactive {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #b91c1c;
    }

    .student-status.inactive::before {
        content: '⏰';
    }
    
    .student-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.8rem;
        margin-top: 1rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #4b5563;
    }

    .detail-item .icon {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        color: white;
        flex-shrink: 0;
    }

    .detail-item:nth-child(1) .icon {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .detail-item:nth-child(2) .icon {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }

    .detail-item:nth-child(3) .icon {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .detail-label {
        font-weight: 600;
        color: #374151;
    }

    .detail-value {
        color: #6b7280;
    }

    /* Empty state styling */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state-text {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .empty-state-subtext {
        font-size: 0.9rem;
        opacity: 0.7;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 1rem;
        }

        .modal-title {
            font-size: 1.1rem;
        }

        .student-details {
            grid-template-columns: 1fr;
        }

        .student-header {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }

        .student-name {
            font-size: 1rem;
        }
    }
</style>

<h3 class="dashboard-header">Data Statistik Bidang</h3>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Data Statistik Bidang</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div></div>
                {{-- Mengubah action form search agar mengarah ke route statistikMagang --}}
                <form action="{{ route('dashboardsiswa.statistik.magang') }}" method="GET" class="search-form ms-auto">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input name="search" type="search" class="form-control" placeholder="Cari Data Bidang" value="{{ request('search') }}">
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th class="text-nowrap">No</th>
                            <th class="text-nowrap">Bidang</th>
                            <th class="text-nowrap">Jumlah Siswa</th>
                            <th class="text-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- ITERASI YANG BENAR:
                             $namaBidang akan berisi nama bidang (contoh: "Desain Grafis")
                             $listSiswa akan berisi koleksi siswa (Collection) untuk bidang tersebut --}}
                        @foreach ($data as $namaBidang => $listSiswa)
                            <tr>
                                <td class="text-center">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                {{-- Mengakses nama bidang dari kunci iterasi --}}
                                <td>{{ $namaBidang }}</td>
                                {{-- Mengakses jumlah siswa dengan menghitung item di dalam koleksi $listSiswa --}}
                                <td class="text-center">{{ $listSiswa->count() }}</td>
                                <td>
                                    <div class="action">
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $loop->index }}" title="Detail">
                                            <i class="fa fa-list"></i> Detail
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal detail siswa - IMPROVED -->
                            <div class="modal fade" id="detailModal{{ $loop->index }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $loop->index }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            {{-- Mengubah judul modal agar sesuai dengan nama bidang --}}
                                            <h5 class="modal-title" id="detailModalLabel{{ $loop->index }}">Daftar Siswa dari Bidang {{ $namaBidang }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                            {{-- Memeriksa apakah ada siswa dalam koleksi $listSiswa --}}
                                            @if($listSiswa->count() > 0)
                                                <ul class="student-list">
                                                    {{-- Iterasi melalui setiap siswa dalam koleksi $listSiswa --}}
                                                    @foreach($listSiswa as $siswa)
                                                    <li class="student-card {{ \Carbon\Carbon::parse($siswa->tgl_selesai)->gte(now()) ? 'active' : 'inactive' }}">
                                                        <div class="student-header">
                                                            <h6 class="student-name">{{ $siswa->nama_lengkap }}</h6>
                                                            <span class="student-status {{ \Carbon\Carbon::parse($siswa->tgl_selesai)->gte(now()) ? 'active' : 'inactive' }}">
                                                                {{ \Carbon\Carbon::parse($siswa->tgl_selesai)->gte(now()) ? 'Aktif' : 'Selesai' }}
                                                            </span>
                                                        </div>
                                                        <div class="student-details">
                                                            <div class="detail-item">
                                                                <span class="icon">📋</span>
                                                                <span><span class="detail-label">NIM/NIS:</span> <span class="detail-value">{{ $siswa->nim_nis }}</span></span>
                                                            </div>
                                                            <div class="detail-item">
                                                                <span class="icon">🎯</span>
                                                                <span><span class="detail-label">Bidang:</span> <span class="detail-value">{{ $siswa->bidang }}</span></span>
                                                            </div>
                                                            <div class="detail-item">
                                                                <span class="icon">🏫</span>
                                                                <span><span class="detail-label">Asal Sekolah:</span> <span class="detail-value">{{ $siswa->asal_sekolah }}</span></span>
                                                            </div>
                                                            <div class="detail-item">
                                                                <span class="icon">📅</span>
                                                                <span><span class="detail-label">Periode:</span> <span class="detail-value">{{ optional($siswa->tgl_mulai)->format('d-m-Y') }} s/d {{ optional($siswa->tgl_selesai)->format('d-m-Y') }}</span></span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <div class="empty-state">
                                                    <div class="empty-state-icon">📚</div>
                                                    <p class="empty-state-text">Tidak ada data siswa</p>
                                                    <p class="empty-state-subtext">Belum ada siswa yang terdaftar di bidang ini</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fa fa-times"></i> Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-container">
                    {{ $data->onEachSide(1)->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection