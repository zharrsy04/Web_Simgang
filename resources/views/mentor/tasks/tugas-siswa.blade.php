@extends('layouts.app')

@section('title', 'Tugas Siswa')

@section('content')
<style>
    .dashboard-header {
        font-size: 1.8rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 2rem;
        text-align: left;
        margin-top: 2rem;
        letter-spacing: -0.5px;
    }

    .student-info-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
    }

    .student-name {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-color);
    }

    .student-details {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    .task-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .task-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }

    .task-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-color);
    }

    .task-mentor {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    .task-description {
        color: var(--text-color);
        margin-bottom: 1rem;
        white-space: pre-wrap;
    }

    .task-meta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .task-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .status-pending {
        background: #ffc107;
        color: #000;
    }

    .status-submitted {
        background: #28a745;
        color: #fff;
    }

    .status-late {
        background: #dc3545;
        color: #fff;
    }

    .btn-download {
        background: var(--primary);
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-download:hover {
        background: var(--primary-dark);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--text-muted);
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-color);
        text-decoration: none;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        color: var(--primary);
    }
</style>

<div class="container">
    <a href="{{ route('mentor.tasks.daftar-tugas') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tugas
    </a>

    <div class="dashboard-header">
        <i class="fas fa-tasks"></i> Tugas Siswa
    </div>

    <div class="student-info-card">
        <div class="student-name">{{ $siswa->nama_lengkap }}</div>
        <div class="student-details">
            <span><i class="fas fa-id-card"></i> {{ $siswa->nim_nis }}</span> • 
            <span><i class="fas fa-school"></i> {{ $siswa->asal_sekolah }}</span> • 
            <span><i class="fas fa-building"></i> {{ $siswa->bidang }}</span>
        </div>
    </div>

    @if($tasks->isEmpty())
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>Siswa ini belum memiliki tugas.</p>
        </div>
    @else
        @foreach($tasks as $task)
        <div class="task-card" id="task-{{ $task->id }}">
            <div class="task-header">
                <div>
                    <div class="task-title">{{ $task->title }}</div>
                    @if($task->assignedByUser)
                    <div class="task-mentor">
                        <i class="fas fa-user-tie"></i> {{ $task->assignedByUser->nama ?? $task->assignedByUser->username }}
                    </div>
                    @endif
                    @if($task->catatan_izin)
                    <div style="margin-top: 0.5rem;">
                        <span class="badge bg-info">
                            <i class="fas fa-info-circle"></i> Tugas Lintas Bimbingan
                        </span>
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">
                            <strong>Alasan:</strong> {{ $task->catatan_izin }}
                        </div>
                    </div>
                    @endif
                </div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    @php
                        $hasSubmission = $task->submissions && !$task->submissions->isEmpty();
                        $status = 'pending';
                        $statusText = 'Belum Dikumpulkan';
                        $now = now();
                        
                        if ($task->due_date && $now > $task->due_date) {
                            $status = 'late';
                            $statusText = 'Terlambat';
                        }
                        
                        if ($hasSubmission) {
                            $latestSubmission = $task->submissions->first();
                            if ($latestSubmission->status === 'submitted' || $latestSubmission->status === 'graded') {
                                $status = 'submitted';
                                $statusText = 'Sudah Dikumpulkan';
                            }
                        }
                    @endphp
                    @if($hasSubmission)
                        <span class="badge bg-warning" style="font-size: 0.75rem;" title="Tugas sudah dikumpulkan">
                            <i class="fas fa-exclamation-triangle"></i> Ada Pengumpulan
                        </span>
                    @endif
                    <span class="status-badge status-{{ $status }}">{{ $statusText }}</span>
                    <div style="display: flex; gap: 0.25rem;">
                        @if($hasSubmission)
                            @php
                                $latestSubmission = $task->submissions->first();
                                $isGraded = $latestSubmission && $latestSubmission->status === 'graded';
                            @endphp
                            @if($isGraded)
                                <a href="{{ route('mentor.tasks.show', $task->id) }}" class="btn btn-sm btn-success" title="Lihat Nilai">
                                    <i class="fas fa-star"></i> Nilai: {{ $latestSubmission->grade }}
                                </a>
                            @else
                                <a href="{{ route('mentor.tasks.show', $task->id) }}" class="btn btn-sm btn-info" title="Beri Nilai">
                                    <i class="fas fa-star"></i> Beri Nilai
                                </a>
                            @endif
                        @endif
                        <button class="btn btn-sm btn-primary" onclick="openEditTaskModal({{ $task->id }})" title="Edit Tugas">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="confirmDeleteTask({{ $task->id }}, {{ json_encode($task->title) }}, {{ json_encode($siswa->nama_lengkap) }}, {{ $hasSubmission ? 'true' : 'false' }})" title="Hapus Tugas">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="task-meta">
                <div class="task-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>Tenggat: {{ $task->due_date ? $task->due_date->format('d M Y') : '-' }}</span>
                </div>
                <div class="task-meta-item">
                    <i class="fas fa-clock"></i>
                    <span>Dibuat: {{ $task->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>

            @if($task->description)
            <div class="task-description">{{ $task->description }}</div>
            @endif

            @if($task->submissions && !$task->submissions->isEmpty())
                @foreach($task->submissions as $submission)
                    @if($submission->file_paths && !empty($submission->file_paths))
                        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                            <div style="font-weight: 500; margin-bottom: 0.5rem;">
                                <i class="fas fa-file-upload"></i> File Dikumpulkan:
                            </div>
                            @foreach($submission->file_paths as $filePath)
                                <a href="{{ route('mentor.tasks.download-submission', $submission->id) }}" class="btn-download">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                            @endforeach
                            @if($submission->notes)
                                <div style="margin-top: 0.5rem; color: var(--text-muted); font-size: 0.9rem;">
                                    <strong>Catatan:</strong> {{ $submission->notes }}
                                </div>
                            @endif
                            @if($submission->grade)
                                <div style="margin-top: 0.5rem; color: var(--primary); font-weight: 500;">
                                    <i class="fas fa-star"></i> Nilai: {{ $submission->grade }}
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
        @endforeach
    @endif
</div>

<!-- Modal Edit Tugas -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editTaskModalLabel"><i class="fas fa-edit me-2"></i> Edit Tugas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    <input type="hidden" id="editTaskId">
                    <div class="mb-3">
                        <label for="editTaskTitle" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editTaskTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskDescription" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="editTaskDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskDueDate" class="form-label">Tenggat Waktu</label>
                        <input type="date" class="form-control" id="editTaskDueDate">
                    </div>
                    <div id="editTaskAlert" class="alert mt-3" style="display:none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="submitEditTaskBtn">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditTaskModal(taskId) {
        console.log('=== DEBUG: openEditTaskModal called with taskId:', taskId);
        
        // Fetch task data from server
        const fetchUrl = '{{ route('mentor.tasks.api.get-task', 'TASK_ID') }}'.replace('TASK_ID', taskId);
        console.log('=== DEBUG: Fetch URL:', fetchUrl);
        
        fetch(fetchUrl)
            .then(response => {
                console.log('=== DEBUG: Response received, status:', response.status);
                console.log('=== DEBUG: Response ok:', response.ok);
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.log('=== DEBUG: Response body:', text);
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('=== DEBUG: JSON data received:', data);
                
                document.getElementById('editTaskId').value = data.id;
                document.getElementById('editTaskTitle').value = data.title;
                document.getElementById('editTaskDescription').value = data.description || '';
                document.getElementById('editTaskDueDate').value = data.due_date || '';
                document.getElementById('editTaskAlert').style.display = 'none';
                
                console.log('=== DEBUG: Form filled, opening modal...');
                const modalElement = document.getElementById('editTaskModal');
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                console.log('=== DEBUG: Modal opened successfully');
            })
            .catch(error => {
                console.error('=== DEBUG ERROR in openEditTaskModal:', error);
                console.error('=== DEBUG ERROR message:', error.message);
                console.error('=== DEBUG ERROR stack:', error.stack);
                showErrorToast('DEBUG ERROR Edit: ' + (error.message || JSON.stringify(error)));
            });
    }

    document.getElementById('submitEditTaskBtn').addEventListener('click', function() {
        const taskId = document.getElementById('editTaskId').value;
        const title = document.getElementById('editTaskTitle').value;
        const description = document.getElementById('editTaskDescription').value;
        const dueDate = document.getElementById('editTaskDueDate').value;
        const alertDiv = document.getElementById('editTaskAlert');

        if (!title) {
            alertDiv.className = 'alert alert-danger';
            alertDiv.textContent = 'Judul tugas harus diisi';
            alertDiv.style.display = 'block';
            return;
        }

        fetch('{{ route('mentor.tasks.update', 'TASK_ID') }}'.replace('TASK_ID', taskId), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                title: title,
                description: description,
                due_date: dueDate
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertDiv.className = 'alert alert-success';
                alertDiv.textContent = data.message;
                alertDiv.style.display = 'block';
                
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editTaskModal'));
                    modal.hide();
                    showSuccessToast(data.message);
                    window.location.reload();
                }, 1500);
            } else {
                alertDiv.className = 'alert alert-danger';
                alertDiv.textContent = data.message || 'Terjadi kesalahan';
                alertDiv.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertDiv.className = 'alert alert-danger';
            alertDiv.textContent = 'Terjadi kesalahan saat memperbarui tugas';
            alertDiv.style.display = 'block';
        });
    });

    function confirmDeleteTask(taskId, taskTitle, studentName, hasSubmission) {
        console.log('=== DEBUG: confirmDeleteTask called');
        console.log('=== DEBUG: taskId:', taskId);
        console.log('=== DEBUG: taskTitle:', taskTitle);
        console.log('=== DEBUG: studentName:', studentName);
        console.log('=== DEBUG: hasSubmission:', hasSubmission);
        
        let message = `Apakah yakin menghapus tugas "${taskTitle}" untuk ${studentName}?`;
        
        if (hasSubmission) {
            message += '\n\nPERINGATAN: Tugas ini sudah memiliki pengumpulan dari siswa. Menghapus tugas akan menghilangkan data pengumpulan tersebut.';
        }
        
        showConfirm(message, function() {
            const deleteUrl = '{{ route('mentor.tasks.destroy', 'TASK_ID') }}'.replace('TASK_ID', taskId);
            
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccessToast(data.message);
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showErrorToast('Gagal menghapus tugas: ' + data.message);
                }
            })
            .catch(error => {
                console.error('=== DEBUG ERROR in confirmDeleteTask:', error);
                showErrorToast('Terjadi kesalahan saat menghapus tugas.');
            });
        }, 'Hapus Tugas', 'Ya, Hapus', 'Batal');
    }
</script>
@endsection
