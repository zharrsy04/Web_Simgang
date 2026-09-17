@extends('layouts.app')

@section('title', 'Detail Bidang - ' . $bidang)

@section('content')
<div class="container-fluid py-5">
    <div class="mb-4">
        <a href="{{ route('sekretariat.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <h2 class="dashboard-header mt-3">{{ $bidang }}</h2>
    </div>

    <!-- Periode Info -->
    @if($periodeInfo['tgl_mulai'] && $periodeInfo['tgl_selesai'])
        <div class="alert alert-info mb-4">
            <i class="fas fa-calendar"></i>
            <strong>Periode Magang:</strong> 
            {{ \Carbon\Carbon::parse($periodeInfo['tgl_mulai'])->format('d M Y') }} - 
            {{ \Carbon\Carbon::parse($periodeInfo['tgl_selesai'])->format('d M Y') }}
        </div>
    @endif

    <!-- Statistics for this field -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm" style="border-top: 4px solid #667eea;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #667eea;">{{ $totalSiswa }}</h3>
                    <p class="text-muted mb-0">Total Siswa</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm" style="border-top: 4px solid #00d4ff;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #00d4ff;">{{ $siswaAktif }}</h3>
                    <p class="text-muted mb-0">Siswa Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center border-0 shadow-sm" style="border-top: 4px solid #27ae60;">
                <div class="card-body">
                    <h3 class="fw-bold" style="color: #27ae60;">{{ $siswaLulus }}</h3>
                    <p class="text-muted mb-0">Siswa Lulus</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Mentors (hanya untuk Sekretariat) -->
        @if($userRole === 'sekretariat')
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i> Daftar Mentor
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($mentors) && count($mentors) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($mentors as $mentor)
                                <div class="list-group-item border-0 py-3 px-3" style="border-bottom: 1px solid #e9ecef;">
                                    <h6 class="mb-1 fw-bold" style="color: #667eea;">
                                        {{ $mentor['nama'] }}
                                    </h6>
                                    <small class="text-muted d-block">
                                        {{ $mentor['siswa_count'] }} siswa dibimbing
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-user-slash" style="font-size: 2rem; color: #ccc;"></i>
                            <p class="mt-2 mb-0">Belum ada mentor</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Students (Full width for Sekretariat) -->
        <div class="col-lg-8">
        @else
        <!-- Students (Full width for Mentor) -->
        <div class="col-lg-12">
        @endif
            <div class="card shadow-sm border-0">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i> 
                        @if($userRole === 'mentor')
                            Siswa Magang Saya di {{ $bidang }}
                        @else
                            Daftar Siswa Magang
                        @endif
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($siswa && count($siswa) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th>Nama Siswa</th>
                                        @if($userRole === 'sekretariat')
                                        <th>Mentor</th>
                                        @endif
                                        <th>Asal Sekolah</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        @if($userRole === 'sekretariat')
                                        <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($siswa as $s)
                                        <tr>
                                            <td>
                                                <strong style="color: #667eea;">{{ $s->nama_lengkap }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $s->nim_nis }}</small>
                                            </td>
                                            @if($userRole === 'sekretariat')
                                            <td>
                                                <small>{{ $s->createdBy->nama ?? $s->createdBy->username ?? 'N/A' }}</small>
                                            </td>
                                            @endif
                                            <td>
                                                <small>{{ $s->asal_sekolah }}</small>
                                            </td>
                                            <td>
                                                <small>
                                                    {{ $s->tgl_mulai->format('d M Y') }} - {{ $s->tgl_selesai->format('d M Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = 'bg-warning';
                                                    $statusText = 'Aktif';
                                                    if ($s->sts_diambil) {
                                                        $statusClass = 'bg-success';
                                                        $statusText = 'Selesai';
                                                    } elseif ($s->tgl_selesai < now()) {
                                                        $statusClass = 'bg-danger';
                                                        $statusText = 'Expired';
                                                    }
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            @if($userRole === 'sekretariat')
                                            <td>
                                                <a href="{{ route('sekretariat.edit-siswa', $s->id_sertifikat) }}" class="btn btn-sm btn-outline-primary" title="Edit Siswa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('mentor.show', $s->id_sertifikat) }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox" style="font-size: 2rem; color: #ccc;"></i>
                            <p class="mt-2 mb-0">Belum ada siswa magang di bidang ini</p>
                        </div>
                    @endif
                </div>
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
</style>
@endsection
