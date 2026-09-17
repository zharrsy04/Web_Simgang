@extends('layouts.app')

@section('title', 'Data Mentor')

@section('content')
<div class="container-fluid py-5">
    <div class="mb-4">
        <a href="{{ route('sekretariat.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <h2 class="dashboard-header">Data Mentor</h2>

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
                <i class="fas fa-user-tie me-2"></i> Daftar Mentor
            </h5>
        </div>
        <div class="card-body p-0">
            @if($mentors && count($mentors) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th>Nama Mentor</th>
                                <th>Username</th>
                                <th>NIP</th>
                                <th>Bidang</th>
                                <th class="text-center">Siswa Dibimbing</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mentors as $mentor)
                                <tr>
                                    <td>
                                        <strong style="color: #667eea;">{{ $mentor['nama'] }}</strong>
                                    </td>
                                    <td>
                                        <small>{{ $mentor['username'] }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $mentor['nip'] }}</small>
                                    </td>
                                    <td>
                                        <small>
                                            @if($mentor['bidang'])
                                                @foreach(explode(', ', $mentor['bidang']) as $b)
                                                    <span class="badge bg-info">{{ $b }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $mentor['siswa_count'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-user-slash" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="mt-3">Belum ada data mentor</p>
                </div>
            @endif
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
</style>
@endsection
