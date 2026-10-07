@extends('layouts.app')

@section('title', 'Tambah Sertifikat')

@section('content')
    <div class="container py-5">
        @php
            $currentFrom = request('from', old('from'));
            $formTitle = 'Tambah Siswa / Mahasiswa PKL';
            if ($currentFrom === 'sekolah') {
                $formTitle = 'Tambah Data Lembaga Pendidikan';
            }
        @endphp
        <h2 class="dashboard-header">{{ $formTitle }}</h2>

        <!-- Progress Bar -->
        <div class="progress mb-4">
            <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25"
                aria-valuemin="0" aria-valuemax="100">Langkah 1 dari 4</div>
        </div>

        <form action="{{ route('mentor.store') }}" method="POST" novalidate id="createForm">
            @csrf
            <input type="hidden" name="from" value="{{ request('from', old('from')) }}">

            {{-- Tentukan step awal berdasarkan field mana yang error --}}
            @php
                $initialStep = 1;
                if ($errors->hasAny(['tgl_mulai', 'tgl_selesai', 'tgl_sertifikat', 'no_sertifikat'])) {
                    $initialStep = 2;
                } elseif ($errors->hasAny(['nm_pembimbing', 'nik_pembimbing', 'jabatan_pembimbing', 'bidang', 'aprove_pembimbing'])) {
                    $initialStep = 3;
                } elseif ($errors->hasAny(['sts_kadis', 'nm_kadis', 'nip_kadis', 'jabatan_pegawai', 'approve_kadis', 'sts_diambil', 'pejabat_ttd'])) {
                    $initialStep = 4;
                }
            @endphp
            <input type="hidden" id="initialStep" value="{{ $initialStep }}">

            {{-- Error Summary --}}
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

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <!-- Step 1: Data Pribadi -->
            <div id="step1" class="form-step">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Data Pribadi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                                    value="{{ old('nama_lengkap') }}" required>
                                @error('nama_lengkap')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
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
                                <input type="hidden" name="instansi_id" id="instansi_id" value="{{ old('instansi_id') }}">

                                <!-- Hidden actual input for asal_sekolah submitted to backend -->
                                <input type="hidden" name="asal_sekolah" id="asal_sekolah" value="{{ old('asal_sekolah') }}">

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
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="nim_nis" class="form-label">NIM/NIS</label>
                                <input type="text" name="nim_nis" id="nim_nis" class="form-control"
                                    value="{{ old('nim_nis') }}" required>
                                @error('nim_nis')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                                    value="{{ old('tempat_lahir') }}" required>
                                @error('tempat_lahir')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control"
                                    value="{{ old('tgl_lahir') }}" required>
                                @error('tgl_lahir')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <input type="text" name="jurusan" id="jurusan" class="form-control"
                                    value="{{ old('jurusan') }}" required>
                                @error('jurusan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            @php
                                $backRoute = route('mentor.index');
                                $currentFrom = request('from', old('from'));
                                if ($currentFrom === 'siswaaktif') $backRoute = route('dashboard.siswaaktif');
                                elseif ($currentFrom === 'siswalulus') $backRoute = route('dashboard.siswalulus');
                                elseif ($currentFrom === 'sekolah') $backRoute = route('dashboard.sekolah');
                                elseif ($currentFrom === 'menunggumagang') $backRoute = route('dashboard.menunggumagang');
                            @endphp
                            <a class="btn btn-outline-secondary" href="{{ $backRoute }}">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                                Lanjut <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Data Sertifikat -->
            <div id="step2" class="form-step" style="display: none;">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Data Sertifikat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control"
                                    value="{{ old('tgl_mulai', $defaults['tgl_mulai'] ?? '') }}" required>
                                @error('tgl_mulai')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="tgl_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control"
                                    value="{{ old('tgl_selesai', $defaults['tgl_selesai'] ?? '') }}" required>
                                @error('tgl_selesai')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="tgl_sertifikat" class="form-label">Tanggal Sertifikat</label>
                                <input type="date" name="tgl_sertifikat" id="tgl_sertifikat" class="form-control"
                                    value="{{ old('tgl_sertifikat', $defaults['tgl_sertifikat'] ?? '') }}" required>
                                @error('tgl_sertifikat')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="no_sertifikat" class="form-label">No. Sertifikat <span class="text-muted">(Opsional)</span></label>
                            <input type="text" name="no_sertifikat" id="no_sertifikat" class="form-control"
                                value="{{ old('no_sertifikat') }}">
                            @error('no_sertifikat')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="previousStep(1)">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep(3)">
                                Lanjut <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Data Pembimbing -->
            <div id="step3" class="form-step" style="display: none;">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Data Pembimbing</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nm_pembimbing" class="form-label">Nama Pembimbing Lapangan <span class="text-danger">*</span></label>
                                <input type="text" name="nm_pembimbing" id="nm_pembimbing" class="form-control"
                                    value="{{ old('nm_pembimbing', $defaults['nm_pembimbing'] ?? (auth()->user()->role === 'mentor' && !str_contains(strtolower(auth()->user()->nama ?? ''), 'administrator') ? auth()->user()->nama : '')) }}" placeholder="Nama pembimbing lapangan" required>
                                <small class="text-muted">Nama Pembimbing Lapangan siswa.</small>
                                @error('nm_pembimbing')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="nik_pembimbing" class="form-label">NIP Pembimbing Lapangan</label>
                                <input type="text" name="nik_pembimbing" id="nik_pembimbing" class="form-control"
                                    value="{{ old('nik_pembimbing') }}" placeholder="NIP Pembimbing / -">
                                @error('nik_pembimbing')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="jabatan_pembimbing" class="form-label">Jabatan Pembimbing</label>
                                <input type="text" name="jabatan_pembimbing" id="jabatan_pembimbing"
                                    class="form-control" value="{{ old('jabatan_pembimbing') }}" required>
                                @error('jabatan_pembimbing')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="bidang" class="form-label">Bidang</label>
                                <select name="bidang" id="bidang" class="form-select" required>
                                    <option value="">Pilih Bidang</option>
                                    @foreach(\App\Constants\BidangConstants::getValidBidang() as $b)
                                        <option value="{{ $b }}"
                                            {{ old('bidang') == $b ? 'selected' : '' }}>
                                            {{ $b }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bidang')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="aprove_pembimbing" class="form-label">Status Persetujuan Pembimbing</label>
                                <select name="aprove_pembimbing" id="aprove_pembimbing" class="form-select" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1" {{ old('aprove_pembimbing', $defaults['aprove_pembimbing'] ?? '') == '1' ? 'selected' : '' }}>
                                        Disetujui
                                    </option>
                                    <option value="0" {{ old('aprove_pembimbing', $defaults['aprove_pembimbing'] ?? '') == '0' ? 'selected' : '' }}>
                                        Belum Disetujui
                                    </option>
                                </select>
                                @error('aprove_pembimbing')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="previousStep(2)">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep(4)">
                                Lanjut <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Data Kepala Bidang -->
            <div id="step4" class="form-step" style="display: none;">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Data Kepala Bidang</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="sts_kadis" class="form-label">Status Kepala Dinas</label>
                                <select name="sts_kadis" id="sts_kadis" class="form-select" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1" {{ old('sts_kadis') == '1' ? 'selected' : '' }}>
                                        Definitif
                                    </option>
                                    <option value="0" {{ old('sts_kadis') == '0' ? 'selected' : '' }}>
                                        Plt
                                    </option>
                                </select>
                                @error('sts_kadis')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="nm_kadis" class="form-label">Nama Kepala Dinas <span class="text-danger">*</span></label>
                                <input type="text" name="nm_kadis" id="nm_kadis" class="form-control"
                                    value="{{ old('nm_kadis', \App\Constants\BidangConstants::KADIS_NAMA) }}" required>
                                <small class="text-muted">Kepala Dinas Komunikasi dan Informatika permanen.</small>
                                @error('nm_kadis')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nip_kadis" class="form-label">NIP Kepala Dinas <span class="text-danger">*</span></label>
                                <input type="text" name="nip_kadis" id="nip_kadis" class="form-control"
                                    value="{{ old('nip_kadis', \App\Constants\BidangConstants::KADIS_NIP) }}" required>
                                <small class="text-muted">NIP Kepala Dinas permanen.</small>
                                @error('nip_kadis')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        
                        <div class="col-md-6">
                        <label for="jabatan_pegawai" class="form-label">Jabatan Pegawai</label>
                        <select name="jabatan_pegawai" id="jabatan_pegawai" class="form-select" required>
                            <option value="">Pilih Jabatan</option>

                            <option value="Kepala Dinas Komunikasi dan Informatika" 
                                {{ old('jabatan_pegawai') == 'Kepala Dinas Komunikasi dan Informatika' ? 'selected' : '' }}>
                                Kepala Dinas Komunikasi dan Informatika
                            </option>

                            <option value="Kepala Bidang Informatika" 
                                {{ old('jabatan_pegawai') == 'Kepala Bidang Informatika' ? 'selected' : '' }}>
                                Kepala Bidang Informatika
                            </option>

                            <option value="Kepala Bidang Statistik dan Persandian" 
                                {{ old('jabatan_pegawai') == 'Kepala Bidang Statistik dan Persandian' ? 'selected' : '' }}>
                                Kepala Bidang Statistik dan Persandian
                            </option>

                            <option value="Kepala Bidang Informasi dan Komunikasi Publik" 
                                {{ old('jabatan_pegawai') == 'Kepala Bidang Informasi dan Komunikasi Publik' ? 'selected' : '' }}>
                                Kepala Bidang Informasi dan Komunikasi Publik
                            </option>

                            <option value="Sekretaris Dinas" 
                                {{ old('jabatan_pegawai') == 'Sekretaris Dinas' ? 'selected' : '' }}>
                                Sekretaris Dinas
                            </option>
                        </select>
                    </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="approve_kadis" class="form-label">Status Persetujuan Pegawai</label>
                                <select name="approve_kadis" id="approve_kadis" class="form-select" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1" {{ old('approve_kadis', $defaults['approve_kadis'] ?? '') == '1' ? 'selected' : '' }}>
                                        Disetujui
                                    </option>
                                    <option value="0" {{ old('approve_kadis', $defaults['approve_kadis'] ?? '') == '0' ? 'selected' : '' }}>
                                        Belum Disetujui
                                    </option>
                                </select>
                                @error('approve_kadis')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sts_diambil" class="form-label">Status Sertifikat Diambil</label>
                                <select name="sts_diambil" id="sts_diambil" class="form-select" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1" {{ old('sts_diambil', $defaults['sts_diambil'] ?? '') == '1' ? 'selected' : '' }}>
                                        Diambil
                                    </option>
                                    <option value="0" {{ old('sts_diambil', $defaults['sts_diambil'] ?? '') == '0' ? 'selected' : '' }}>
                                        Belum Diambil
                                    </option>
                                </select>
                                @error('sts_diambil')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="pejabat_ttd" class="form-label">Pejabat Penandatangan</label>
                                <select name="pejabat_ttd" id="pejabat_ttd" class="form-select" required>
                                    <option value="">Pilih Pejabat</option>
                                    <option value="Kepala Dinas"
                                        {{ old('pejabat_ttd') == 'Kepala Dinas' ? 'selected' : '' }}>
                                        Kepala Dinas
                                    </option>
                                    <option value="Sekretaris Dinas"
                                        {{ old('pejabat_ttd') == 'Sekretaris Dinas' ? 'selected' : '' }}>
                                        Sekretaris Dinas
                                    </option>
                                    <option value="Kabid Bidang Informasi dan Kepentingan Publik"
                                        {{ old('pejabat_ttd') == 'Kabid Bidang Informasi dan Kepentingan Publik' ? 'selected' : '' }}>
                                        Kabid Bidang Informasi dan Kepentingan Publik
                                    </option>
                                    <option value="Kabid Bidang Informatika"
                                        {{ old('pejabat_ttd') == 'Kabid Bidang Informatika' ? 'selected' : '' }}>
                                        Kabid Bidang Informatika
                                    </option>
                                    <option value="Kabid Bidang Statistik dan Persandian"
                                        {{ old('pejabat_ttd') == 'Kabid Bidang Statistik dan Persandian' ? 'selected' : '' }}>
                                        Kabid Bidang Statistik dan Persandian
                                    </option>
                                </select>
                                @error('pejabat_ttd')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="previousStep(3)">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </button>
                            <input type="hidden" name="created_id" value="{{ Auth::user()->id }}">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Custom validation messages dalam bahasa Indonesia
        document.addEventListener('DOMContentLoaded', function() {
            const selectElements = document.querySelectorAll('select[required]');
            selectElements.forEach(function(select) {
                select.addEventListener('invalid', function(e) {
                    if (this.value === '') {
                        this.setCustomValidity('Silakan pilih salah satu opsi dari daftar.');
                    }
                });
                select.addEventListener('change', function() {
                    this.setCustomValidity('');
                });
            });

            // Validasi untuk input required
            const requiredInputs = document.querySelectorAll('input[required], textarea[required]');
            requiredInputs.forEach(function(input) {
                input.addEventListener('invalid', function(e) {
                    if (this.validity.valueMissing) {
                        this.setCustomValidity('Kolom ini wajib diisi.');
                    }
                });
                input.addEventListener('input', function() {
                    this.setCustomValidity('');
                });
            });

        });

        function updateProgressBar(step) {
            const progressBar = document.getElementById('progress-bar');
            const stepTitles = ['Data Pribadi', 'Data Sertifikat', 'Data Pembimbing', 'Data Kepala Dinas'];
            const stepPercentage = (step / 4) * 100;
            progressBar.style.width = stepPercentage + '%';
            progressBar.setAttribute('aria-valuenow', stepPercentage);
            progressBar.innerHTML = `Langkah ${step} dari 4 (${stepTitles[step - 1]})`;
        }

        function validateStep(stepNum) {
            const stepEl = document.getElementById('step' + stepNum);
            const inputs = stepEl.querySelectorAll('input[required], select[required], textarea[required]');
            let valid = true;
            inputs.forEach(function(input) {
                // Reset border
                input.style.borderColor = '';
                if (!input.value.trim()) {
                    input.style.borderColor = '#dc3545';
                    valid = false;
                }
            });
            if (!valid) {
                showWarningToast('Harap isi semua field yang wajib diisi sebelum melanjutkan.');
            }
            return valid;
        }

        function nextStep(step) {
            const currentStep = step - 1;
            if (!validateStep(currentStep)) return;

            document.querySelectorAll('.form-step').forEach((element) => element.style.display = 'none');
            document.getElementById('step' + step).style.display = 'block';
            updateProgressBar(step);
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        function previousStep(step) {
            document.querySelectorAll('.form-step').forEach((element) => element.style.display = 'none');
            document.getElementById('step' + step).style.display = 'block';
            updateProgressBar(step);
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        // Navigasi ke step yang tepat saat ada error validasi (setelah redirect dari server)
        document.addEventListener('DOMContentLoaded', function() {
            const initialStep = parseInt(document.getElementById('initialStep').value) || 1;
            if (initialStep > 1) {
                document.querySelectorAll('.form-step').forEach((el) => el.style.display = 'none');
                document.getElementById('step' + initialStep).style.display = 'block';
                updateProgressBar(initialStep);
            }

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

        // Validasi step 4 saat klik Simpan
        document.getElementById('createForm').addEventListener('submit', function(e) {
            if (!validateStep(4)) {
                e.preventDefault();
                return;
            }
            
            // Remove required attribute from hidden steps to allow form submission
            const hiddenSteps = document.querySelectorAll('.form-step[style*="display: none"]');
            const removedRequired = new Map();
            
            hiddenSteps.forEach(step => {
                const requiredFields = step.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    removedRequired.set(field, field.getAttribute('required'));
                    field.removeAttribute('required');
                });
            });
            
            // Re-add required attributes after a short delay (in case of error)
            setTimeout(() => {
                removedRequired.forEach((value, field) => {
                    if (value) {
                        field.setAttribute('required', value);
                    }
                });
            }, 500);
        });
    </script>

    <style>
        /* Fonts */
        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }

        /* Header Dashboard */
        .dashboard-header {
            font-size: 1.8rem;
            font-weight: 600;
            color: #f8f9fa;
            margin-bottom: 1.5rem;
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
            background-color: #4361ee;
            border-radius: 3px;
        }

        /* Progress Bar */
        .progress {
            height: 10px;
            border-radius: 5px;
            background-color: #e9ecef;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .progress-bar {
            background-color: #4361ee;
            color: white;
            font-size: 0.8rem;
            font-weight: 500;
            line-height: 10px;
            text-align: center;
            transition: width 0.6s ease;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .card-header {
            background-color: #4361ee;
            color: white;
            border-bottom: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .card-header h5 {
            font-size: 1.1rem;
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .card-body {
            padding: 1.5rem;
            background-color: white;
        }

        /* Form Elements */
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
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus, .form-select:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .btn-primary {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        .btn-primary:hover {
            background-color: #3a56d4;
            border-color: #3a56d4;
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
            transform: translateY(-1px);
        }

        .btn-success {
            background-color: #38b000;
            border-color: #38b000;
        }

        .btn-success:hover {
            background-color: #2d9200;
            border-color: #2d9200;
            transform: translateY(-1px);
        }

        /* Text Error */
        .text-danger {
            color: #dc3545;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.25rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.25rem;
            }

            .dashboard-header {
                font-size: 1.5rem;
            }

            .btn {
                padding: 0.5rem 1.25rem;
                font-size: 0.9rem;
            }
        }
    </style>
@endsection