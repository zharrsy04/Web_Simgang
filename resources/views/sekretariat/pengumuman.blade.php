@extends('layouts.app')

@section('title', 'Kelola Pengumuman')

@section('content')
<div class="container-fluid py-5">
    <div class="mb-4">
        <a href="{{ route('sekretariat.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <h2 class="dashboard-header mb-4">Kelola Pengumuman</h2>

    <!-- Alert Success -->
    <div id="alertSuccess" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
        <i class="fas fa-check-circle me-2"></i>
        <span id="successMessage"></span>
        <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'"></button>
    </div>

    <!-- Alert Error -->
    <div id="alertError" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
        <i class="fas fa-exclamation-circle me-2"></i>
        <span id="errorMessage"></span>
        <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'"></button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-bullhorn me-2"></i> Daftar Pengumuman
                </h5>
                <button class="btn btn-light btn-sm" onclick="openModal('create')">
                    <i class="fas fa-plus"></i> Tambah Pengumuman
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="announcementsContainer" class="row g-4">
                <!-- Pengumuman akan dimuat di sini via JavaScript -->
            </div>
            <div id="loadingSpinner" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat pengumuman...</p>
            </div>
            <div id="emptyState" class="text-center py-5" style="display: none;">
                <i class="fas fa-bell-slash" style="font-size: 3rem; color: #ccc;"></i>
                <p class="mt-3 text-muted">Belum ada pengumuman</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Pengumuman -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                <h5 class="modal-title" id="modalTitle">Tambah Pengumuman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="announcementForm">
                    <input type="hidden" id="announcementId">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe</label>
                        <select class="form-select" id="type">
                            <option value="">Pilih Tipe</option>
                            <option value="info">Info</option>
                            <option value="pengumuman">Pengumuman</option>
                            <option value="warning">Peringatan</option>
                            <option value="penting">Penting</option>
                            <option value="akademik">Akademik</option>
                            <option value="success">Keberhasilan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Konten <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="published_at" class="form-label">Tanggal Publikasi <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="published_at" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveAnnouncement()">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
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

    .announcement-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-left: 4px solid #667eea;
    }

    .announcement-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .announcement-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2d3748;
    }

    .announcement-type {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .type-info { background: #e3f2fd; color: #1976d2; }
    .type-pengumuman { background: #f3e5f5; color: #7b1fa2; }
    .type-warning { background: #fff3e0; color: #f57c00; }
    .type-penting { background: #ffebee; color: #c62828; }
    .type-akademik { background: #e8f5e9; color: #388e3c; }
    .type-success { background: #e8f5e9; color: #2e7d32; }

    .announcement-content {
        color: #4a5568;
        line-height: 1.6;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .announcement-date {
        color: #718096;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .announcement-actions {
        display: flex;
        gap: 10px;
    }

    .btn {
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }
</style>

<script>
let announcementsData = [];
let modalInstance = null;
let currentMode = 'create';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap modal
    modalInstance = new bootstrap.Modal(document.getElementById('announcementModal'));
    
    // Set default date to today
    document.getElementById('published_at').valueAsDate = new Date();
    
    // Load announcements
    loadAnnouncements();
});

function loadAnnouncements() {
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('announcementsContainer').style.display = 'none';
    document.getElementById('emptyState').style.display = 'none';

    fetch('{{ route("announcements.get") }}', {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loadingSpinner').style.display = 'none';
        
        if (data.success && data.data && data.data.length > 0) {
            announcementsData = data.data;
            displayAnnouncements(data.data);
        } else {
            document.getElementById('emptyState').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('loadingSpinner').style.display = 'none';
        showAlert('error', 'Gagal memuat pengumuman');
    });
}

function displayAnnouncements(announcements) {
    const container = document.getElementById('announcementsContainer');
    container.style.display = 'flex';
    container.innerHTML = '';

    announcements.forEach(announcement => {
        const card = document.createElement('div');
        card.className = 'col-md-6 col-lg-4';
        
        const typeClass = announcement.type ? `type-${announcement.type.toLowerCase()}` : 'type-info';
        const publishedDate = announcement.published_at ? new Date(announcement.published_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : 'Tidak ada tanggal';
        
        card.innerHTML = `
            <div class="announcement-card">
                <h6 class="announcement-title">${escapeHtml(announcement.title)}</h6>
                ${announcement.type ? `<span class="announcement-type ${typeClass}">${escapeHtml(announcement.type)}</span>` : ''}
                <p class="announcement-content">${escapeHtml(announcement.content).substring(0, 150)}${announcement.content.length > 150 ? '...' : ''}</p>
                <div class="announcement-date">
                    <i class="fas fa-calendar me-1"></i> ${publishedDate}
                </div>
                <div class="announcement-actions">
                    <button class="btn btn-sm btn-primary" onclick="editAnnouncement(${announcement.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteAnnouncement(${announcement.id}, '${escapeHtml(announcement.title)}')">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(card);
    });
}

function openModal(mode) {
    currentMode = mode;
    const form = document.getElementById('announcementForm');
    form.reset();
    
    if (mode === 'create') {
        document.getElementById('modalTitle').textContent = 'Tambah Pengumuman';
        document.getElementById('announcementId').value = '';
        document.getElementById('published_at').valueAsDate = new Date();
    } else {
        document.getElementById('modalTitle').textContent = 'Edit Pengumuman';
    }
    
    modalInstance.show();
}

function editAnnouncement(id) {
    const announcement = announcementsData.find(a => a.id === id);
    if (!announcement) return;
    
    openModal('edit');
    document.getElementById('announcementId').value = announcement.id;
    document.getElementById('title').value = announcement.title;
    document.getElementById('type').value = announcement.type || '';
    document.getElementById('content').value = announcement.content;
    document.getElementById('published_at').value = announcement.published_at ? announcement.published_at.split('T')[0] : '';
}

function saveAnnouncement() {
    const id = document.getElementById('announcementId').value;
    const title = document.getElementById('title').value.trim();
    const type = document.getElementById('type').value;
    const content = document.getElementById('content').value.trim();
    const published_at = document.getElementById('published_at').value;

    if (!title || !content || !published_at) {
        showAlert('error', 'Mohon lengkapi semua field yang wajib diisi');
        return;
    }

    const url = id ? `{{ url('/dashboard/pengumuman/update') }}/${id}` : '{{ route("announcements.store") }}';
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            title: title,
            type: type,
            content: content,
            published_at: published_at
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message || (id ? 'Pengumuman berhasil diperbarui' : 'Pengumuman berhasil ditambahkan'));
            modalInstance.hide();
            loadAnnouncements();
        } else {
            showAlert('error', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat menyimpan pengumuman');
    });
}

function deleteAnnouncement(id, title) {
    if (!confirm(`Apakah Anda yakin ingin menghapus pengumuman "${title}"?`)) {
        return;
    }

    fetch(`{{ url('/dashboard/pengumuman/delete') }}/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message || 'Pengumuman berhasil dihapus');
            loadAnnouncements();
        } else {
            showAlert('error', data.message || 'Gagal menghapus pengumuman');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat menghapus pengumuman');
    });
}

function showAlert(type, message) {
    const alertId = type === 'success' ? 'alertSuccess' : 'alertError';
    const messageId = type === 'success' ? 'successMessage' : 'errorMessage';
    
    const alertEl = document.getElementById(alertId);
    const messageEl = document.getElementById(messageId);
    
    messageEl.textContent = message;
    alertEl.style.display = 'block';
    alertEl.classList.add('show');
    
    setTimeout(() => {
        alertEl.classList.remove('show');
        setTimeout(() => {
            alertEl.style.display = 'none';
        }, 150);
    }, 5000);
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}
</script>
@endsection
