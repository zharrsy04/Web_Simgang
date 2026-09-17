@extends('layouts.app')

@section('title', 'Buat Tugas Baru')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">
                <i class="fas fa-plus-circle"></i> Buat Tugas Baru
            </h2>
            <p class="text-muted mt-2">Berikan tugas kepada siswa di bidang Anda</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('mentor.tasks.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('mentor.tasks.store') }}" method="POST" id="taskForm">
                        @csrf

                        <!-- Bidang Selection -->
                        <div class="mb-4">
                            <label for="bidang" class="form-label fw-bold">Bidang Magang <span class="text-danger">*</span></label>
                            <select id="bidang" name="bidang" class="form-control form-control-lg @error('bidang') is-invalid @enderror" required>
                                <option value="">-- Pilih Bidang --</option>
                                @foreach($mentorBidang as $b)
                                    <option value="{{ $b }}" @if(old('bidang') == $b) selected @endif>
                                        {{ $b }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bidang')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Student Selection -->
                        <div class="mb-4">
                            <label for="sertifikat_pkl_id" class="form-label fw-bold">Siswa Penerima <span class="text-danger">*</span></label>
                            <select id="sertifikat_pkl_id" name="sertifikat_pkl_id" class="form-control form-control-lg @error('sertifikat_pkl_id') is-invalid @enderror" required disabled>
                                <option value="">-- Pilih Siswa --</option>
                            </select>
                            <small class="text-muted d-block mt-2">Pilih bidang terlebih dahulu untuk melihat daftar siswa</small>
                            @error('sertifikat_pkl_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">Judul Tugas <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   placeholder="Contoh: Buat dokumentasi proyek" 
                                   value="{{ old('title') }}" required maxlength="255">
                            @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Deskripsi / Instruksi</label>
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="6" maxlength="5000" placeholder="Jelaskan tugas yang harus dikerjakan siswa...">{{ old('description') }}</textarea>
                            <small class="text-muted d-block mt-1">Gunakan untuk menjelaskan detail tugas, kriteria penilaian, dan instruksi lengkap</small>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Due Date -->
                        <div class="mb-4">
                            <label for="due_date" class="form-label fw-bold">Tanggal Deadline</label>
                            <input type="date" id="due_date" name="due_date" class="form-control form-control-lg @error('due_date') is-invalid @enderror"
                                   value="{{ old('due_date') }}">
                            <small class="text-muted d-block mt-1">Tanggal deadline harus hari ini atau setelahnya</small>
                            @error('due_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-check-circle"></i> Buat Tugas
                            </button>
                            <a href="{{ route('mentor.tasks.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle"></i>
                <strong>Catatan Penting:</strong>
                <ul class="mb-0 mt-2">
                    <li>Hanya siswa yang ditempatkan di bidang yang sama dengan Anda yang dapat menerima tugas</li>
                    <li>Sistem akan memvalidasi bahwa siswa benar-benar berada di bidang yang dipilih</li>
                    <li>Tugas akan ditampilkan di dashboard siswa di bagian "Ringkasan Tugas"</li>
                </ul>
            </div>
        </div>

        <!-- Sidebar Help -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-question-circle text-info"></i> Bantuan
                    </h5>
                    <div class="small">
                        <h6 class="fw-bold mt-3">Apa itu Bidang?</h6>
                        <p class="text-muted">Bidang adalah departemen/divisi tempat siswa magang. Contoh: Pengembangan Web, Data Science, UI/UX Design, dll.</p>

                        <h6 class="fw-bold mt-3">Validasi Bidang</h6>
                        <p class="text-muted">Sistem akan otomatis mengecek bahwa:</p>
                        <ul class="text-muted small">
                            <li>Siswa yang dipilih ditangani oleh Anda</li>
                            <li>Siswa berada di bidang yang sama</li>
                            <li>Data penempatan bidang sudah valid</li>
                        </ul>

                        <h6 class="fw-bold mt-3">Tipe Tugas</h6>
                        <p class="text-muted">Tugas dapat berupa:</p>
                        <ul class="text-muted small">
                            <li>Proyek kecil (1-2 hari)</li>
                            <li>Dokumentasi</li>
                            <li>Analisis atau riset</li>
                            <li>Implementasi fitur</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control-lg, .btn-lg {
        font-size: 1rem;
    }

    .form-label {
        color: #2c3e50;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bidangSelect = document.getElementById('bidang');
    const studentSelect = document.getElementById('sertifikat_pkl_id');

    bidangSelect.addEventListener('change', function() {
        const bidang = this.value;

        // Clear previous options
        studentSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';

        if (!bidang) {
            studentSelect.disabled = true;
            return;
        }

        // Fetch students for this bidang
        fetch(`{{ url('/mentor/tasks/api/bidang') }}/${bidang}/students-placed`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch students');
                return response.json();
            })
            .then(students => {
                studentSelect.disabled = false;

                if (students.length === 0) {
                    studentSelect.innerHTML += '<option value="" disabled>Tidak ada siswa di bidang ini</option>';
                    studentSelect.disabled = true;
                    return;
                }

                students.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id_sertifikat;
                    option.textContent = `${student.nama_lengkap} (${student.nim_nis})`;
                    studentSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                studentSelect.innerHTML += '<option value="" disabled>Gagal memuat siswa</option>';
                studentSelect.disabled = true;
            });
    });

    // Restore selection if form has errors
    @if(old('bidang'))
        bidangSelect.dispatchEvent(new Event('change'));
    @endif
});
</script>
@endsection
