@extends('layouts.app')

@section('title', 'Edit Siswa Magang')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('sekretariat.data-siswa') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <h2 class="dashboard-header">Edit Data Siswa Magang</h2>

    <form action="{{ route('sekretariat.update-siswa', $siswa->id_sertifikat) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong><i class="fas fa-exclamation-triangle me-2"></i>Data gagal disimpan. Harap periksa field berikut:</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3">
            <!-- Row 1: Personal Data -->
            <div class="col-md-6">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Asal Sekolah / Universitas <span class="text-danger">*</span></label>
                
                <!-- Searchable Dropdown Container -->
                <div class="custom-dropdown-container position-relative" id="instansiDropdownContainer">
                    <div class="custom-dropdown-trigger form-control d-flex justify-content-between align-items-center cursor-pointer bg-white" id="dropdownTrigger" style="cursor: pointer; min-height: 42px;">
                        <div class="d-flex align-items-center text-truncate">
                            <span id="selectedLogoPreview" class="me-2 d-inline-flex align-items-center">
                                <i class="fas fa-university text-muted"></i>
                            </span>
                            <span id="selectedOptionText" class="text-muted text-truncate">-- Pilih Sekolah/Universitas --</span>
                        </div>
                        <i class="fas fa-chevron-down text-muted ms-2"></i>
                    </div>

                    <div class="custom-dropdown-menu shadow-lg border rounded-3 p-2 bg-white" id="dropdownMenu" style="display: none; position: absolute; z-index: 1050; width: 100%; left: 0; margin-top: 5px;">
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="dropdownSearchInput" class="form-control border-start-0" placeholder="Cari sekolah/universitas..." autocomplete="off">
                        </div>
                        <div class="dropdown-options-list" style="max-height: 220px; overflow-y: auto;">
                            @foreach($instansis as $ins)
                                <div class="dropdown-option-item p-2 d-flex align-items-center cursor-pointer rounded" 
                                     data-id="{{ $ins->id }}" 
                                     data-nama="{{ $ins->nama }}" 
                                     data-logo="{{ $ins->getLogoUrl() ?? '' }}"
                                     style="cursor: pointer; transition: background 0.15s ease;">
                                    @if($ins->getLogoUrl())
                                        <img src="{{ $ins->getLogoUrl() }}" alt="Logo" class="rounded-circle me-2" style="width: 26px; height: 26px; object-fit: cover; border: 1px solid #dee2e6;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2 border" style="width: 26px; height: 26px;">
                                            <i class="fas fa-university text-muted" style="font-size: 12px;"></i>
                                        </div>
                                    @endif
                                    <span class="text-truncate">{{ $ins->nama }}</span>
                                </div>
                            @endforeach
                            <div class="dropdown-option-item p-2 d-flex align-items-center cursor-pointer border-top text-primary fw-600 rounded mt-1" 
                                 data-id="lainnya" 
                                 data-nama="Lainnya" 
                                 data-logo=""
                                 style="cursor: pointer;">
                                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center me-2 border border-primary" style="width: 26px; height: 26px;">
                                    <i class="fas fa-plus text-primary" style="font-size: 12px;"></i>
                                </div>
                                <span>Lainnya / Sekolah Tidak Terdaftar</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden input for instansi_id -->
                <input type="hidden" name="instansi_id" id="instansi_id" value="{{ old('instansi_id', $siswa->instansi_id ?? '') }}">

                <!-- Hidden actual input for asal_sekolah submitted to backend -->
                <input type="hidden" name="asal_sekolah" id="asal_sekolah" value="{{ old('asal_sekolah', $siswa->asal_sekolah ?? '') }}">

                <!-- Conditional manual input for asal_sekolah if 'lainnya' -->
                <div class="mt-2" id="manualInputContainer" style="display: none;">
                    <label for="asal_sekolah_manual" class="form-label small text-muted">Ketik Nama Sekolah Secara Manual <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="asal_sekolah_manual" placeholder="Ketik nama lengkap sekolah/universitas" autocomplete="off">
                </div>

                <!-- School & Logo Live Dynamic Feedback Card -->
                <div id="schoolLogoPreviewBox" class="mt-2 p-2 px-3 rounded-3 border bg-light d-flex align-items-center justify-content-between" style="font-size: 0.85rem;">
                    <div class="d-flex align-items-center text-truncate me-2">
                        <div id="logoPreviewContainer" class="me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; flex-shrink: 0;">
                            <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fas fa-university text-muted"></i>
                            </div>
                        </div>
                        <div class="text-truncate">
                            <div class="fw-semibold text-dark text-truncate" id="logoPreviewSchoolName">Belum ada sekolah dipilih</div>
                            <small class="text-muted" id="logoPreviewStatus">Pilih sekolah dari daftar dropdown</small>
                        </div>
                    </div>
                    <span class="badge bg-secondary-subtle text-secondary border" id="logoPreviewBadge">Default</span>
                </div>

                @error('asal_sekolah')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
                @error('instansi_id')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            <!-- Row 2 -->
            <div class="col-md-6">
                <label class="form-label">NIM/NIS</label>
                <input type="text" name="nim_nis" class="form-control" value="{{ old('nim_nis', $siswa->nim_nis) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jurusan</label>
                <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan', $siswa->jurusan) }}" required>
            </div>

            <!-- Row 3 -->
            <div class="col-md-6">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', $siswa->tgl_lahir) }}" required>
            </div>

            <!-- Row 4: Internship Data -->
            <div class="col-md-6">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" class="form-control" value="{{ old('tgl_mulai', $siswa->tgl_mulai) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" class="form-control" value="{{ old('tgl_selesai', $siswa->tgl_selesai) }}" required>
            </div>

            <!-- Row 5 -->
            <div class="col-md-6">
                <label class="form-label">Bidang</label>
                <select name="bidang" class="form-select" required>
                    <option value="">Pilih Bidang</option>
                    @foreach($bidang as $b)
                        <option value="{{ $b }}" {{ old('bidang', $siswa->bidang) == $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Akun Mentor / Pembimbing</label>
                <select name="created_id" class="form-select" required>
                    <option value="">Pilih Akun Mentor</option>
                    @foreach($mentors as $mentor)
                        <option value="{{ $mentor->id }}" {{ old('created_id', $siswa->created_id) == $mentor->id ? 'selected' : '' }}>
                            {{ $mentor->nama ?? $mentor->username }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Row 6: Mentor Data -->
            @php
                $defaultNmPembimbing = $siswa->nm_pembimbing ?: $siswa->pembimbing_lapangan;
                $defaultNikPembimbing = $siswa->nik_pembimbing ?: $siswa->nip_pembimbing_lapangan;

                $defaultNmKabid = (!empty($siswa->nm_kadis) && !str_contains(strtolower($siswa->nm_kadis), 'buddy') && !str_contains(strtolower($siswa->nm_kadis), 'supriyanto'))
                    ? $siswa->nm_kadis
                    : $siswa->nama_kabid;

                $defaultNipKabid = (!empty($siswa->nip_kadis) && !str_contains($siswa->nip_kadis, '19711203') && !str_contains($siswa->nip_kadis, '19650825'))
                    ? $siswa->nip_kadis
                    : $siswa->nip_kabid;
            @endphp
            <div class="col-md-6">
                <label class="form-label">Nama Pembimbing Lapangan <span class="text-danger">*</span></label>
                <input type="text" name="nm_pembimbing" class="form-control" value="{{ old('nm_pembimbing', $defaultNmPembimbing) }}" placeholder="Nama pembimbing lapangan" required>
                <small class="text-muted">Nama Pembimbing Lapangan siswa.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">NIP Pembimbing Lapangan</label>
                <input type="text" name="nik_pembimbing" class="form-control" value="{{ old('nik_pembimbing', $defaultNikPembimbing) }}" placeholder="NIP Pembimbing / -">
            </div>

            <!-- Row 7 -->
            <div class="col-md-6">
                <label class="form-label">Jabatan Pembimbing</label>
                <input type="text" name="jabatan_pembimbing" class="form-control" value="{{ old('jabatan_pembimbing', $siswa->jabatan_pembimbing ?: 'Pembimbing Lapangan') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status Persetujuan Pembimbing</label>
                <select name="aprove_pembimbing" class="form-select" required>
                    <option value="1" {{ old('aprove_pembimbing', $siswa->aprove_pembimbing) == 1 ? 'selected' : '' }}>Disetujui</option>
                    <option value="0" {{ old('aprove_pembimbing', $siswa->aprove_pembimbing) == 0 ? 'selected' : '' }}>Belum Disetujui</option>
                </select>
            </div>

            <!-- Row 7: Data Kepala Dinas -->
            <h5 class="mt-4 mb-3">Data Kepala Dinas</h5>
            <div class="col-md-6">
                <label class="form-label">Tanggal Sertifikat</label>
                <input type="date" name="tgl_sertifikat" class="form-control" value="{{ old('tgl_sertifikat', $siswa->tgl_sertifikat) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">No. Sertifikat (Opsional)</label>
                <input type="text" name="no_sertifikat" class="form-control" value="{{ old('no_sertifikat', $siswa->no_sertifikat) }}">
            </div>

            <!-- Row -->
            <div class="col-md-6">
                <label class="form-label">Status Kepala Dinas</label>
                <select name="sts_kadis" class="form-select" required>
                    <option value="1" {{ old('sts_kadis', $siswa->sts_kadis) == 1 || is_null($siswa->sts_kadis) ? 'selected' : '' }}>Definitif</option>
                    <option value="0" {{ old('sts_kadis', $siswa->sts_kadis) === 0 ? 'selected' : '' }}>Plt</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Kepala Dinas <span class="text-danger">*</span></label>
                <input type="text" name="nm_kadis" class="form-control" value="{{ old('nm_kadis', $siswa->nm_kadis ?? \App\Constants\BidangConstants::KADIS_NAMA) }}" required>
                <small class="text-muted">Kepala Dinas Komunikasi dan Informatika permanen.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">NIP Kepala Dinas <span class="text-danger">*</span></label>
                <input type="text" name="nip_kadis" class="form-control" value="{{ old('nip_kadis', $siswa->nip_kadis ?? \App\Constants\BidangConstants::KADIS_NIP) }}" required>
                <small class="text-muted">NIP Kepala Dinas permanen.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jabatan Pegawai</label>
                <select name="jabatan_pegawai" class="form-select" required>
                    <option value="Kepala Dinas Komunikasi dan Informatika" {{ old('jabatan_pegawai', $siswa->jabatan_pegawai) == 'Kepala Dinas Komunikasi dan Informatika' ? 'selected' : '' }}>Kepala Dinas Komunikasi dan Informatika</option>
                    <option value="Kepala Bidang Informatika" {{ old('jabatan_pegawai', $siswa->jabatan_pegawai) == 'Kepala Bidang Informatika' ? 'selected' : '' }}>Kepala Bidang Informatika</option>
                    <option value="Kepala Bidang Statistik dan Persandian" {{ old('jabatan_pegawai', $siswa->jabatan_pegawai) == 'Kepala Bidang Statistik dan Persandian' ? 'selected' : '' }}>Kepala Bidang Statistik dan Persandian</option>
                    <option value="Kepala Bidang Informasi dan Komunikasi Publik" {{ old('jabatan_pegawai', $siswa->jabatan_pegawai) == 'Kepala Bidang Informasi dan Komunikasi Publik' ? 'selected' : '' }}>Kepala Bidang Informasi dan Komunikasi Publik</option>
                    <option value="Sekretaris Dinas" {{ old('jabatan_pegawai', $siswa->jabatan_pegawai) == 'Sekretaris Dinas' ? 'selected' : '' }}>Sekretaris Dinas</option>
                </select>
            </div>

            <!-- Row 11 -->
            <div class="col-md-6">
                <label class="form-label">Status Persetujuan Pegawai</label>
                <select name="approve_kadis" class="form-select" required>
                    <option value="1" {{ old('approve_kadis', $siswa->approve_kadis) == 1 ? 'selected' : '' }}>Disetujui</option>
                    <option value="0" {{ old('approve_kadis', $siswa->approve_kadis) == 0 ? 'selected' : '' }}>Belum Disetujui</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status Sertifikat Diambil</label>
                <select name="sts_diambil" class="form-select" required>
                    <option value="1" {{ old('sts_diambil', $siswa->sts_diambil) == 1 ? 'selected' : '' }}>Diambil</option>
                    <option value="0" {{ old('sts_diambil', $siswa->sts_diambil) == 0 ? 'selected' : '' }}>Belum Diambil</option>
                </select>
            </div>

            <!-- Row 12 -->
            <div class="col-md-12">
                <label class="form-label">Nama Pejabat Penandatangan</label>
                <input type="text" name="pejabat_ttd" class="form-control" value="{{ old('pejabat_ttd', $siswa->pejabat_ttd) }}" placeholder="Nama lengkap pejabat yang menandatangani sertifikat">
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('sekretariat.data-siswa') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-1"></i> Batal
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<style>
    .dashboard-header {
        font-size: 1.8rem;
        font-weight: 600;
        color: #f8f9fa;
        text-align: center;
        position: relative;
        margin-bottom: 2rem;
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

    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
    }

    .btn {
        border-radius: 8px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-success {
        background-color: #38b000;
        border-color: #38b000;
    }

    .btn-success:hover {
        background-color: #2d9200;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownTrigger = document.getElementById('dropdownTrigger');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownSearchInput = document.getElementById('dropdownSearchInput');
    const selectedOptionText = document.getElementById('selectedOptionText');
    const selectedLogoPreview = document.getElementById('selectedLogoPreview');
    const instansiIdInput = document.getElementById('instansi_id');
    const asalSekolahInput = document.getElementById('asal_sekolah');
    const manualInputContainer = document.getElementById('manualInputContainer');
    const manualSchoolInput = document.getElementById('asal_sekolah_manual');
    const dropdownOptions = document.querySelectorAll('.dropdown-option-item');
    const logoPreviewContainer = document.getElementById('logoPreviewContainer');
    const logoPreviewSchoolName = document.getElementById('logoPreviewSchoolName');
    const logoPreviewStatus = document.getElementById('logoPreviewStatus');
    const logoPreviewBadge = document.getElementById('logoPreviewBadge');

    function updateLogoPreview(nama, logoUrl, isRegistered) {
        if (selectedLogoPreview) {
            if (logoUrl) {
                selectedLogoPreview.innerHTML = `<img src="${logoUrl}" alt="Logo" class="rounded-circle" style="width: 22px; height: 22px; object-fit: cover;">`;
            } else {
                selectedLogoPreview.innerHTML = `<i class="fas fa-university text-muted me-1"></i>`;
            }
        }

        if (logoPreviewContainer && logoPreviewSchoolName && logoPreviewStatus && logoPreviewBadge) {
            if (!nama) {
                logoPreviewContainer.innerHTML = `<div class="rounded-circle bg-white border d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="fas fa-university text-muted"></i></div>`;
                logoPreviewSchoolName.textContent = 'Belum ada sekolah dipilih';
                logoPreviewStatus.textContent = 'Pilih sekolah dari daftar dropdown di atas';
                logoPreviewBadge.className = 'badge bg-light text-muted border';
                logoPreviewBadge.textContent = 'Belum Dipilih';
                return;
            }

            logoPreviewSchoolName.textContent = nama;

            if (logoUrl) {
                logoPreviewContainer.innerHTML = `<img src="${logoUrl}" alt="${nama}" class="rounded-circle border shadow-sm" style="width: 36px; height: 36px; object-fit: cover;">`;
            } else {
                logoPreviewContainer.innerHTML = `<div class="rounded-circle bg-white border d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px;"><i class="fas fa-university text-primary"></i></div>`;
            }

            if (isRegistered) {
                logoPreviewStatus.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>Logo & data terhubung ke Master Instansi';
                logoPreviewBadge.className = 'badge bg-success-subtle text-success border border-success-subtle';
                logoPreviewBadge.textContent = 'Terdaftar';
            } else {
                logoPreviewStatus.innerHTML = '<i class="fas fa-info-circle text-warning me-1"></i>Input manual (belum ada logo di Master Instansi)';
                logoPreviewBadge.className = 'badge bg-warning-subtle text-warning border border-warning-subtle';
                logoPreviewBadge.textContent = 'Manual';
            }
        }
    }

    if (dropdownTrigger && dropdownMenu) {
        dropdownTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = dropdownMenu.style.display === 'block';
            dropdownMenu.style.display = isOpen ? 'none' : 'block';
            if (!isOpen && dropdownSearchInput) {
                dropdownSearchInput.value = '';
                filterOptions('');
                dropdownSearchInput.focus();
            }
        });

        document.addEventListener('click', function() {
            dropdownMenu.style.display = 'none';
        });

        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    if (dropdownSearchInput) {
        dropdownSearchInput.addEventListener('input', function() {
            filterOptions(this.value.toLowerCase());
        });
    }

    function filterOptions(searchTerm) {
        dropdownOptions.forEach(option => {
            const text = option.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                option.style.display = 'flex';
            } else {
                option.style.display = 'none';
            }
        });
    }

    dropdownOptions.forEach(option => {
        option.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f0f4ff';
        });
        option.addEventListener('mouseleave', function() {
            if (!this.classList.contains('selected')) {
                this.style.backgroundColor = '';
            }
        });

        option.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            const logo = this.getAttribute('data-logo') || '';

            dropdownOptions.forEach(opt => {
                opt.classList.remove('selected');
                opt.style.backgroundColor = '';
            });
            this.classList.add('selected');
            this.style.backgroundColor = '#e8eeff';

            if (id === 'lainnya') {
                instansiIdInput.value = '';
                manualInputContainer.style.display = 'block';
                selectedOptionText.textContent = 'Lainnya / Sekolah Tidak Terdaftar';
                selectedOptionText.classList.remove('text-muted');
                asalSekolahInput.value = manualSchoolInput.value.trim();
                manualSchoolInput.focus();
                updateLogoPreview(manualSchoolInput.value || 'Sekolah Tidak Terdaftar', '', false);
            } else {
                instansiIdInput.value = id;
                asalSekolahInput.value = nama;
                if (manualSchoolInput) manualSchoolInput.value = nama;
                manualInputContainer.style.display = 'none';
                selectedOptionText.textContent = nama;
                selectedOptionText.classList.remove('text-muted');
                updateLogoPreview(nama, logo, true);
            }

            if (dropdownMenu) dropdownMenu.style.display = 'none';
        });
    });

    if (manualSchoolInput) {
        manualSchoolInput.addEventListener('input', function() {
            asalSekolahInput.value = this.value;
            updateLogoPreview(this.value.trim() || 'Sekolah Tidak Terdaftar', '', false);
        });
    }

    // Initialize existing value
    const currentInstansiId = instansiIdInput ? instansiIdInput.value : '';
    const currentAsalSekolah = asalSekolahInput ? asalSekolahInput.value : '';

    let matched = false;
    if (currentInstansiId) {
        const matchingOption = Array.from(dropdownOptions).find(opt => opt.getAttribute('data-id') === currentInstansiId);
        if (matchingOption) {
            matchingOption.classList.add('selected');
            matchingOption.style.backgroundColor = '#e8eeff';
            const nama = matchingOption.getAttribute('data-nama');
            const logo = matchingOption.getAttribute('data-logo') || '';
            selectedOptionText.textContent = nama;
            selectedOptionText.classList.remove('text-muted');
            asalSekolahInput.value = nama;
            manualInputContainer.style.display = 'none';
            updateLogoPreview(nama, logo, true);
            matched = true;
        }
    }

    if (!matched && currentAsalSekolah) {
        const matchingByName = Array.from(dropdownOptions).find(opt => {
            const optNama = opt.getAttribute('data-nama');
            return optNama && optNama.toLowerCase().trim() === currentAsalSekolah.toLowerCase().trim();
        });

        if (matchingByName) {
            matchingByName.classList.add('selected');
            matchingByName.style.backgroundColor = '#e8eeff';
            const id = matchingByName.getAttribute('data-id');
            const nama = matchingByName.getAttribute('data-nama');
            const logo = matchingByName.getAttribute('data-logo') || '';
            instansiIdInput.value = id;
            selectedOptionText.textContent = nama;
            selectedOptionText.classList.remove('text-muted');
            asalSekolahInput.value = nama;
            manualInputContainer.style.display = 'none';
            updateLogoPreview(nama, logo, true);
            matched = true;
        } else {
            const lainnyaOption = Array.from(dropdownOptions).find(opt => opt.getAttribute('data-id') === 'lainnya');
            if (lainnyaOption) {
                lainnyaOption.classList.add('selected');
                lainnyaOption.style.backgroundColor = '#e8eeff';
            }
            selectedOptionText.textContent = 'Lainnya / Sekolah Tidak Terdaftar';
            selectedOptionText.classList.remove('text-muted');
            manualInputContainer.style.display = 'block';
            if (manualSchoolInput) manualSchoolInput.value = currentAsalSekolah;
            asalSekolahInput.value = currentAsalSekolah;
            updateLogoPreview(currentAsalSekolah, '', false);
        }
    }
});
</script>
@endsection
