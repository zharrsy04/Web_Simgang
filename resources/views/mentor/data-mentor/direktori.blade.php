@extends('layouts.app')

@section('title', 'Direktori Mentor')

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

    .bidang-section {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
    }

    .bidang-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--primary);
    }

    .mentor-card {
        background: var(--card-bg);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .mentor-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .mentor-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .mentor-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-color);
    }

    .mentor-details {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    .student-count {
        background: var(--primary);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
    }

    .student-list {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
        display: none;
    }

    .student-list.show {
        display: block;
    }

    .student-item {
        padding: 0.5rem;
        border-radius: 6px;
        margin-bottom: 0.5rem;
        background: var(--input-bg);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .student-name {
        font-size: 0.9rem;
        color: var(--text-color);
    }

    .student-details {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: var(--text-muted);
    }

    .expand-icon {
        transition: transform 0.3s ease;
    }

    .expand-icon.rotated {
        transform: rotate(180deg);
    }
</style>

<div class="container">
    <div class="dashboard-header">
        <i class="fas fa-chalkboard-teacher"></i> Direktori Mentor
    </div>

    @foreach($mentorsByBidang as $bidang => $mentors)
        <div class="bidang-section">
            <div class="bidang-title">
                <i class="fas fa-building"></i> {{ $bidang }}
                <span class="student-count" style="margin-left: 0.5rem;">{{ $mentors->count() }} Mentor</span>
            </div>

            @if($mentors->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-user-slash"></i>
                    <p>Tidak ada mentor di bidang ini</p>
                </div>
            @else
                @foreach($mentors as $mentor)
                    <div class="mentor-card">
                        <div class="mentor-header" onclick="toggleStudentList({{ $mentor->id }})">
                            <div>
                                <div class="mentor-name">{{ $mentor->nama }}</div>
                                <div class="mentor-details">
                                    <span><i class="fas fa-user"></i> {{ $mentor->username }}</span>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <span class="student-count">
                                    {{ $mentor->mentorSiswa->count() }} Siswa
                                </span>
                                <i class="fas fa-chevron-down expand-icon" id="icon-{{ $mentor->id }}"></i>
                            </div>
                        </div>

                        <div class="student-list" id="students-{{ $mentor->id }}">
                            @if($mentor->mentorSiswa->isEmpty())
                                <div class="empty-state" style="padding: 1rem;">
                                    <p>Tidak ada siswa bimbingan</p>
                                </div>
                            @else
                                @foreach($mentor->mentorSiswa as $ms)
                                    @if($ms->siswa)
                                    <div class="student-item" id="student-item-{{ $ms->id }}">
                                        <div>
                                            <div class="student-name">{{ $ms->siswa->nama_lengkap }}</div>
                                            <div class="student-details">
                                                <span>{{ $ms->siswa->nim_nis }}</span> • 
                                                <span>{{ $ms->siswa->asal_sekolah }}</span>
                                            </div>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <div style="font-size: 0.8rem; color: var(--text-muted);">
                                                {{ $ms->tanggal_bimbingan->format('d M Y') }}
                                            </div>
                                            <a href="{{ route('mentor.tasks.tugas-siswa', $ms->siswa->id_sertifikat) }}" class="btn btn-sm btn-primary" title="Lihat Tugas">
                                                <i class="fas fa-tasks"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="confirmRemoveStudent({{ $mentor->id }}, '{{ $ms->siswa->id_sertifikat }}', '{{ $ms->siswa->nama_lengkap }}', '{{ $mentor->nama }}')"
                                                    title="Hapus dari bimbingan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    @endforeach
</div>

<script>
    function toggleStudentList(mentorId) {
        const studentList = document.getElementById('students-' + mentorId);
        const icon = document.getElementById('icon-' + mentorId);
        
        studentList.classList.toggle('show');
        icon.classList.toggle('rotated');
    }

    function confirmRemoveStudent(mentorId, siswaId, studentName, mentorName) {
        console.log('=== DEBUG: confirmRemoveStudent called');
        console.log('=== DEBUG: mentorId:', mentorId);
        console.log('=== DEBUG: siswaId:', siswaId);
        console.log('=== DEBUG: studentName:', studentName);
        console.log('=== DEBUG: mentorName:', mentorName);

        showConfirm(`Apakah yakin menghapus ${studentName} dari daftar bimbingan ${mentorName}?`, function() {
            console.log('=== DEBUG: User confirmed, sending request');
            const url = '{{ route('mentor.data-mentor.remove-student') }}';
            console.log('=== DEBUG: URL:', url);

            // Send AJAX request to remove student
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    mentor_id: mentorId,
                    siswa_id: siswaId
                })
            })
            .then(response => {
                console.log('=== DEBUG: Response received, status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('=== DEBUG: Response data:', data);
                if (data.success) {
                    // Show success notification
                    showSuccessToast(data.message);

                    // Reload the page to update the list
                    window.location.reload();
                } else {
                    showErrorToast('Gagal menghapus siswa: ' + data.message);
                }
            })
            .catch(error => {
                console.error('=== DEBUG ERROR:', error);
                showErrorToast('Terjadi kesalahan saat menghapus siswa');
            });
        });
    }
</script>
@endsection
