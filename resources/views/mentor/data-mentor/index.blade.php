@extends('layouts.app')

@section('title', 'Data Mentor')

@section('content')
<style>
    .action {
        display: flex;
        gap: 0.35rem;
        justify-content: center;
    }

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

    .badge-bidang {
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
    }

    .badge-sekretariat {
        background-color: #8b5cf6;
        color: white;
    }

    .badge-informatika {
        background-color: #3b82f6;
        color: white;
    }

    .badge-statistik {
        background-color: #10b981;
        color: white;
    }

    /* Update card styling */
    .card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: none;
        margin-bottom: 2rem;
    }

    .card-header {
        background-color: #ffffff;
        color: #1f2937;
        font-weight: 600;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .card-header h4 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 600;
    }

    /* Buttons */
    .btn-action {
        border-radius: 6px;
        padding: 0.35rem 0.6rem;
        color: white;
    }
    .btn-edit { background-color: #2563eb; border-color: #2563eb; }
    .btn-delete { background-color: #dc2626; border-color: #dc2626; }
</style>

<div class="container mt-4">
    <div class="dashboard-header" style="color: white;">
        <h1 style="margin: 0;">Data Mentor</h1>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>Daftar Mentor</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <a href="{{ route('mentor.data-mentor.create') }}" class="btn btn-success" style="background-color: #198754; border-color: #198754; padding: 0.5rem 1rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                    <i class="fas fa-plus me-1"></i> Tambah Mentor
                </a>
                <form action="{{ route('mentor.data-mentor.index') }}" method="GET" class="d-flex gap-2 align-items-center ms-auto">
                    <select name="bidang" class="form-select" style="width: 200px; border-radius: 6px;">
                        <option value="all" {{ $bidangFilter === 'all' ? 'selected' : '' }}>Semua Bidang</option>
                        @foreach($bidangList as $bidang)
                            <option value="{{ $bidang }}" {{ $bidangFilter === $bidang ? 'selected' : '' }}>{{ $bidang }}</option>
                        @endforeach
                    </select>
                    <div class="input-group" style="width: 250px;">
                        <span class="input-group-text bg-white" style="border-right: none;"><i class="fa fa-search text-muted"></i></span>
                        <input name="search" type="search" class="form-control" style="border-left: none;" placeholder="Cari nama/username" value="{{ $search }}">
                    </div>
                    <button type="submit" class="btn btn-primary" style="background-color: #2563eb; border-color: #2563eb; border-radius: 6px;">
                        <i class="fa fa-filter me-1"></i> Filter
                    </button>
                </form>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center text-uppercase">NO</th>
                            <th class="text-nowrap text-center text-uppercase">NAMA</th>
                            <th class="text-nowrap text-center text-uppercase">USERNAME</th>
                            <th class="text-nowrap text-center text-uppercase">EMAIL</th>
                            <th class="text-nowrap text-center text-uppercase">BIDANG</th>
                            <th class="text-nowrap text-center text-uppercase">SISWA BIMBINGAN</th>
                            <th class="text-nowrap text-center text-uppercase">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($mentors->count() > 0)
                            @foreach($mentors as $index => $mentor)
                                <tr style="border-bottom: 1px solid #f1f5f9; background-color: #ffffff;">
                                    <td class="text-center py-3" style="color: #334155;">{{ ($mentors->currentPage() - 1) * $mentors->perPage() + $loop->iteration }}</td>
                                    <td class="text-center py-3" style="color: #334155;"></td>
                                    <td class="text-center py-3" style="color: #334155;">{{ $mentor->username }}</td>
                                    <td class="text-center py-3" style="color: #334155;"></td>
                                    <td class="text-center py-3" style="color: #334155;"></td>
                                    <td class="text-center py-3" style="color: #334155;">
                                        {{ $mentor->mentorSiswa()->where('is_active', true)->count() }} siswa
                                    </td>
                                    <td class="text-center py-3">
                                        <div class="action gap-2">
                                            <a href="{{ route('mentor.data-mentor.edit', $mentor->id) }}" class="btn btn-action btn-edit shadow-none" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('mentor.data-mentor.destroy', $mentor->id) }}" method="POST" class="d-inline delete-form"
                                                  data-mentor-nama="{{ $mentor->nama }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-action btn-delete shadow-none" title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada data mentor</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{ $mentors->links() }}
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// ===== SIMGANG: Konfirmasi Hapus Mentor =====
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const namaMentor = form.dataset.mentorNama || 'mentor ini';
            showConfirm(
                'Akun mentor "' + namaMentor + '" akan dihapus. Pastikan tidak ada siswa bimbingan aktif.',
                function() {
                    const btn = form.querySelector('button[type=submit]');
                    if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; }
                    form.submit();
                },
                'Hapus Akun Mentor',
                'Ya, Hapus',
                'Batal'
            );
        });
    });
});
</script>
@endsection
