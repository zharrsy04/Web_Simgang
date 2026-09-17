@extends('layouts.app')

@section('title', 'Daftar Pengguna')

@section('content')
<style>
    /* Reset container padding for full-width design */
    .container {
        padding-right: 0;
        padding-left: 0;
        max-width: 1240px;
    }

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

    /* Role badge styling */
    .role-badge {
        display: inline-block;
        padding: 0.35rem 0.65rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 2rem;
        letter-spacing: 0.3px;
    }
    
    .role-badge.admin {
        background-color: #e0f2fe;
        color: #0369a1;
    }
    
    .role-badge.mentor {
        background-color: #dcfce7;
        color: #15803d;
    }
    
    .role-badge.siswa {
        background-color: #fef3c7;
        color: #a16207;
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
    
    .btn-success { 
        background-color: #10b981;
        border-color: #10b981;
    }
    
    .btn-danger { 
        background-color: #ef4444;
        border-color: #ef4444;
    }

    /* Search form */
    .search-form {
        background-color: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        max-width: 300px;
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
        padding: 0.75rem;
        transition: all 0.2s ease;
    }

    .search-form button[type="submit"] {
        transition: all 0.2s ease;
        border: none;
        background-color: #4f46e5;
        color: white;
    }

    .search-form button[type="submit"]:hover {
        background-color: #4338ca;
    }

    .search-form button[type="submit"]:active {
        transform: scale(0.95);
    }

    /* Alert styling */
    .alert {
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: none;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .alert i {
        font-size: 1.25rem;
    }
    
    /* Empty state styling */
    .empty-state {
        padding: 3rem 0;
        text-align: center;
        color: #94a3b8;
    }
    
    .empty-state i {
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    
    /* Pagination styling */
    .pagination {
        justify-content: center;
        margin-top: 1.5rem;
    }

    .page-item {
        margin: 0 0.2rem;
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
</style>

<div class="container mt-4">
    <div class="dashboard-header" style="color: white;">
        <h1 style="margin: 0;">Manajemen Pengguna Sistem</h1>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>Daftar Pengguna</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('user.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-user-plus"></i> Tambah Pengguna
                </a>
                <form action="{{ route('user.index') }}" method="GET" class="search-form" id="userSearchForm">
                    <div class="input-group">
                        <input name="search" type="text" class="form-control" id="userSearchInput" placeholder="Cari Pengguna" value="{{ request('search') }}" autocomplete="off">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success mb-4" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>USERNAME</th>
                            <th class="text-center">ROLE</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="fw-medium">{{ $user->username }}</td>
                                <td class="text-center">
                                    <span class="role-badge {{ strtolower($user->role) }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action">
                                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline delete-form" data-username="{{ $user->username }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <p>Belum ada data pengguna tersedia</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($users, 'links'))
                <div class="mt-4">
                    {{ $users->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Auto submit form saat input berubah atau enter ditekan
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearchInput');
    const searchForm = document.getElementById('userSearchForm');
    
    if (searchInput && searchForm) {
        // Submit form saat enter ditekan
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchForm.submit();
            }
        });
        
        // Auto submit ketika input dikosongkan (backspace/delete semua text)
        let typingTimer;
        const doneTypingInterval = 500; // 500ms delay after user stops typing
        
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            
            // Jika input kosong, langsung submit untuk reset
            if (this.value.trim() === '') {
                searchForm.submit();
            } else {
                // Jika masih ada isi, tunggu user selesai ngetik baru submit
                typingTimer = setTimeout(function() {
                    searchForm.submit();
                }, doneTypingInterval);
            }
        });
    }
    
    // ===== SIMGANG: Konfirmasi Hapus Pengguna =====
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const username = form.dataset.username || 'pengguna ini';
            showConfirm(
                'Akun pengguna "' + username + '" akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.',
                function() {
                    const btn = form.querySelector('button[type=submit]');
                    if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghapus...'; }
                    form.submit();
                },
                'Hapus Pengguna',
                'Ya, Hapus',
                'Batal'
            );
        });
    });
});
</script>
@endsection