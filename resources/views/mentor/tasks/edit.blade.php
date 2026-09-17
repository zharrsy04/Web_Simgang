@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-edit"></i> Edit Tugas
            </h2>
            <p class="text-muted mt-2">Ubah detail tugas atau berikan penilaian</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('mentor.tasks.show', $task->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-pencil-alt"></i> Informasi Tugas
                    </h5>

                    <form action="{{ route('mentor.tasks.update', $task->id) }}" method="POST" id="editTaskForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold">Siswa Penerima (Tidak dapat diubah)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $task->recipientStudent->nama_lengkap }} ({{ $task->recipientStudent->nim_nis }})" disabled>
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-2">Untuk mengubah siswa penerima, buat tugas baru</small>
                        </div>

                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">Judul Tugas <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   value="{{ old('title', $task->title) }}" required maxlength="255">
                            @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Deskripsi / Instruksi</label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="6" maxlength="5000">{{ old('description', $task->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="due_date" class="form-label fw-bold">Tanggal Deadline</label>
                            <input type="date" id="due_date" name="due_date" class="form-control form-control-lg @error('due_date') is-invalid @enderror"
                                   value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
                            @error('due_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Status Tugas <span class="text-danger">*</span></label>
                            <select id="status" name="status" class="form-control form-control-lg @error('status') is-invalid @enderror" required>
                                <option value="pending" @if(old('status', $task->status) === 'pending') selected @endif>Pending</option>
                                <option value="in progress" @if(old('status', $task->status) === 'in progress') selected @endif>Sedang Dikerjakan</option>
                                <option value="completed" @if(old('status', $task->status) === 'completed') selected @endif>Selesai</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('mentor.tasks.show', $task->id) }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Submission Info Sidebar -->
        <div class="col-lg-4">
            @php
                $submission = $task->submissions->first();
            @endphp

            @if($submission)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="card-title mb-3">
                            <i class="fas fa-file-upload"></i> Submission Siswa
                        </h6>

                        <div class="small">
                            <p>
                                <strong>Status:</strong>
                                @php
                                    $subStatusClass = match($submission->status) {
                                        'submitted' => 'bg-info',
                                        'graded' => 'bg-success',
                                        'revision' => 'bg-warning',
                                        default => 'bg-secondary'
                                    };
                                    $subStatusText = match($submission->status) {
                                        'submitted' => 'Dikumpulkan',
                                        'graded' => 'Sudah Dinilai',
                                        'revision' => 'Perlu Revisi',
                                        default => 'Pending'
                                    };
                                @endphp
                                <span class="badge {{ $subStatusClass }}">{{ $subStatusText }}</span>
                            </p>

                            @if($submission->submitted_at)
                                <p><strong>Dikumpulkan:</strong> {{ $submission->submitted_at->format('d M Y H:i') }}</p>
                            @endif

                            @if($submission->status === 'graded')
                                <p><strong>Nilai:</strong> <span class="text-success fw-bold">{{ $submission->grade }}</span></p>
                                <p><strong>Dinilai:</strong> {{ $submission->graded_at->format('d M Y H:i') }}</p>
                            @endif

                            @if($submission->file_paths && count($submission->file_paths) > 0)
                                <p class="mb-2"><strong>File:</strong></p>
                                <div class="list-group list-group-sm">
                                    @foreach($submission->file_paths as $file)
                                        <a href="{{ asset('storage/' . $file) }}" class="list-group-item list-group-item-action py-1" target="_blank">
                                            <i class="fas fa-file-download"></i> {{ basename($file) }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-hourglass-half"></i> Belum ada submission dari siswa
                </div>
            @endif

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-info-circle text-info"></i> Catatan
                    </h6>
                    <small class="text-muted">
                        Perubahan pada judul, deskripsi, deadline, dan status tugas akan ditampilkan di dashboard siswa.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
