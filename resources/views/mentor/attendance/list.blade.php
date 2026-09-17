@extends('layouts.app')
@section('title', 'Absensi')

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
        flex-wrap: wrap;
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
        text-decoration: none;
        position: relative;
        overflow: hidden;
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

    .btn-warning {
        background-color: #f59e0b;
        border-color: #f59e0b;
        color: white;
    }

    .btn-info {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    /* Loading state for buttons */
    .btn-loading {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
        position: relative;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .alert-error {
        background-color: #fee2e2;
        color: #b91c1c;
    }

    .alert-warning {
        background-color: #fef3c7;
        color: #92400e;
    }

    .alert i {
        font-size: 1.25rem;
    }

    .alert-dismissible {
        padding-right: 3rem;
    }

    .alert .btn-close {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        padding: 0.5rem;
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        opacity: 0.7;
    }

    .alert .btn-close:hover {
        opacity: 1;
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

   /* ==================================== */
/* CSS untuk Paginasi                   */
/* ==================================== */

.pagination {
    display: flex;
    justify-content: center; /* Memusatkan paginasi secara horizontal */
    align-items: center;
    list-style: none;
    margin-top: 1.5rem; /* Memberikan jarak dari konten di atasnya */
    padding: 0;
    gap: 0.25rem; /* Jarak antar tombol paginasi */
}

/* Gaya dasar untuk setiap item paginasi (nomor, "Previous", "Next") */
.page-item {
    display: inline-block;
}

.page-link {
    padding: 0.75rem 1rem; /* Padding untuk tombol agar mudah diklik */
    font-size: 1rem;
    color: var(--text-primary);
    background-color: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 0.5rem; /* Sudut membulat */
    text-decoration: none;
    transition: all 0.2s ease; /* Transisi halus untuk efek hover */
    font-weight: 500;
}

/* Efek saat tombol paginasi di-hover */
.page-link:hover {
    color: var(--primary-light);
    background-color: var(--primary-light);
    border-color: var(--primary);
}
.page-link:hover .page-text {
    color: white;
}

/* Gaya untuk tombol paginasi yang sedang aktif */
.page-item.active .page-link {
    background-color: var(--primary);
    border-color: var(--primary);
    color: white;
    cursor: default; /* Nonaktifkan kursor klik */
}

/* Gaya untuk tombol yang dinonaktifkan (disabled) */
.page-item.disabled .page-link {
    color: var(--text-secondary);
    background-color: #e9ecef;
    border-color: var(--border-color);
    cursor: not-allowed; /* Ubah kursor menjadi tidak diizinkan */
    opacity: 0.6;
}

/* Gaya untuk ikon di dalam tombol (seperti '<' dan '>') */
.page-link i {
    font-size: 0.8rem;
    color: var(--text-primary);
}

.page-link:hover i {
    color: white;
}

.page-item.active .page-link i {
    color: white;
}

.page-item.disabled .page-link i {
    color: var(--text-secondary);
}

/* Gaya tambahan untuk teks "Previous" dan "Next" jika ada */
.page-text {
    color: var(--text-primary);
}
    /* Certificate number styling */
    .cert-number {
        font-family: monospace;
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 600;
    }

    /* Student name styling */
    .student-name {
        font-weight: 500;
        color: #1f2937;
        margin-top: 0.25rem;
    }

    /* Certificate info styling */
    .cert-info {
        line-height: 1.3;
    }

    /* Modal styling improvements */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 1.5rem;
        background-color: #f9fafb;
        border-radius: 12px 12px 0 0;
    }

    .modal-title {
        font-weight: 600;
        color: #1f2937;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
        background-color: #f9fafb;
        border-radius: 0 0 12px 12px;
    }

    /* Form improvements */
    .form-control {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        padding: 0.75rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
    }

    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-text {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }

    /* Attendance summary styling */
    .attendance-summary {
        min-width: 200px;
        padding: 0.5rem;
    }

    .attendance-stats {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 0.5rem;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        min-width: 35px;
    }

    .stat-item i {
        font-size: 0.875rem;
    }

    .stat-number {
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
    }

    .stat-present {
        color: #10b981;
    }

    .stat-permission {
        color: #3b82f6;
    }

    .stat-sick {
        color: #f59e0b;
    }

    .stat-absent {
        color: #ef4444;
    }

    /* Attendance percentage bar */
    .attendance-percentage {
        margin: 0.5rem 0;
    }

    .percentage-bar {
        width: 100%;
        height: 6px;
        background-color: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 0.25rem;
    }

    .percentage-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    .percentage-text {
        font-size: 0.7rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* Attendance actions */
    .attendance-actions {
        display: flex;
        gap: 0.25rem;
        justify-content: center;
    }

    .attendance-actions .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
    }

    .attendance-actions .btn i {
        font-size: 0.7rem;
    }

    /* Responsive attendance summary */
    @media (max-width: 768px) {
        .attendance-summary {
            min-width: 160px;
            padding: 0.375rem;
        }

        .attendance-stats {
            gap: 0.5rem;
        }

        .stat-item {
            min-width: 28px;
        }

        .stat-item i {
            font-size: 0.75rem;
        }

        .stat-number {
            font-size: 0.65rem;
        }

        .percentage-bar {
            height: 4px;
        }

        .percentage-text {
            font-size: 0.65rem;
        }

        .attendance-actions .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.65rem;
        }

        .attendance-actions .btn i {
            font-size: 0.65rem;
        }
    }

    /* Hover effects for attendance summary */
    .attendance-summary:hover .percentage-fill {
        background: linear-gradient(90deg, #059669 0%, #10b981 100%);
    }

    .attendance-summary:hover .stat-item {
        transform: scale(1.05);
        transition: transform 0.2s ease;
    }

    /* Status badges */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-badge.warning {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-badge.danger {
        background-color: #fee2e2;
        color: #b91c1c;
    }

    /* DataTables Search Box Styling */
    .dataTables_wrapper .dataTables_filter {
        text-align: right;
    }

    .dataTables_wrapper .dataTables_filter label {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.5rem;
        font-weight: 500;
        color: #374151;
    }

    .dataTables_wrapper .dataTables_filter label i {
        color: #6b7280;
        font-size: 1rem;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        margin-left: 0.5rem;
        transition: all 0.2s ease;
        width: 250px;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .dataTables_wrapper .dataTables_filter input::placeholder {
        color: #9ca3af;
        font-style: italic;
    }

    /* Custom Search Box Styling */
    #customSearchInput {
        border-radius: 0;
    }

    #customSearchBtn {
        border-radius: 0 8px 8px 0;
        padding: 0.5rem 1rem;
    }

    .input-group-text {
        border-radius: 8px 0 0 8px;
    }

    #customSearchInput:focus {
        box-shadow: none;
        border-color: #d1d5db;
    }
</style>

<h3 class="dashboard-header">Data Absensi Siswa</h3>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Daftar Siswa</h4>
        </div>
        <div class="card-body">
            <!-- Custom Search Box -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div></div>
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-start-0" 
                               id="customSearchInput" 
                               placeholder="Cari Nama Peserta"
                               style="border-left: none;">
                        <button class="btn btn-primary" type="button" id="customSearchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-sm" id="sertifikatTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA SISWA</th>
                            <th>SEKOLAH / UNIVERSITAS</th>
                            <th>JURUSAN</th>
                            <th>BIDANG</th>
                            <th>TANGGAL MULAI</th>
                            <th>TANGGAL SELESAI</th>
                            <th class="text-center">PRESENSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Riwayat Absensi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-sm">
          <thead>
            <tr>
              <th>No</th>
              <th>Status</th>
              <th>Waktu</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody id="historyTableBody"></tbody>
        </table>
      </div>
    </div>
  </div>
</div> -->

<div class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-clock me-2 text-primary"></i> Riwayat Absensi Siswa
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Filter Bulan -->
  <div class="mb-3">
    <label for="filterBulan" class="form-label">Filter Bulan</label>
    <select id="filterBulan" class="form-select form-select-sm w-auto d-inline-block">
      <option value="">Semua Bulan</option>
      <option value="01">Januari</option>
      <option value="02">Februari</option>
      <option value="03">Maret</option>
      <option value="04">April</option>
      <option value="05">Mei</option>
      <option value="06">Juni</option>
      <option value="07">Juli</option>
      <option value="08">Agustus</option>
      <option value="09">September</option>
      <option value="10">Oktober</option>
      <option value="11">November</option>
      <option value="12">Desember</option>
    </select>

     <!-- Tombol reset -->
  <button id="resetFilter" class="btn btn-sm btn-outline-secondary">
    <i class="fas fa-undo"></i> Reset
  </button>
  </div>

        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead>
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">Status</th>
                <th class="text-center">Waktu</th>
                <th class="text-center">Keterangan</th>
              </tr>
            </thead>
            <tbody id="historyTableBody">
              <tr>
                <td colspan="4" class="text-center text-muted py-4">
                  <i class="fas fa-spinner fa-spin me-2"></i>Memuat...
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
          <i class="fas fa-times"></i> Tutup
        </button>
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css" />

<script>
$(document).ready(function() {
    moment.locale('id');

    const table = $('#sertifikatTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false, // Disable default search behavior
        ajax: {
            url: "{{ route('mentor.attendance.data') }}", 
            type: "GET",
            dataType: "json",
            error: function(xhr, error, code) {
                console.error('DataTables Ajax Error:', error);
                console.error('Response:', xhr.responseText);
                alert('Terjadi kesalahan saat memuat data. Silakan refresh halaman atau hubungi administrator.');
            }
        },
        columns: [
            { 
                data: null,
                className: "text-center",
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            // Kolom ID Sertifikat telah dihapus
            { 
                data: "nama_lengkap",
                className: "student-name"
            },
            { 
                data: "asal_sekolah", 
                className: "text-left"
            },
            {
                data: "jurusan",
                className: "text-left"
            },
            {
                data: "bidang",
                className: "text-left"
            },
            {
                data: "tgl_mulai",
                className: "text-center",
                render: function(data, type, row) {
                    return data ? moment(data).format('DD-MM-YYYY') : '-';
                }
            },
            {
                data: "tgl_selesai",
                className: "text-center",
                render: function(data, type, row) {
                    return data ? moment(data).format('DD-MM-YYYY') : '-';
                }
            },
            {
                data: "presensi",
                className: "text-center",
                orderable: false, 
                searchable: false,
            //     render: function(data, type, row) {
            //         if (data && data !== '-') {
            //             const parts = data.split(',').map(s => s.trim());
            //             let hadir = 0;
            //             let izin = 0;
            //             let alpa = 0;

            //             parts.forEach(part => {
            //                 if (part.startsWith('Hadir:')) {
            //                     hadir = parseInt(part.split(':')[1].trim(), 10);
            //                 } else if (part.startsWith('Izin:')) {
            //                     izin = parseInt(part.split(':')[1].trim(), 10);
            //                 } else if (part.startsWith('Alpa:')) {
            //                     alpa = parseInt(part.split(':')[1].trim(), 10);
            //                 }
            //             });

            //             return `
            //                 <div>
            //                     <span class="badge bg-success">Hadir: ${hadir}</span>
            //                     <span class="badge bg-warning">Izin: ${izin}</span>
            //                     <span class="badge bg-danger">Alpa: ${alpa}</span>
            //                 </div>
            //             `;
            //         } else {
            //             return '<span class="status-badge absent">Belum Ada Data</span>';
            //         }
            //     }
            //}
            render: function(data, type, row) {
                // Parse data presensi dari string
                let hadir = 0, izin = 0, sakit = 0, alpa = 0;
                
                if (row.presensi && row.presensi !== '-') {
                    const parts = row.presensi.split(',').map(s => s.trim());
                    parts.forEach(part => {
                        if (part.includes('Hadir:')) {
                            hadir = parseInt(part.split(':')[1].trim()) || 0;
                        } else if (part.includes('Izin:')) {
                            izin = parseInt(part.split(':')[1].trim()) || 0;
                        } else if (part.includes('Sakit:')) {
                            sakit = parseInt(part.split(':')[1].trim()) || 0;
                        } else if (part.includes('Alpa:')) {
                            alpa = parseInt(part.split(':')[1].trim()) || 0;
                        }
                    });
                }
                
                return `
                    <div>
                        <span class="badge bg-success">Hadir: ${hadir}</span>
                        <span class="badge bg-warning">Izin: ${izin}</span>
                        <span class="badge bg-info">Sakit: ${sakit}</span>
                        <span class="badge bg-danger">Alpa: ${alpa}</span>
                        <br>
                        <button class="btn btn-sm btn-primary mt-2 view-history" data-id="${row.id_sertifikat}">
                            <i class="fas fa-clock"></i> Riwayat
                        </button>
                    </div>
                `;
                }
            },
            { data: "id_sertifikat", visible: false }
        ],
        language: {
            processing: '<div class="loading-spinner"></div>Memuat data...',
            search: '<i class="fas fa-search"></i>',
            searchPlaceholder: "Cari Nama Peserta",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            },
            emptyTable: "Tidak ada data yang tersedia",
            zeroRecords: "Tidak ditemukan data yang sesuai"
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6">>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        drawCallback: function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    // Custom search dengan icon yang harus diklik
    $('#customSearchBtn').on('click', function() {
        const searchValue = $('#customSearchInput').val();
        table.search(searchValue).draw();
    });

    // Enter key untuk search
    $('#customSearchInput').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            const searchValue = $(this).val();
            table.search(searchValue).draw();
        }
    });
});

//absensi history
//$('#sertifikatTable').on('click', '.view-history', function() {
    //const id = $(this).data('id');
    // $('#historyTableBody').html('<tr><td colspan="3" class="text-center">Memuat...</td></tr>');
    // $('#historyModal').modal('show');

    // $.get("{{ url('mentor/attendance/history') }}/" + id, function(data) {
    //     let rows = '';
    //     if (data.length > 0) {
    //         data.forEach((item, index) => {
    //             rows += `
    //                 <tr>
//                         <td>${index+1}</td>
//                         <td>${item.status}</td>
//                         <td>${moment(item.created_at).format('dddd, DD-MM-YYYY HH:mm')}</td>
//                         <td>${item.notes}</td>
//                     </tr>
//                 `;
//             });
//         } else {
//             rows = '<tr><td colspan="3" class="text-center">Belum ada data riwayat</td></tr>';
//         }
//         $('#historyTableBody').html(rows);
//     });
// });

// Absensi history
let currentId = null; // simpan id siswa aktif

    // Klik tombol riwayat
    $('#sertifikatTable').on('click', '.view-history', function() {
        currentId = $(this).data('id');
        $('#historyTableBody').html(`
            <tr>
                <td colspan="4" class="text-center text-muted py-4">
                    <i class="fas fa-spinner fa-spin me-2"></i>Memuat...
                </td>
            </tr>
        `);
        $('#historyModal').modal('show');

        loadHistory(currentId, $('#filterBulan').val());
    });

    // Saat filter bulan berubah
    $('#filterBulan').on('change', function() {
        if (currentId) {
            loadHistory(currentId, $(this).val());
        }
    });

    // Fungsi ambil data riwayat
    function loadHistory(id, bulan = '') {
        $.get("{{ url('mentor/attendance/history') }}/" + id, { bulan: bulan }, function(data) {
            let rows = '';
            if (data.length > 0) {
                data.forEach((item, index) => {
                    let badgeClass = 'secondary';
                    if (item.status === 'Hadir') badgeClass = 'success';
                    else if (item.status === 'Izin') badgeClass = 'warning';
                    else if (item.status === 'Sakit') badgeClass = 'info';
                    else if (item.status === 'Alpa') badgeClass = 'danger';

                    rows += `
                        <tr>
                            <td class="text-center">${index+1}</td>
                            <td class="text-center">
                                <span class="badge bg-${badgeClass}">${item.status}</span>
                            </td>
                            <td class="text-center">${moment(item.created_at).format('dddd, DD-MM-YYYY HH:mm')}</td>
                            <td>${item.notes ? item.notes : '<em class="text-muted">-</em>'}</td>
                        </tr>
                    `;
                });
            } else {
                rows = `
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle me-2"></i>Tidak ada data pada bulan ini
                        </td>
                    </tr>
                `;
            }
            $('#historyTableBody').html(rows);
        });
    }
    // Reset filter bulan
$('#resetFilter').on('click', function() {
    $('#filterBulan').val(''); // balik ke "Semua Bulan"
    if (currentId) {
        loadHistory(currentId, ''); // reload semua data
    }
});


</script>
@endsection