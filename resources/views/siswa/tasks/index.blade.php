@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px;">
                <h1 class="mb-2"><i class="fas fa-tasks me-2"></i>Kelola Tugas Magang</h1>
                <p class="mb-0 opacity-90">Lihat dan kumpulkan semua tugas yang telah diberikan oleh mentor</p>
            </div>
        </div>
    </div>
    
    @if(isset($message))
        <div class="alert alert-info alert-dismissible fade show">
            <i class="fas fa-info-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @else
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 rounded-4" style="border-left: 4px solid #6c757d;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-1">Total Tugas</div>
                            <h3 class="mb-0" style="color: #6c757d;">{{ $stats['total'] }}</h3>
                        </div>
                        <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; color: #6c757d;">
                            <i class="fas fa-clipboard-list fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 rounded-4" style="border-left: 4px solid #ffc107;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-1">Belum Dikerjakan</div>
                            <h3 class="mb-0" style="color: #ffc107;">{{ $stats['pending'] }}</h3>
                        </div>
                        <div style="background: #fffbf0; padding: 12px; border-radius: 8px; color: #ffc107;">
                            <i class="fas fa-hourglass-half fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 rounded-4" style="border-left: 4px solid #17a2b8;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-1">Menunggu Penilaian</div>
                            <h3 class="mb-0" style="color: #17a2b8;">{{ $stats['submitted'] }}</h3>
                        </div>
                        <div style="background: #f0f8fa; padding: 12px; border-radius: 8px; color: #17a2b8;">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 rounded-4" style="border-left: 4px solid #28a745;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-1">Selesai Dinilai</div>
                            <h3 class="mb-0" style="color: #28a745;">{{ $stats['graded'] }}</h3>
                        </div>
                        <div style="background: #f0f9f4; padding: 12px; border-radius: 8px; color: #28a745;">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tasks List -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2" style="color: #667eea;"></i>Daftar Tugas</h5>
        </div>
        <div class="card-body">
            @if($tasks->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3" style="opacity: 0.5;"></i>
                    <p class="text-muted mb-0">Belum ada tugas yang diberikan</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Judul Tugas</th>
                                <th width="30%">Deskripsi</th>
                                <th width="12%">Mentor</th>
                                <th width="10%">Deadline</th>
                                <th width="10%">Status</th>
                                <th width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $index => $task)
                                @php
                                    $submission = $task->submissions->first();
                                    $statusClass = 'bg-secondary';
                                    $statusText = 'Belum Dikerjakan';
                                    $statusIcon = 'fa-circle-xmark';
                                    
                                    if ($submission) {
                                        switch($submission->status) {
                                            case 'submitted':
                                                $statusClass = 'bg-info';
                                                $statusText = 'Menunggu Penilaian';
                                                $statusIcon = 'fa-hourglass-half';
                                                break;
                                            case 'graded':
                                                $statusClass = 'bg-success';
                                                $statusText = 'Selesai';
                                                $statusIcon = 'fa-check-circle';
                                                break;
                                            case 'revision':
                                                $statusClass = 'bg-warning';
                                                $statusText = 'Perlu Revisi';
                                                $statusIcon = 'fa-exclamation-circle';
                                                break;
                                        }
                                    }
                                    
                                    $isOverdue = $task->due_date && now()->gt($task->due_date) && !$submission;
                                @endphp
                                <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                                    <td><strong>{{ $index + 1 }}</strong></td>
                                    <td>
                                        <strong style="color: #2c3e50;">{{ $task->title }}</strong>
                                        @if($isOverdue)
                                            <br><span class="badge bg-danger small mt-2"><i class="fas fa-exclamation-triangle me-1"></i>Terlambat</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ \Str::limit($task->description, 80) }}</small>
                                    </td>
                                    <td>
                                        <small class="fw-500">{{ $task->assignedByUser->nama ?? $task->assignedByUser->username }}</small>
                                    </td>
                                    <td>
                                        @if($task->due_date)
                                            <small>{{ $task->due_date->format('d M Y') }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusClass }}" style="padding: 6px 10px; font-size: 0.85rem;">
                                            <i class="fas {{ $statusIcon }} me-1"></i>{{ $statusText }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('siswa.tasks.show', $task->id) }}" 
                                           class="btn btn-sm" style="background: #667eea; color: white; padding: 6px 12px; font-size: 0.85rem; border-radius: 6px; text-decoration: none;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

<style>
.stat-card {
    border: none;
    border-radius: 10px;
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.stat-icon {
    opacity: 0.8;
}

.card {
    border: none;
    border-radius: 10px;
}

.table th {
    font-weight: 600;
    color: #495057;
}
</style>
@endsection
