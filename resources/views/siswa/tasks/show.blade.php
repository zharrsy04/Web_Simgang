@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="container-fluid px-4">
    <!-- Header dengan Gradient -->
    <div class="row mb-4">
        <div class="col-12">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 class="mb-2"><i class="fas fa-clipboard me-2"></i>{{ $task->title }}</h1>
                    <p class="mb-0 opacity-90">Informasi detail dan submission tugas</p>
                </div>
                <a href="{{ route('siswa.tasks.index') }}" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.5);">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-md-8">
            <!-- Task Detail Card -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Tugas</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 10px;">
                                <small class="text-muted d-block mb-2"><i class="fas fa-user me-2" style="color: #667eea;"></i>Pemberi Tugas</small>
                                <p class="mb-0 fw-600">{{ $task->assignedByUser->nama ?? $task->assignedByUser->username }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 10px;">
                                <small class="text-muted d-block mb-2"><i class="fas fa-calendar me-2" style="color: #667eea;"></i>Tanggal Dibuat</small>
                                <p class="mb-0 fw-600">{{ $task->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($task->due_date)
                        <div class="alert {{ now()->gt($task->due_date) && (!$submission || $submission->status === 'pending') ? 'alert-danger' : 'alert-info' }} alert-dismissible" style="border: none; border-left: 4px solid;">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Deadline:</strong>
                            {{ $task->due_date->format('d M Y, H:i') }}
                            @if(now()->gt($task->due_date) && (!$submission || $submission->status === 'pending'))
                                <span class="badge bg-danger ms-2"><i class="fas fa-exclamation-triangle me-1"></i>Terlambat</span>
                            @endif
                        </div>
                    @endif

                    @if($task->catatan_izin)
                        <div class="alert alert-info" style="border: none; border-left: 4px solid #17a2b8;">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Tugas Lintas Bimbingan</strong>
                            <div class="mt-2">
                                <small>Alasan: {{ $task->catatan_izin }}</small>
                            </div>
                        </div>
                    @endif
                    
                    <hr>
                    
                    <h6 class="fw-bold mb-3"><i class="fas fa-align-left me-2" style="color: #667eea;"></i>Deskripsi Tugas:</h6>
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; line-height: 1.6; color: #555;">
                        {!! nl2br(e($task->description)) !!}
                    </div>
                </div>
            </div>
            
            @if($submission)
                <!-- Submission Status Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header 
                        @if($submission->status === 'submitted') bg-info text-white
                        @elseif($submission->status === 'graded') bg-success text-white
                        @elseif($submission->status === 'revision') bg-warning text-dark
                        @else bg-secondary text-white @endif">
                        <h5 class="mb-0"><i class="fas fa-check-square"></i> Status Pengumpulan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Status:</strong></p>
                                @if($submission->status === 'submitted')
                                    <span class="badge bg-info fs-6">Menunggu Penilaian</span>
                                @elseif($submission->status === 'graded')
                                    <span class="badge bg-success fs-6">Selesai Dinilai</span>
                                @elseif($submission->status === 'revision')
                                    <span class="badge bg-warning fs-6">Perlu Revisi</span>
                                @else
                                    <span class="badge bg-secondary fs-6">Belum Dikumpulkan</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p><strong>Dikumpulkan:</strong></p>
                                <p class="mb-0">
                                    @if($submission->submitted_at)
                                        {{ $submission->submitted_at->format('d M Y, H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        @if($submission->notes)
                            <hr>
                            <p><strong>Catatan Anda:</strong></p>
                            <div class="bg-light p-3 rounded">
                                {{ $submission->notes }}
                            </div>
                        @endif
                        
                        @if($submission->file_paths && count($submission->file_paths) > 0)
                            <hr>
                            <p><strong>File yang Dikumpulkan:</strong></p>
                            <ul class="list-group">
                                @foreach($submission->file_paths as $index => $path)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-file-alt text-primary"></i>
                                            {{ basename($path) }}
                                        </span>
                                        <a href="{{ Storage::url($path) }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        
                        @if($submission->mentor_feedback)
                            <hr>
                            <p><strong><i class="fas fa-comment-dots me-2" style="color: #667eea;"></i>Feedback Mentor:</strong></p>
                            <div class="alert {{ $submission->status === 'revision' ? 'alert-warning' : 'alert-info' }}" style="border-left: 4px solid {{ $submission->status === 'revision' ? '#ffc107' : '#17a2b8' }}; background: {{ $submission->status === 'revision' ? '#fff8e1' : '#e7f3ff' }};">
                                <i class="fas fa-{{ $submission->status === 'revision' ? 'exclamation-circle' : 'info-circle' }} me-2"></i>
                                {{ $submission->mentor_feedback }}
                            </div>
                        @endif
                        
                        @if($submission->status === 'graded')
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nilai:</strong></p>
                                    <h3 class="mb-0">
                                        <span class="badge bg-success fs-4">{{ $submission->grade ?? '-' }}</span>
                                    </h3>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Dinilai Pada:</strong></p>
                                    <p>{{ $submission->graded_at ? $submission->graded_at->format('d M Y, H:i') : '-' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-md-4">
            @if(!$submission || $submission->status === 'revision')
                <!-- Submit Form -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-upload"></i> 
                            {{ $submission && $submission->status === 'revision' ? 'Kirim Revisi' : 'Kumpulkan Tugas' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($submission && $submission->status === 'submitted')
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle"></i> 
                                Upload ulang akan mengganti file yang sudah dikumpulkan.
                            </div>
                        @endif
                        <form action="{{ route('siswa.tasks.submit', $task->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Catatan (Opsional)</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                    name="notes" rows="4" placeholder="Tambahkan catatan atau keterangan...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 1000 karakter</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Upload File</label>
                                <input type="file" class="form-control @error('files.*') is-invalid @enderror" 
                                    name="files[]" multiple>
                                @error('files.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    Format: Semua jenis file termasuk video (PDF, DOC, DOCX, ZIP, JPG, PNG, MP4, dll)<br>
                                    Maksimal 50MB per file<br>
                                    Dapat upload multiple files
                                </small>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-paper-plane"></i> Kirim Tugas
                            </button>
                        </form>
                    </div>
                </div>
            @elseif($submission && $submission->status === 'submitted' && $task->due_date && now()->lt($task->due_date))
                <!-- Re-upload Form -->
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-redo"></i> Upload Ulang
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle"></i> 
                            Anda masih bisa mengganti file sebelum deadline.
                        </div>
                        <form action="{{ route('siswa.tasks.submit', $task->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Catatan (Opsional)</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                    name="notes" rows="4" placeholder="Tambahkan catatan atau keterangan...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Upload File Baru</label>
                                <input type="file" class="form-control @error('files.*') is-invalid @enderror" 
                                    name="files[]" multiple>
                                @error('files.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    Format: Semua jenis file termasuk video<br>
                                    Maksimal 50MB per file
                                </small>
                            </div>
                            
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-redo"></i> Ganti File
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.list-group-item {
    border-left: 3px solid #0d6efd;
}
</style>
@endsection
