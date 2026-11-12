@extends('layouts.app')

@section('title', 'Edit Sertifikat')

@section('content')
    <div class="container py-5">
        @php
            $currentFrom = request('from', $from ?? old('from'));
            $formTitle = 'Edit Data Siswa / Mahasiswa PKL';
            if ($currentFrom === 'sekolah') {
                $formTitle = 'Edit Data Lembaga Pendidikan';
            }
        @endphp
        <h2 class="dashboard-header">{{ $formTitle }}</h2>

        <!-- Progress Bar -->
        <div class="progress mb-4">
            <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25"
                aria-valuemin="0" aria-valuemax="100">Langkah 1 dari 4</div>
        </div>
        <form action="{{ route('mentor.update', $sertifikat->id_sertifikat) }}" method="POST" novalidate id="editForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="from" value="{{ request('from', $from ?? old('from')) }}">

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
                                    value="{{ old('nama_lengkap', $sertifikat->nama_lengkap) }}" required>
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
                                <input type="hidden" name="instansi_id" id="instansi_id" value="{{ old('instansi_id', $sertifikat->instansi_id ?? '') }}">

                                <!-- Hidden actual input for asal_sekolah submitted to backend -->
                                <input type="hidden" name="asal_sekolah" id="asal_sekolah" value="{{ old('asal_sekolah', $sertifikat->asal_sekolah ?? '') }}">

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
                                    value="{{ old('nim_nis', $sertifikat->nim_nis) }}" required>
                                @error('nim_nis')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                                    value="{{ old('tempat_lahir', $sertifikat->tempat_lahir) }}" required>
                                @error('tempat_lahir')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control"
                                    value="{{ old('tgl_lahir', substr($sertifikat->tgl_lahir,0,10)) }}" required>
                                @error('tgl_lahir')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <input type="text" name="jurusan" id="jurusan" class="form-control"
                                    value="{{ old('jurusan', $sertifikat->jurusan) }}" required>
                                @error('jurusan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            @php
                                $backRoute = route('mentor.index');
                                $currentFrom = request('from', $from ?? old('from'));
                                if ($currentFrom === 'siswalulus') $backRoute = route('dashboard.siswalulus');
                                elseif ($currentFrom === 'siswaaktif') $backRoute = route('dashboard.siswaaktif');
                                elseif ($currentFrom === 'menunggumagang') $backRoute = route('dashboard.menunggumagang');
                                elseif ($currentFrom === 'sekolah') {
                                    $backRoute = auth()->user() && auth()->user()->role === 'sekretariat'
                                        ? route('sekretariat.instansi.index')
                                        : route('dashboard.sekolah');
                                }
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
                                    value="{{ old('tgl_mulai', substr($sertifikat->tgl_mulai,0,10)) }}" required>
                                @error('tgl_mulai')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="tgl_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control"
                                    value="{{ old('tgl_selesai', substr($sertifikat->tgl_selesai,0,10)) }}" required>
                                @error('tgl_selesai')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="tgl_sertifikat" class="form-label">Tanggal Sertifikat</label>
                                <input type="date" name="tgl_sertifikat" id="tgl_sertifikat" class="form-control"
                                    value="{{ old('tgl_sertifikat', substr($sertifikat->tgl_sertifikat,0,10)) }}" required>
                                @error('tgl_sertifikat')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="no_sertifikat" class="form-label">No. Sertifikat <span class="text-muted">(Opsional)</span></label>
                            <input type="text" name="no_sertifikat" id="no_sertifikat" class="form-control"
                                value="{{ old('no_sertifikat', $sertifikat->no_sertifikat) }}">
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
            @php
                $defaultNmPembimbing = $sertifikat->nm_pembimbing ?: $sertifikat->pembimbing_lapangan;
                $defaultNikPembimbing = $sertifikat->nik_pembimbing ?: $sertifikat->nip_pembimbing_lapangan;
            @endphp
            <div id="step3" class="form-step" style="display: none;">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Data Pembimbing Lapangan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="nm_pembimbing" class="form-label">Nama Pembimbing Lapangan <span class="text-danger">*</span></label>
                                <input type="text" name="nm_pembimbing" id="nm_pembimbing" class="form-control"
                                    value="{{ old('nm_pembimbing', $defaultNmPembimbing) }}" placeholder="Nama pembimbing lapangan" required>
                                <small class="text-muted">Nama Pembimbing Lapangan siswa.</small>
                                @error('nm_pembimbing')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="nik_pembimbing" class="form-label">NIP Pembimbing Lapangan</label>
                                <input type="text" name="nik_pembimbing" id="nik_pembimbing" class="form-control"
                                    value="{{ old('nik_pembimbing', $defaultNikPembimbing) }}" placeholder="NIP Pembimbing / -">
                                @error('nik_pembimbing')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="jabatan_pembimbing" class="form-label">Jabatan Pembimbing</label>
                                <input type="text" name="jabatan_pembimbing" id="jabatan_pembimbing"
                                    class="form-control" value="{{ old('jabatan_pembimbing', $sertifikat->jabatan_pembimbing ?: 'Pembimbing Lapangan') }}" required>
                                @error('jabatan_pembimbing')
                                    <small class="text-danger d-block">{{ $message }}</small>
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
                                            {{ old('bidang', $sertifikat->bidang) == $b ? 'selected' : '' }}>
                                            {{ $b }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bidang')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="aprove_pembimbing" class="form-label">Status Persetujuan Pembimbing</label>
                                <select name="aprove_pembimbing" id="aprove_pembimbing" class="form-select">
                                    <option value="1"
                                        {{ old('aprove_pembimbing', $sertifikat->aprove_pembimbing) == 1 ? 'selected' : '' }}>
                                        Disetujui
                                    </option>
                                    <option value="0"
                                        {{ old('aprove_pembimbing', $sertifikat->aprove_pembimbing) == 0 ? 'selected' : '' }}>
                                        Belum Disetujui
                                    </option>
                                </select>
                                @error('aprove_pembimbing')
                                    <small class="text-danger d-block">{{ $message }}</small>
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

            <!-- Step 4: Data Kepala Dinas -->
            <div id="step4" class="form-step" style="display: none;">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Data Kepala Dinas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="pejabat_ttd" class="form-label">Pejabat Penandatangan</label>
                                <select name="pejabat_ttd" id="pejabat_ttd" class="form-select" required>
                                    <option value="">Pilih Pejabat</option>
                                    <option value="Kepala Dinas"
                                        {{ old('pejabat_ttd', $sertifikat->pejabat_ttd) == 'Kepala Dinas' || empty($sertifikat->pejabat_ttd) ? 'selected' : '' }}>
                                        Kepala Dinas
                                    </option> 
                                    <option value="Sekretaris Dinas"
                                        {{ old('pejabat_ttd', $sertifikat->pejabat_ttd) == 'Sekretaris Dinas'? 'selected' : '' }}>
                                        Sekretaris Dinas
                                    </option> 
                                    <option value="Kabid Bidang Informasi dan Kepentingan Publik"
                                        {{ old('pejabat_ttd', $sertifikat->pejabat_ttd) == 'Kabid Bidang Informasi dan Kepentingan Publik'? 'selected' : '' }}>
                                        Kabid Bidang Informasi dan Kepentingan Publik 
                                    </option>
                                    <option value="Kabid Bidang Informatika"
                                        {{ old('pejabat_ttd', $sertifikat->pejabat_ttd) == 'Kabid Bidang Informatika'? 'selected' : '' }}>
                                        Kabid Bidang Informatika
                                    </option>
                                    <option value="Kabid Bidang Statistik dan Persandian"
                                        {{ old('pejabat_ttd', $sertifikat->pejabat_ttd) == 'Kabid Bidang Statistik dan Persandian'? 'selected' : '' }}>
                                        Kabid Bidang Statistik dan Persandian
                                    </option>
                                </select>
                                @error('pejabat_ttd')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="sts_kadis" class="form-label">Status Pejabat</label>
                                <select name="sts_kadis" id="sts_kadis" class="form-select" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1" {{ old('sts_kadis', $sertifikat->sts_kadis) == 1 || is_null($sertifikat->sts_kadis) ? 'selected' : '' }}>
                                        Definitif
                                    </option>
                                    <option value="0" {{ old('sts_kadis', $sertifikat->sts_kadis) === 0 ? 'selected' : '' }}>
                                        Plt
                                    </option>
                                </select>
                                @error('sts_kadis')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="nm_kadis" class="form-label">Nama Kepala Dinas <span class="text-danger">*</span></label>
                                <input type="text" name="nm_kadis" id="nm_kadis" class="form-control"
                                    value="{{ old('nm_kadis', $sertifikat->nm_kadis ?? \App\Constants\BidangConstants::KADIS_NAMA) }}" required>
                                <small class="text-muted">Kepala Dinas Komunikasi dan Informatika permanen.</small>
                                @error('nm_kadis')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nip_kadis" class="form-label">NIP Kepala Dinas <span class="text-danger">*</span></label>
                                <input type="text" name="nip_kadis" id="nip_kadis" class="form-control"
                                    value="{{ old('nip_kadis', $sertifikat->nip_kadis ?? \App\Constants\BidangConstants::KADIS_NIP) }}" required>
                                <small class="text-muted">NIP Kepala Dinas permanen.</small>
                                @error('nip_kadis')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                            <label for="jabatan_pegawai" class="form-label">Kepala Bidang Penempatan</label>
                            <select name="jabatan_pegawai" id="jabatan_pegawai" class="form-select" required>
                                <option value="">Pilih Jabatan</option>

                                <option value="Kepala Dinas Komunikasi dan Informatika"
                                    {{ old('jabatan_pegawai', $sertifikat->jabatan_pegawai) == 'Kepala Dinas Komunikasi dan Informatika' ? 'selected' : '' }}>
                                    Kepala Dinas Komunikasi dan Informatika
                                </option>

                                <option value="Kepala Bidang Informatika"
                                    {{ old('jabatan_pegawai', $sertifikat->jabatan_pegawai) == 'Kepala Bidang Informatika' ? 'selected' : '' }}>
                                    Kepala Bidang Informatika
                                </option>

                                <option value="Kepala Bidang Statistik dan Persandian"
                                    {{ old('jabatan_pegawai', $sertifikat->jabatan_pegawai) == 'Kepala Bidang Statistik dan Persandian' ? 'selected' : '' }}>
                                    Kepala Bidang Statistik dan Persandian
                                </option>

                                <option value="Kepala Bidang Informasi dan Komunikasi Publik"
                                    {{ old('jabatan_pegawai', $sertifikat->jabatan_pegawai) == 'Kepala Bidang Informasi dan Komunikasi Publik' ? 'selected' : '' }}>
                                    Kepala Bidang Informasi dan Komunikasi Publik
                                </option>

                                <option value="Sekretaris Dinas"
                                    {{ old('jabatan_pegawai', $sertifikat->jabatan_pegawai) == 'Sekretaris Dinas' ? 'selected' : '' }}>
                                    Sekretaris Dinas
                                </option>
                            </select>
                        </div>

                            <div class="col-md-6">
                                <label for="nip_jabatan_pegawai" class="form-label">NIP Kepala Bidang</label>
                                <input type="text" name="nip_jabatan_pegawai" id="nip_jabatan_pegawai" class="form-control"
                                    value="{{ old('nip_jabatan_pegawai', $sertifikat->nip_jabatan_pegawai) }}"
                                    placeholder="Masukkan NIP Pejabat (opsional)">
                                <small class="text-muted">Terisi Otomatis untuk Kabid Informatika, Isi Manual Untuk Kabid yang Lainnya.</small>
                                @error('nip_jabatan_pegawai')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="approve_kadis" class="form-label">Status Persetujuan Pegawai</label>
                                <select name="approve_kadis" id="approve_kadis" class="form-select">
                                    <option value="1"
                                        {{ old('approve_kadis', $sertifikat->approve_kadis) == 1 ? 'selected' : '' }}>
                                        Disetujui
                                    </option>
                                    <option value="0"
                                        {{ old('approve_kadis', $sertifikat->approve_kadis) == 0 ? 'selected' : '' }}>
                                        Belum Disetujui
                                    </option>
                                </select>
                                @error('approve_kadis')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sts_diambil" class="form-label">Status Sertifikat Diambil</label>
                                <select name="sts_diambil" id="sts_diambil" class="form-select">
                                    <option value="1"
                                        {{ old('sts_diambil', $sertifikat->sts_diambil) == 1 ? 'selected' : '' }}>
                                        Diambil
                                    </option>
                                    <option value="0"
                                        {{ old('sts_diambil', $sertifikat->sts_diambil) == 0 ? 'selected' : '' }}>
                                        Belum Diambil
                                    </option>
                                </select>
                                @error('sts_diambil')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" onclick="previousStep(3)">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </button>
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
        // ==========================================
        // TOAST NOTIFICATION HELPER
        // ==========================================
        function showToast(message, type = 'danger') {
            // Hapus toast sebelumnya jika ada
            const existing = document.getElementById('validationToast');
            if (existing) existing.remove();

            const icons = {
                danger:  'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                success: 'fas fa-check-circle',
                info:    'fas fa-info-circle'
            };
            const colors = {
                danger:  '#dc3545',
                warning: '#ffc107',
                success: '#28a745',
                info:    '#17a2b8'
            };

            const toast = document.createElement('div');
            toast.id = 'validationToast';
            toast.innerHTML = `
                <div style="
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 99999;
                    min-width: 320px;
                    max-width: 420px;
                    background: #fff;
                    border-left: 5px solid ${colors[type]};
                    border-radius: 10px;
                    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
                    padding: 16px 20px;
                    display: flex;
                    align-items: flex-start;
                    gap: 12px;
                    animation: slideInRight 0.35s ease;
                ">
                    <i class="${icons[type]}" style="color: ${colors[type]}; font-size: 1.3rem; margin-top: 2px; flex-shrink: 0;"></i>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: #2c3e50; margin-bottom: 2px; font-size: 0.95rem;">
                            ${type === 'danger' ? 'Data Belum Lengkap!' : type === 'success' ? 'Berhasil!' : 'Perhatian!'}
                        </div>
                        <div style="color: #6c757d; font-size: 0.88rem; line-height: 1.4;">${message}</div>
                    </div>
                    <button onclick="this.closest('#validationToast').remove()" style="
                        background: none; border: none; color: #aaa; font-size: 1.2rem;
                        cursor: pointer; line-height: 1; padding: 0; flex-shrink: 0;
                    ">&times;</button>
                </div>
            `;
            document.body.appendChild(toast);

            // Auto-dismiss setelah 4 detik
            setTimeout(() => { if (toast.parentNode) toast.remove(); }, 4000);
        }

        // ==========================================
        // VALIDASI PER STEP
        // ==========================================
        function validateStep(step) {
            const stepEl = document.getElementById('step' + step);
            if (!stepEl) return true;

            const inputs = stepEl.querySelectorAll('input[required], select[required], textarea[required]');
            const emptyFields = [];

            inputs.forEach(function(field) {
                const value = field.value.trim();
                if (!value || value === '') {
                    emptyFields.push(field);
                    field.classList.add('is-invalid');
                    field.style.borderColor = '#dc3545';
                    field.style.boxShadow = '0 0 0 0.2rem rgba(220,53,69,0.2)';
                } else {
                    field.classList.remove('is-invalid');
                    field.style.borderColor = '';
                    field.style.boxShadow = '';
                }
            });

            if (emptyFields.length > 0) {
                const labels = Array.from(emptyFields).map(f => {
                    // Cari label terdekat
                    const id = f.id || f.name;
                    const label = document.querySelector(`label[for="${id}"]`);
                    return label ? `<strong>${label.textContent.trim().replace(' *', '')}</strong>` : `<strong>${id}</strong>`;
                });

                const stepNames = { 1: 'Data Pribadi', 2: 'Data Sertifikat', 3: 'Data Pembimbing', 4: 'Data Pegawai' };
                showToast(
                    `Pada bagian <strong>${stepNames[step] || 'ini'}</strong>, harap isi field berikut:<br>• ${labels.join('<br>• ')}`,
                    'danger'
                );

                // Scroll ke field pertama yang kosong
                emptyFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                emptyFields[0].focus();
                return false;
            }
            return true;
        }

        // ==========================================
        // STEP NAVIGATION
        // ==========================================
        function nextStep(step) {
            const currentStep = step - 1;
            if (!validateStep(currentStep)) return; // Hentikan jika tidak valid

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

        function updateProgressBar(step) {
            const progressBar = document.getElementById('progress-bar');
            const stepTitles = ['Data Pribadi', 'Data Sertifikat', 'Data Pembimbing', 'Data Kepala Dinas'];
            const stepPercentage = (step / 4) * 100;
            progressBar.style.width = stepPercentage + '%';
            progressBar.setAttribute('aria-valuenow', stepPercentage);
            progressBar.innerHTML = `Langkah ${step} dari 4 (${stepTitles[step - 1]})`;
        }

        // ==========================================
        // INSTANSI DROPDOWN & LOGO PREVIEW
        // ==========================================
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

        // ==========================================
        // VALIDASI SAAT SUBMIT — CEK SEMUA STEP
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editForm');

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Selalu cegah submit dulu

                // Cek semua step satu per satu
                const stepNames = { 1: 'Data Pribadi', 2: 'Data Sertifikat', 3: 'Data Pembimbing', 4: 'Data Pegawai' };
                for (let s = 1; s <= 4; s++) {
                    const stepEl = document.getElementById('step' + s);
                    if (!stepEl) continue;

                    // Tampilkan step ini sementara untuk keperluan validasi
                    const wasHidden = stepEl.style.display === 'none';
                    if (wasHidden) stepEl.style.display = 'block';

                    const inputs = stepEl.querySelectorAll('input[required], select[required], textarea[required]');
                    const emptyFields = [];

                    inputs.forEach(function(field) {
                        const val = field.value.trim();
                        if (val === '') {
                            emptyFields.push(field);
                            field.classList.add('is-invalid');
                            field.style.borderColor = '#dc3545';
                            field.style.boxShadow = '0 0 0 0.2rem rgba(220,53,69,0.2)';
                        } else {
                            field.classList.remove('is-invalid');
                            field.style.borderColor = '';
                            field.style.boxShadow = '';
                        }
                    });

                    if (emptyFields.length > 0) {
                        // Navigasi ke step yang bermasalah
                        document.querySelectorAll('.form-step').forEach(el => el.style.display = 'none');
                        stepEl.style.display = 'block';
                        updateProgressBar(s);
                        window.scrollTo({ top: 0, behavior: 'smooth' });

                        const labels = Array.from(emptyFields).map(f => {
                            const id = f.id || f.name;
                            const label = document.querySelector('label[for="' + id + '"]');
                            return label
                                ? '<strong>' + label.textContent.trim().replace('(Opsional)', '').trim() + '</strong>'
                                : '<strong>' + id + '</strong>';
                        });

                        showToast(
                            'Pada bagian <strong>' + stepNames[s] + '</strong>, harap isi kolom berikut:<br>• ' + labels.join('<br>• '),
                            'danger'
                        );

                        emptyFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                        setTimeout(() => emptyFields[0].focus(), 400);
                        return false;
                    }

                    // Sembunyikan kembali jika tadinya tersembunyi
                    if (wasHidden) stepEl.style.display = 'none';
                }

                // Semua valid — submit form
                showToast('Data berhasil disimpan! Mohon tunggu...', 'success');
                setTimeout(() => form.submit(), 600);
            });

            // Reset border merah saat field diubah
            document.querySelectorAll('input, select, textarea').forEach(function(field) {
                field.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    this.style.borderColor = '';
                    this.style.boxShadow = '';
                });
                field.addEventListener('change', function() {
                    this.classList.remove('is-invalid');
                    this.style.borderColor = '';
                    this.style.boxShadow = '';
                });
            });
        });

        function saveStepData(step) {
            const elements = document.querySelectorAll(`#step${step} input, #step${step} select`);
            elements.forEach(el => {
                localStorage.setItem(el.name, el.value);
            });
        }

        function loadStepData() {
            const elements = document.querySelectorAll('input, select');
            elements.forEach(el => {
                if (localStorage.getItem(el.name)) {
                    el.value = localStorage.getItem(el.name);
                }
            });
        }

        // Fix for form submission: remove required from hidden steps
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submit triggered');
                    // Get all visible steps
                    const hiddenSteps = document.querySelectorAll('.form-step[style*="display: none"]');
                    console.log('Hidden steps found:', hiddenSteps.length);

                    // Temporarily remove required attribute from hidden step fields
                    hiddenSteps.forEach(step => {
                        const requiredFields = step.querySelectorAll('[required]');
                        requiredFields.forEach(field => {
                            field.removeAttribute('required');
                        });
                    });

                    // Re-add required attributes after form validation (in case form stays on page)
                    setTimeout(() => {
                        hiddenSteps.forEach(step => {
                            const fields = step.querySelectorAll('input, select, textarea');
                            fields.forEach(field => {
                                // Check if field originally had required (you might want to store this info)
                                const fieldName = field.name;
                                // Re-add required if the parent form still has this field
                                if (fieldName && form.querySelector(`[name="${fieldName}"]`) &&
                                    (fieldName.match(/nama_lengkap|asal_sekolah|nim_nis|tempat_lahir|tgl_lahir|jurusan|tgl_mulai|tgl_selesai|tgl_sertifikat|nm_pembimbing|nik_pembimbing|bidang|aprove_pembimbing|nm_kadis|nip_kadis|jabatan_pegawai|approve_kadis|sts_diambil|sts_kadis|pejabat_ttd/))) {
                                    field.setAttribute('required', 'required');
                                }
                            });
                        });
                    }, 100);
                });
            }
        });

        // ==========================================
        // AUTO-FILL NIP JABATAN PEGAWAI
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            const jabatanSelect = document.getElementById('jabatan_pegawai');
            const nipJabatanInput = document.getElementById('nip_jabatan_pegawai');

            if (jabatanSelect && nipJabatanInput) {
                jabatanSelect.addEventListener('change', function() {
                    if (this.value === 'Kepala Bidang Informatika') {
                        nipJabatanInput.value = '198503232010011028';
                    } else {
                        nipJabatanInput.value = '';
                    }
                });
            }
        });
    </script>

    <style>
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(60px); }
            to   { opacity: 1; transform: translateX(0); }
        }
    </style>

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
