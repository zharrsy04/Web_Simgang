@extends('layouts.app')

@section('title', 'Daftar Tugas')

@section('content')
<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
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

    .student-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .student-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .student-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .student-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-color);
    }

    .student-details {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    .btn-view-tasks {
        background: var(--primary);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-view-tasks:hover {
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
</style>

<div class="container">
    <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <i class="fas fa-tasks"></i> Daftar Tugas
        </div>
        <button class="btn btn-primary" onclick="openAddTaskModal()">
            <i class="fas fa-plus"></i> Tambah Tugas
        </button>
    </div>

    <!-- Search and Filter Section -->
    <div style="background: var(--card-bg); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid var(--border-color);">
        <form action="{{ route('mentor.tasks.daftar-tugas') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label for="search" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Cari Nama Siswa</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       class="form-control" 
                       placeholder="Masukkan nama siswa..." 
                       value="{{ $search ?? '' }}">
            </div>
            <div style="flex: 1; min-width: 250px;">
                <label for="bidang" style="font-weight: 500; margin-bottom: 0.5rem; display: block;">Filter Bidang</label>
                <select id="bidang" name="bidang" class="form-control">
                    <option value="">Semua Bidang</option>
                    @foreach(\App\Constants\BidangConstants::getValidBidang() as $b)
                        <option value="{{ $b }}" {{ ($bidangFilter ?? '') === $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('mentor.tasks.daftar-tugas') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
        </form>
    </div>

    @if($studentsByBidang->isEmpty())
        <div class="empty-state">
            <i class="fas fa-user-graduate"></i>
            <p>{{ $search || $bidangFilter ? 'Tidak ada siswa yang sesuai dengan kriteria pencarian.' : 'Anda belum memberikan tugas ke siswa manapun.' }}</p>
        </div>
    @else
        @foreach($studentsByBidang as $bidang => $siswas)
        <div style="margin-bottom: 2rem;">
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
                <span style="background: var(--primary); color:#fff; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight:600;">
                    <i class="fas fa-layer-group"></i> {{ $bidang }}
                </span>
                <span style="color: var(--text-muted); font-size: 0.85rem;">{{ $siswas->count() }} siswa</span>
            </div>
            @foreach($siswas as $siswa)
            <div class="student-card">
                <div class="student-info">
                    <div>
                        <div class="student-name">{{ $siswa->nama_lengkap }}</div>
                        <div class="student-details">
                            <span><i class="fas fa-id-card"></i> {{ $siswa->nim_nis }}</span> •
                            <span><i class="fas fa-school"></i> {{ $siswa->asal_sekolah }}</span> •
                            <span><i class="fas fa-building"></i> {{ $siswa->bidang }}</span>
                        </div>
                    </div>
                    <a href="{{ route('mentor.tasks.tugas-siswa', $siswa->id_sertifikat) }}" class="btn-view-tasks">
                        <i class="fas fa-eye"></i> Lihat Tugas
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    @endif
</div>

<!-- Modal Tambah Tugas -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addTaskModalLabel"><i class="fas fa-plus me-2"></i> Tambah Tugas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTaskForm">
                    <div class="mb-3">
                        <label for="taskTitle" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="taskTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="taskDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="taskDueDate" class="form-label">Tenggat Waktu</label>
                        <input type="date" class="form-control" id="taskDueDate">
                    </div>
                    <div class="mb-3">
                        <label for="taskBidangFilter" class="form-label">Filter Bidang</label>
                        <select class="form-control" id="taskBidangFilter" onchange="loadStudentsByBidang()">
                            <option value="">Semua Bidang</option>
                            @foreach(\App\Constants\BidangConstants::getValidBidang() as $b)
                                <option value="{{ $b }}">{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="taskStudent" class="form-label">Pilih Siswa Penerima Tugas <span class="text-danger">*</span></label>
                        <select class="form-control" id="taskStudent" required>
                            <option value="">-- Pilih Siswa --</option>
                        </select>
                        <small class="text-muted">Pilih siswa dari bidang manapun untuk memberikan tugas lintas bidang</small>
                    </div>
                    <div id="addTaskAlert" class="alert mt-3" style="display:none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="submitAddTaskBtn">Simpan Tugas</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openAddTaskModal() {
        // Reset form
        document.getElementById('addTaskForm').reset();
        document.getElementById('taskBidangFilter').value = '';
        
        if (!studentSelectControl) {
            document.getElementById('taskStudent').innerHTML = '<option value="">-- Pilih Siswa --</option>';
        }
        
        document.getElementById('addTaskAlert').style.display = 'none';
        
        // Load all students
        loadStudentsByBidang();
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('addTaskModal'));
        modal.show();
    }

    let studentSelectControl = null;

    function loadStudentsByBidang() {
        const bidang = document.getElementById('taskBidangFilter').value;
        const studentSelect = document.getElementById('taskStudent');
        
        // Initialize if not exists
        if (!studentSelectControl) {
            studentSelectControl = new TomSelect('#taskStudent', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "-- Pilih Siswa --"
            });
        }
        
        // Show loading state
        studentSelectControl.clear();
        studentSelectControl.clearOptions();
        studentSelectControl.settings.placeholder = "Loading...";
        studentSelectControl.control_input.placeholder = "Loading...";
        studentSelectControl.disable();
        
        fetch(`/mentor/api/students-by-bidang?bidang=${bidang}`)
            .then(response => response.json())
            .then(data => {
                studentSelectControl.clearOptions();
                
                data.students.forEach(student => {
                    studentSelectControl.addOption({
                        value: student.id_sertifikat,
                        text: `${student.nama_lengkap} (${student.bidang})`
                    });
                });
                
                studentSelectControl.settings.placeholder = "-- Pilih Siswa --";
                studentSelectControl.control_input.placeholder = "-- Pilih Siswa --";
                studentSelectControl.enable();
                studentSelectControl.setValue('');
            })
            .catch(error => {
                console.error('Error loading students:', error);
                if (studentSelectControl) {
                    studentSelectControl.enable();
                }
            });
    }

    document.getElementById('submitAddTaskBtn').addEventListener('click', function() {
        const title = document.getElementById('taskTitle').value;
        const description = document.getElementById('taskDescription').value;
        const dueDate = document.getElementById('taskDueDate').value;
        const studentId = document.getElementById('taskStudent').value;
        const alertDiv = document.getElementById('addTaskAlert');

        if (!title || !studentId) {
            alertDiv.className = 'alert alert-danger';
            alertDiv.textContent = 'Judul tugas dan siswa penerima harus diisi';
            alertDiv.style.display = 'block';
            return;
        }

        fetch('{{ route('mentor.tasks.daftar-tugas') }}/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                title: title,
                description: description,
                due_date: dueDate,
                sertifikat_pkl_id: studentId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertDiv.className = 'alert alert-success';
                alertDiv.textContent = data.message;
                alertDiv.style.display = 'block';
                
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addTaskModal'));
                    modal.hide();
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
            alertDiv.textContent = 'Terjadi kesalahan saat menyimpan tugas';
            alertDiv.style.display = 'block';
        });
    });
</script>
@endsection

@section('scripts')
<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
@endsection
