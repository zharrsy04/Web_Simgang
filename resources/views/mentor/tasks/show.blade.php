@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-tasks"></i> {{ $task->title }}
            </h2>
            <p class="text-muted mt-2">Kelola tugas dan lihat submission siswa</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('mentor.tasks.edit', $task->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('mentor.tasks.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Task Details Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-info-circle text-info"></i> Detail Tugas
                    </h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Siswa Penerima</small>
                            <strong>{{ $task->recipientStudent->nama_lengkap }}</strong>
                            <br>
                            <small class="text-muted">{{ $task->recipientStudent->nim_nis }}</small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Bidang</small>
                            <span class="badge bg-info" style="font-size: 1rem;">{{ $task->student_bidang }}</span>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Status Tugas</small>
                            @php
                                $statusClass = match($task->status) {
                                    'pending' => 'bg-warning',
                                    'in progress' => 'bg-info',
                                    'completed' => 'bg-success',
                                    default => 'bg-secondary'
                                };
                                $statusText = match($task->status) {
                                    'pending' => 'Pending',
                                    'in progress' => 'Sedang Dikerjakan',
                                    'completed' => 'Selesai',
                                    default => 'Tidak Diketahui'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}" style="font-size: 1rem;">{{ $statusText }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Deadline</small>
                            @if($task->due_date)
                                <strong>{{ $task->due_date->format('d F Y') }}</strong>
                                @if($task->due_date < now() && $task->status !== 'completed')
                                    <br>
                                    <span class="badge bg-danger">Overdue</span>
                                @endif
                            @else
                                <span class="text-muted">Tidak ada deadline</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Dibuat</small>
                            <small>{{ $task->created_at->format('d M Y H:i') }}</small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Terakhir Diubah</small>
                            <small>{{ $task->updated_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-file-alt"></i> Deskripsi / Instruksi
                    </h5>
                    @if($task->description)
                        <div class="bg-light p-3 rounded" style="white-space: pre-wrap;">
                            {{ $task->description }}
                        </div>
                    @else
                        <p class="text-muted">Tidak ada deskripsi tambahan</p>
                    @endif
                </div>
            </div>

            <!-- Submissions Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-file-upload"></i> Submission Siswa
                    </h5>

                    @php
                        $submission = $task->submissions->first();
                    @endphp

                    @if($submission)
                        <div class="submission-details">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Status Submission</small>
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
                                    <span class="badge {{ $subStatusClass }}" style="font-size: 0.9rem;">{{ $subStatusText }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Dikumpulkan</small>
                                    @if($submission->submitted_at)
                                        <small>{{ $submission->submitted_at->format('d M Y H:i') }}</small>
                                    @else
                                        <small class="text-muted">Belum dikumpulkan</small>
                                    @endif
                                </div>
                            </div>

                            @if($submission->notes)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Catatan Siswa</small>
                                    <div class="bg-light p-2 rounded small" style="white-space: pre-wrap;">
                                        {{ $submission->notes }}
                                    </div>
                                </div>
                            @endif

                            @if($submission->file_paths && count($submission->file_paths) > 0)
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-2">File yang Dikumpulkan</small>
                                    <div class="list-group list-group-sm">
                                        @foreach($submission->file_paths as $file)
                                            <a href="{{ asset('storage/' . $file) }}" class="list-group-item list-group-item-action" target="_blank">
                                                <i class="fas fa-file"></i> {{ basename($file) }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($submission->status === 'graded')
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Nilai</small>
                                        <h4 class="text-success">{{ $submission->grade }}</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Dinilai</small>
                                        <small>{{ $submission->graded_at->format('d M Y H:i') }}</small>
                                    </div>
                                </div>

                                @if($submission->mentor_feedback)
                                    <div class="mt-3">
                                        <small class="text-muted d-block">Feedback Mentor</small>
                                        <div class="bg-light p-2 rounded small" style="white-space: pre-wrap;">
                                            {{ $submission->mentor_feedback }}
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-3">
                                    <button type="button" class="btn btn-sm btn-warning" onclick="openGradeModal({{ $submission->id }}, '{{ $submission->grade }}', '{{ addslashes($submission->mentor_feedback ?? '') }}')">
                                        <i class="fas fa-edit"></i> Edit Nilai
                                    </button>
                                </div>
                            @elseif($submission->status === 'submitted')
                                <div class="mt-3">
                                    <button type="button" class="btn btn-primary" onclick="openGradeModal({{ $submission->id }}, null, '')">
                                        <i class="fas fa-star"></i> Beri Nilai
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-hourglass-half"></i> Siswa belum mengumpulkan tugas ini.
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-hourglass-half"></i> Belum ada submission dari siswa untuk tugas ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-magic"></i> Aksi Cepat
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('mentor.tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit Tugas
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteTask({{ $task->id }})">
                            <i class="fas fa-trash"></i> Hapus Tugas
                        </button>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-info-circle text-info"></i> Informasi
                    </h6>
                    <div class="small text-muted">
                        <p><strong>ID Tugas:</strong> #{{ $task->id }}</p>
                        <p><strong>Jenis Relasi:</strong></p>
                        <ul class="small">
                            <li>Siswa: {{ $task->recipientStudent->nim_nis }}</li>
                            <li>Sertifikat ID: {{ $task->sertifikat_pkl_id }}</li>
                            <li>Mentor ID: {{ $task->assigned_by }}</li>
                        </ul>
                        <p class="mt-3"><strong>Data Terhubung dengan:</strong></p>
                        <ul class="small">
                            <li>✓ Tabel: tbl_sertifikat_pkl</li>
                            <li>✓ Tabel: tbl_login</li>
                            <li>✓ Tabel: penempatan_bidang</li>
                            <li>✓ Tabel: task_submissions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Beri Nilai -->
<div class="modal fade" id="gradeModal" tabindex="-1" aria-labelledby="gradeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gradeModalLabel">
                    <i class="fas fa-star"></i> Beri Nilai Tugas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="gradeForm">
                <div class="modal-body">
                    <input type="hidden" id="submission_id" name="submission_id">
                    
                    <div class="mb-3">
                        <label for="grade" class="form-label">
                            Nilai <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="grade" name="grade" required>
                            <option value="" disabled selected>Pilih Nilai</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                        <div class="invalid-feedback">
                            Nilai wajib dipilih
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mentor_feedback" class="form-label">
                            Feedback/Catatan <small class="text-muted">(opsional)</small>
                        </label>
                        <textarea class="form-control" id="mentor_feedback" name="mentor_feedback" 
                                  rows="4" maxlength="2000" 
                                  placeholder="Berikan masukan atau komentar untuk siswa..."></textarea>
                        <small class="text-muted">Maksimal 2000 karakter</small>
                    </div>

                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle"></i> 
                        Nilai dan feedback ini akan terlihat oleh siswa setelah disimpan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let gradeModalInstance = null;

// Initialize modal when page loads
document.addEventListener('DOMContentLoaded', function() {
    const gradeModalEl = document.getElementById('gradeModal');
    if (gradeModalEl) {
        gradeModalInstance = new bootstrap.Modal(gradeModalEl);
    }
});

// Open grade modal
function openGradeModal(submissionId, currentGrade = null, currentFeedback = '') {
    document.getElementById('submission_id').value = submissionId;
    document.getElementById('grade').value = currentGrade || '';
    document.getElementById('mentor_feedback').value = currentFeedback || '';
    
    // Update modal title
    const modalTitle = currentGrade !== null ? 'Edit Nilai Tugas' : 'Beri Nilai Tugas';
    document.getElementById('gradeModalLabel').innerHTML = `<i class="fas fa-star"></i> ${modalTitle}`;
    
    // Reset validation
    document.getElementById('gradeForm').classList.remove('was-validated');
    
    // Show modal
    if (gradeModalInstance) {
        gradeModalInstance.show();
    }
}

// Handle form submission
document.getElementById('gradeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate form
    if (!this.checkValidity()) {
        e.stopPropagation();
        this.classList.add('was-validated');
        return;
    }
    
    const submissionId = document.getElementById('submission_id').value;
    const grade = document.getElementById('grade').value;
    const feedback = document.getElementById('mentor_feedback').value;
    
    // Validate grade option
    if (!['A', 'B', 'C', 'D', 'E'].includes(grade)) {
        showErrorToast('Nilai tidak valid');
        return;
    }
    
    // Disable submit button
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    
    // Send AJAX request
    fetch(`{{ url('/mentor/tasks/submission') }}/${submissionId}/grade`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            grade: grade,
            mentor_feedback: feedback
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast('Penilaian berhasil disimpan!');
            
            // Close modal
            if (gradeModalInstance) {
                gradeModalInstance.hide();
            }
            
            // Reload page after short delay
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showErrorToast(data.error || 'Gagal menyimpan penilaian');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('Gagal menyimpan penilaian. Periksa koneksi Anda.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
});

function deleteTask(taskId) {
    showConfirm(
        'Tugas "{{ addslashes($task->title) }}" akan dihapus permanen beserta semua data submission-nya. Tindakan ini tidak dapat dibatalkan.',
        function() {
            fetch(`{{ url('/mentor/tasks') }}/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast('Tugas berhasil dihapus.');
                    setTimeout(() => {
                        window.location.href = '{{ route("mentor.tasks.index") }}';
                    }, 1500);
                } else {
                    showErrorToast(data.error || 'Gagal menghapus tugas.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorToast('Gagal menghapus tugas. Periksa koneksi Anda.');
            });
        },
        'Hapus Tugas',
        'Ya, Hapus',
        'Batal'
    );
}
</script>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
