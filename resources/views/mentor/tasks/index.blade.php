@extends('layouts.app')

@section('title', 'Manajemen Tugas')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-tasks"></i> Daftar Tugas
            </h2>
            <p class="text-muted mt-2">Kelola tugas yang Anda berikan kepada siswa</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('mentor.tasks.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Tugas Baru
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tasks Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            @if($tasks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Judul Tugas</th>
                                <th>Siswa Penerima</th>
                                <th>Bidang</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>
                                        <strong>{{ $task->title }}</strong>
                                        @if($task->description)
                                            <br>
                                            <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $task->recipientStudent->nama_lengkap ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $task->recipientStudent->nim_nis ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $task->student_bidang ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($task->due_date)
                                            <small>{{ $task->due_date->format('d M Y') }}</small>
                                            @if($task->due_date < now() && $task->status === 'pending')
                                                <br>
                                                <span class="badge bg-danger">Overdue</span>
                                            @endif
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
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
                                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $task->created_at->format('d M Y H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('mentor.tasks.show', $task->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('mentor.tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteTask({{ $task->id }}, '{{ addslashes($task->title) }}')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small class="text-muted">Total: {{ $tasks->total() }} tugas</small>
                    {{ $tasks->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Belum ada tugas yang dibuat</p>
                    <a href="{{ route('mentor.tasks.create') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-plus"></i> Buat Tugas Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Info Box -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="alert alert-info">
                <i class="fas fa-lightbulb"></i>
                <strong>Tips:</strong> Tugas akan otomatis muncul di "Ringkasan Tugas" di dashboard siswa. Pastikan deskripsi tugas jelas agar siswa memahami apa yang harus dikerjakan.
            </div>
        </div>
    </div>
</div>

<script>
function deleteTask(taskId, taskTitle) {
    const label = taskTitle ? '"' + taskTitle + '"' : 'tugas ini';
    showConfirm(
        'Tugas ' + label + ' akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.',
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
                    showSuccessToast(data.message || 'Tugas berhasil dihapus.');
                    setTimeout(() => location.reload(), 1500);
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
