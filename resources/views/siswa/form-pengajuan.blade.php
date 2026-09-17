@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px;">
                <h1 class="mb-2"><i class="fas fa-file-alt me-2"></i>Formulir Pengajuan Magang</h1>
                <p class="mb-0 opacity-90">Lengkapi data di bawah untuk mengajukan program magang di Diskominfo Cilacap</p>
            </div>
        </div>
    </div>
    
    @if($pengajuan && $pengajuan->status === 'rejected')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-times-circle"></i> Pengajuan Ditolak</strong>
            <p class="mb-0 mt-2">Alasan: {{ $pengajuan->alasan_reject }}</p>
            <p class="mb-0 mt-1 small">Silakan perbaiki data dan ajukan kembali.</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
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
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Data Diri</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" id="pengajuanForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label fw-600">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                    id="nama" name="nama" value="{{ old('nama', $pengajuan->nama ?? Auth::user()->name) }}" required>
                                @error('nama')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nim_nis" class="form-label fw-600">NIM/NIS <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nim_nis') is-invalid @enderror" 
                                    id="nim_nis" name="nim_nis" value="{{ old('nim_nis', $pengajuan->nim_nis ?? '') }}" required>
                                @error('nim_nis')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                <small class="text-muted">Nomor Induk Mahasiswa/Siswa</small>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tempat_lahir" class="form-label fw-600">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                    id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $pengajuan->tempat_lahir ?? '') }}" required>
                                @error('tempat_lahir')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tgl_lahir" class="form-label fw-600">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" 
                                    id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir', isset($pengajuan->tgl_lahir) ? (is_string($pengajuan->tgl_lahir) ? $pengajuan->tgl_lahir : $pengajuan->tgl_lahir->format('Y-m-d')) : '') }}" required>
                                @error('tgl_lahir')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        
                        <div class="mb-3 position-relative">
                            <label class="form-label fw-600">Asal Sekolah/Universitas <span class="text-danger">*</span></label>
                            
                            <!-- Searchable Dropdown Container -->
                            <div class="custom-dropdown-container" id="instansiDropdownContainer">
                                <div class="custom-dropdown-trigger form-control d-flex justify-content-between align-items-center cursor-pointer" id="dropdownTrigger">
                                    <span id="selectedOptionText" class="text-muted">-- Pilih Sekolah/Universitas --</span>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </div>
                                <div class="custom-dropdown-menu shadow-lg border rounded-3 p-2" id="dropdownMenu" style="display: none; position: absolute; z-index: 1000; background: white; width: 100%; left: 0; margin-top: 5px;">
                                    <div class="input-group input-group-sm mb-2">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                        <input type="text" id="dropdownSearchInput" class="form-control border-start-0" placeholder="Cari sekolah..." autocomplete="off">
                                    </div>
                                    <div class="dropdown-options-list" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($instansis as $ins)
                                            <div class="dropdown-option-item p-2 d-flex align-items-center cursor-pointer" data-id="{{ $ins->id }}" data-nama="{{ $ins->nama }}">
                                                @if($ins->getLogoUrl())
                                                    <img src="{{ $ins->getLogoUrl() }}" alt="Logo" class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px;">
                                                        <i class="fas fa-university text-muted" style="font-size: 12px;"></i>
                                                    </div>
                                                @endif
                                                <span>{{ $ins->nama }}</span>
                                            </div>
                                        @endforeach
                                        <div class="dropdown-option-item p-2 d-flex align-items-center cursor-pointer border-top text-primary fw-600" data-id="lainnya" data-nama="Lainnya">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px;">
                                                <i class="fas fa-plus text-primary" style="font-size: 12px;"></i>
                                            </div>
                                            <span>Lainnya / Sekolah Tidak Terdaftar</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden input for instansi_id -->
                            <input type="hidden" name="instansi_id" id="instansi_id" value="{{ old('instansi_id', $pengajuan->instansi_id ?? '') }}">
                            
                            <!-- Conditional manual text input & logo upload -->
                            <div class="mt-2" id="manualInputContainer" style="display: none;">
                                <div class="mb-3">
                                    <label for="asal_sekolah" class="form-label small text-muted fw-600">Ketik Nama Sekolah Secara Manual <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('asal_sekolah') is-invalid @enderror" 
                                        id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah', $pengajuan->asal_sekolah ?? '') }}" 
                                        placeholder="Ketik nama lengkap sekolah/universitas Anda" autocomplete="off" required>
                                    @error('asal_sekolah')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-2" id="logoUploadContainer">
                                    <label for="logo" class="form-label small text-muted fw-600">Upload Foto/Logo Sekolah <span class="text-muted fw-normal">(Opsional)</span></label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                        id="logo" name="logo" accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp">
                                    <div class="form-text text-muted" style="font-size: 0.78rem;">Format: PNG, JPG, JPEG, SVG, WEBP. Maksimal 2MB.</div>
                                    @error('logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            
                            @error('instansi_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="jurusan" class="form-label fw-600">Jurusan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('jurusan') is-invalid @enderror" 
                                id="jurusan" name="jurusan" value="{{ old('jurusan', $pengajuan->jurusan ?? '') }}" 
                                placeholder="Contoh: Teknik Informatika" required>
                            @error('jurusan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="bidang" class="form-label fw-600">Pilihan Bidang Magang <span class="text-danger">*</span></label>
                            <select class="form-select @error('bidang') is-invalid @enderror" 
                                id="bidang" name="bidang" required>
                                <option value="" selected disabled>-- Pilih Bidang --</option>
                                @foreach(\App\Constants\BidangConstants::getValidBidang() as $opt)
                                    <option value="{{ $opt }}" 
                                        {{ old('bidang', $pengajuan->bidang ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                            @error('bidang')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Kalender Referensi -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Referensi Jadwal Magang</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Lihat jadwal magang yang sedang berlangsung untuk membantu Anda memilih periode yang sesuai.</p>
                    
                    <div class="row">
                        <!-- Mini Kalender -->
                        <div class="col-lg-6 mb-3">
                            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 fw-bold"><i class="fas fa-calendar me-2" style="color: #667eea;"></i>Kalender</h6>
                                    <div style="gap: 5px; display: flex;">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle" id="prevMonthForm" style="padding: 5px 8px; width: 32px; height: 32px;" title="Bulan Sebelumnya"><i class="fas fa-chevron-left"></i></button>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle" id="nextMonthForm" style="padding: 5px 8px; width: 32px; height: 32px;" title="Bulan Berikutnya"><i class="fas fa-chevron-right"></i></button>
                                    </div>
                                </div>
                                <p id="calendarMonthYearForm" class="text-center fw-bold mb-3" style="font-size: 0.95rem; color: #333;"></p>
                                
                                <table class="calendar-table-form" style="width: 100%; font-size: 0.85rem; border-spacing: 3px; table-layout: fixed; margin-bottom: 15px;">
                                    <thead>
                                        <tr style="text-align: center; color: #64748b; font-weight: 600;">
                                            <th>Min</th><th>Sen</th><th>Sel</th><th>Rab</th><th>Kam</th><th>Jum</th><th>Sab</th>
                                        </tr>
                                    </thead>
                                    <tbody id="calendarBodyForm"></tbody>
                                </table>
                                
                                <div class="d-flex align-items-center justify-content-center mt-2 pt-2" style="border-top: 1px dashed #e2e8f0;">
                                    <span style="display:inline-block; width:8px; height:8px; background-color:#f59e0b; border-radius:50%; margin-right:6px;"></span>
                                    <small class="text-muted" style="font-size: 0.8rem;">Tanggal dengan peserta magang</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Info Tanggal Dipilih -->
                        <div class="col-lg-6 mb-3">
                            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; min-height: 350px; display: flex; flex-direction: column;">
                                <h6 class="mb-3 fw-bold"><i class="fas fa-info-circle me-2" style="color: #667eea;"></i>Detail Jadwal</h6>
                                
                                <div id="formDetailEmpty" class="flex-grow-1 d-flex flex-column justify-content-center align-items-center text-center">
                                    <i class="fas fa-calendar-check text-muted" style="font-size: 2.5rem; margin-bottom: 10px;"></i>
                                    <p class="text-muted small mb-0">Klik tanggal di kalender untuk melihat detail</p>
                                </div>
                                
                                <div id="formDetailData" class="flex-grow-1 d-none flex-column justify-content-between">
                                    <div>
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Peserta Aktif</small>
                                            <div style="background: white; padding: 12px; border-radius: 8px; text-align: center;">
                                                <div style="font-size: 1.8rem; color: #667eea; font-weight: bold;" id="formTotalPeserta">0</div>
                                                <small class="text-muted">orang</small>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <small class="text-muted d-block mb-2">Bidang Penempatan</small>
                                            <div id="formBidangList" style="display: flex; flex-wrap: wrap; gap: 6px;"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="formDetailNotFound" class="flex-grow-1 d-none flex-column justify-content-center align-items-center text-center">
                                    <i class="fas fa-inbox text-muted" style="font-size: 2.5rem; margin-bottom: 10px;"></i>
                                    <p class="text-muted small mb-0">Tidak ada peserta pada tanggal ini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tanggal Magang -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Periode Magang</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai" class="form-label fw-600">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', isset($pengajuan->tanggal_mulai) ? (is_string($pengajuan->tanggal_mulai) ? $pengajuan->tanggal_mulai : $pengajuan->tanggal_mulai->format('Y-m-d')) : '') }}" required form="pengajuanForm">
                            @error('tanggal_mulai')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_selesai" class="form-label fw-600">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', isset($pengajuan->tanggal_selesai) ? (is_string($pengajuan->tanggal_selesai) ? $pengajuan->tanggal_selesai : $pengajuan->tanggal_selesai->format('Y-m-d')) : '') }}" required form="pengajuanForm">
                            @error('tanggal_selesai')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Dokumen -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-file me-2"></i>Dokumen Pendukung</h5>
                </div>
                <div class="card-body">
                    <label for="dokumen" class="form-label fw-600">Upload Dokumen (Opsional)</label>
                    <input type="file" class="form-control @error('dokumen') is-invalid @enderror" 
                        id="dokumen" name="dokumen" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" form="pengajuanForm">
                    @if(old('temp_dokumen_path'))
                        <input type="hidden" name="temp_dokumen_path" value="{{ old('temp_dokumen_path') }}" form="pengajuanForm">
                        <input type="hidden" name="temp_dokumen_name" value="{{ old('temp_dokumen_name') }}" form="pengajuanForm">
                        <small class="text-success d-block mt-1"><i class="fas fa-check-circle"></i> File sebelumnya telah terupload: {{ old('temp_dokumen_name') }}</small>
                    @endif
                    @error('dokumen')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <small class="text-muted d-block mt-2">Format: PDF, DOC, DOCX, PNG, JPG | Ukuran Maksimal: 5MB</small>
                    <small class="text-muted d-block">Contoh: Surat pengantar, proposal magang, atau dokumen lainnya</small>
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="d-grid gap-2 mb-4">
                <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px 20px; font-weight: 600;" form="pengajuanForm">
                    <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan
                </button>
            </div>
        </div>
        
        <!-- Info Box -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 mb-4" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border: 1px solid #667eea30 !important;">
                <div class="card-body">
                    <h6 class="fw-bold mb-3" style="color: #667eea;"><i class="fas fa-lightbulb me-2"></i>Informasi Penting</h6>
                    <ul class="small text-muted list-unstyled">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Pastikan data Anda lengkap dan benar</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Periode magang fleksibel sesuai kebutuhan</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Dokumen pendukung membantu proses verifikasi</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Tunggu persetujuan dari mentor</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Anda akan menerima notifikasi status pengajuan</li>
                    </ul>
                </div>
            </div>
            
            <div class="card shadow-sm border-0 rounded-4" style="background: linear-gradient(135deg, #ffc10715 0%, #ff650015 100%); border: 1px solid #ffc10730 !important;">
                <div class="card-body">
                    <h6 class="fw-bold mb-3" style="color: #ff6500;"><i class="fas fa-exclamation-triangle me-2"></i>Catatan</h6>
                    <p class="small text-muted mb-0">Pengajuan yang tidak lengkap atau data yang tidak valid akan ditolak. Pastikan Anda mengikuti semua panduan yang disediakan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.btn-primary {
    background: linear-gradient(135deg, #4568dc, #6157ff);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #3755c7, #5048e5);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(69, 104, 220, 0.3);
}

/* Mini calendar styles untuk form */
.calendar-table-form {
    width: 100%;
    table-layout: fixed;
    border-spacing: 3px;
}

.calendar-table-form th {
    text-align: center;
    padding: 8px 0;
    color: #64748b;
    font-weight: 600;
    font-size: 0.85rem;
}

.calendar-table-form td {
    text-align: center;
    padding: 12px 0;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s;
    height: 40px;
    position: relative;
    overflow: visible;
}

.calendar-table-form td:not(.empty):hover {
    background-color: #f1f5f9;
    transform: scale(1.05);
}

.calendar-table-form td.empty {
    cursor: default;
}

.calendar-table-form td.today {
    background-color: #e0e7ff;
    color: #667eea;
    font-weight: 700;
    border: 2px solid #667eea;
    box-sizing: border-box;
}

.calendar-table-form td.selected {
    background-color: #667eea;
    color: white;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.calendar-table-form td.has-interns::after {
    content: '';
    display: block;
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 6px;
    height: 6px;
    background-color: #f59e0b;
    border-radius: 50%;
}

.calendar-table-form td.today.has-interns::after {
    background-color: #667eea;
}

.calendar-table-form td.selected.has-interns::after {
    background-color: white;
    box-shadow: 0 1px 3px rgba(102, 126, 234, 0.4);
}

/* Custom Dropdown Styling */
.custom-dropdown-container {
    position: relative;
    width: 100%;
}
.custom-dropdown-trigger {
    cursor: pointer;
    background-color: #fff;
    border: 1px solid #dee2e6;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    user-select: none;
}
.custom-dropdown-trigger:hover {
    border-color: #b0b3b8;
}
.custom-dropdown-menu {
    display: none;
    position: absolute;
    z-index: 1050;
    background: white;
    width: 100%;
    left: 0;
    margin-top: 5px;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
.dropdown-options-list {
    max-height: 200px;
    overflow-y: auto;
}
.dropdown-option-item {
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 0.25rem;
    transition: background 0.2s ease;
    user-select: none;
}
.dropdown-option-item:hover {
    background-color: #f1f5f9;
}
.dropdown-option-item.selected {
    background-color: #e2e8f0;
    font-weight: 600;
}
</style>

<script>
// =============== SEARCHABLE DROPDOWN & FORM ===============
document.addEventListener('DOMContentLoaded', function() {
    // Searchable dropdown logic
    const dropdownTrigger = document.getElementById('dropdownTrigger');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownSearchInput = document.getElementById('dropdownSearchInput');
    const selectedOptionText = document.getElementById('selectedOptionText');
    const instansiIdInput = document.getElementById('instansi_id');
    const manualInputContainer = document.getElementById('manualInputContainer');
    const manualSchoolInput = document.getElementById('asal_sekolah');
    const dropdownOptions = document.querySelectorAll('.dropdown-option-item');

    // Toggle dropdown visibility
    if (dropdownTrigger) {
        dropdownTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = dropdownMenu.style.display === 'block';
            dropdownMenu.style.display = isOpen ? 'none' : 'block';
            if (!isOpen) {
                dropdownSearchInput.value = '';
                filterOptions('');
                dropdownSearchInput.focus();
            }
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function() {
        if (dropdownMenu) dropdownMenu.style.display = 'none';
    });

    // Prevent closing when clicking inside the dropdown menu (e.g. search input)
    if (dropdownMenu) {
        dropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Search filter function
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

    // Handle option selection
    dropdownOptions.forEach(option => {
        option.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');

            // Remove selected class from all options
            dropdownOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');

            selectedOptionText.textContent = nama;
            selectedOptionText.classList.remove('text-muted');

            if (id === 'lainnya') {
                instansiIdInput.value = '';
                manualInputContainer.style.display = 'block';
                manualSchoolInput.value = '';
                manualSchoolInput.setAttribute('required', 'required');
                manualSchoolInput.focus();
            } else {
                instansiIdInput.value = id;
                manualInputContainer.style.display = 'none';
                // Set the manualSchoolInput value to the chosen school name, so it passes backend validation 'asal_sekolah'
                manualSchoolInput.value = nama;
                manualSchoolInput.removeAttribute('required');
            }

            dropdownMenu.style.display = 'none';
        });
    });

    // Initial check (in case of old inputs after validation errors)
    if (instansiIdInput && manualSchoolInput) {
        const initialInstansiId = instansiIdInput.value;
        const initialAsalSekolah = manualSchoolInput.value;

        if (initialInstansiId) {
            const matchingOption = Array.from(dropdownOptions).find(opt => opt.getAttribute('data-id') === initialInstansiId);
            if (matchingOption) {
                matchingOption.classList.add('selected');
                selectedOptionText.textContent = matchingOption.getAttribute('data-nama');
                selectedOptionText.classList.remove('text-muted');
                manualInputContainer.style.display = 'none';
                manualSchoolInput.value = matchingOption.getAttribute('data-nama');
                manualSchoolInput.removeAttribute('required');
            }
        } else if (initialAsalSekolah) {
            const lainnyaOption = Array.from(dropdownOptions).find(opt => opt.getAttribute('data-id') === 'lainnya');
            if (lainnyaOption) {
                lainnyaOption.classList.add('selected');
                selectedOptionText.textContent = 'Lainnya / Sekolah Tidak Terdaftar';
                selectedOptionText.classList.remove('text-muted');
                manualInputContainer.style.display = 'block';
                manualSchoolInput.value = initialAsalSekolah;
                manualSchoolInput.setAttribute('required', 'required');
            }
        }
    }

    // =============== KALENDER MAGANG FORM ===============
    const semuaMagang = @json($semuaMagang ?? []);
    let currentDateForm = new Date();
    let selectedDateForm = new Date();

    // Render calendar untuk form
    function renderCalendarForm(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const firstDayIndex = new Date(year, month, 1).getDay();
        const lastDayDate = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const monthYearElement = document.getElementById('calendarMonthYearForm');
        if (monthYearElement) {
            monthYearElement.textContent = `${monthNames[month]} ${year}`;
        }
        
        let days = "";
        let dayCount = 1;
        
        for (let i = 0; i < 6; i++) {
            let row = "<tr>";
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDayIndex) {
                    row += `<td class="empty"></td>`;
                } else if (dayCount > lastDayDate) {
                    row += `<td class="empty"></td>`;
                } else {
                    const formattedCellDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(dayCount).padStart(2, '0')}`;
                    
                    let classes = [];
                    if (dayCount === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                        classes.push('today');
                    }
                    if (dayCount === selectedDateForm.getDate() && month === selectedDateForm.getMonth() && year === selectedDateForm.getFullYear()) {
                        classes.push('selected');
                    }
                    
                    const hasInterns = semuaMagang.some(magang => {
                        const start = magang.tgl_mulai ? magang.tgl_mulai.substring(0, 10) : '';
                        const end = magang.tgl_selesai ? magang.tgl_selesai.substring(0, 10) : '';
                        return start && end && formattedCellDate >= start && formattedCellDate <= end;
                    });
                    
                    if (hasInterns) classes.push('has-interns');
                    
                    row += `<td class="${classes.join(' ')}" onclick="selectDateForm(${year}, ${month}, ${dayCount})">${dayCount}</td>`;
                    dayCount++;
                }
            }
            row += "</tr>";
            days += row;
            if (dayCount > lastDayDate) break;
        }
        const calendarBodyForm = document.getElementById('calendarBodyForm');
        if (calendarBodyForm) {
            calendarBodyForm.innerHTML = days;
        }
    }

    // Select date di form calendar
    window.selectDateForm = function(year, month, day) {
        selectedDateForm = new Date(year, month, day);
        renderCalendarForm(currentDateForm);
        
        const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        fetchDetailJadwalForm(formattedDate);
    };

    // Fetch detail jadwal untuk form
    function fetchDetailJadwalForm(dateStr) {
        const formDetailEmpty    = document.getElementById('formDetailEmpty');
        const formDetailData     = document.getElementById('formDetailData');
        const formDetailNotFound = document.getElementById('formDetailNotFound');
        const formTotalPeserta   = document.getElementById('formTotalPeserta');
        const formBidangList     = document.getElementById('formBidangList');

        // Helper function untuk mengganti visibility panel secara konsisten
        function showPanel(panelId) {
            const panels = {
                empty: formDetailEmpty,
                data: formDetailData,
                notfound: formDetailNotFound
            };
            
            Object.keys(panels).forEach(key => {
                const el = panels[key];
                if (!el) return;
                if (key === panelId) {
                    el.classList.remove('d-none');
                    el.classList.add('d-flex');
                } else {
                    el.classList.add('d-none');
                    el.classList.remove('d-flex');
                }
            });
        }

        // Reset data lama
        if (formTotalPeserta)   formTotalPeserta.textContent     = '0';
        if (formBidangList)     formBidangList.innerHTML         = '';

        // Filter data magang yang aktif pada tanggal yang dipilih
        const internsOnDate = semuaMagang.filter(magang => {
            const start = magang.tgl_mulai ? magang.tgl_mulai.substring(0, 10) : '';
            const end = magang.tgl_selesai ? magang.tgl_selesai.substring(0, 10) : '';
            return start && end && dateStr >= start && dateStr <= end;
        });

        if (internsOnDate.length > 0) {
            // Ada peserta — hitung total & kelompokkan per bidang
            const totalPeserta = internsOnDate.length;
            const bidangMap = {};

            internsOnDate.forEach(student => {
                if (!bidangMap[student.bidang]) {
                    bidangMap[student.bidang] = 0;
                }
                bidangMap[student.bidang]++;
            });

            // Tampilkan jumlah peserta
            if (formTotalPeserta) {
                formTotalPeserta.textContent = totalPeserta;
            }

            // Render daftar bidang
            if (formBidangList) {
                Object.entries(bidangMap).forEach(([bidang, count]) => {
                    const badgeDiv = document.createElement('div');
                    badgeDiv.style.cssText = 'display: flex; align-items: center; gap: 8px; padding: 10px; background: white; border-radius: 6px; border-left: 3px solid #667eea; width: 100%;';
                    badgeDiv.innerHTML = `
                        <i class="fas fa-briefcase" style="color: #667eea; font-size: 14px;"></i>
                        <span style="flex-grow: 1; font-weight: 500; color: #2c3e50; font-size: 0.85rem;">${bidang}</span>
                        <span style="background: #667eea; color: white; padding: 3px 10px; border-radius: 15px; font-weight: 600; font-size: 12px;">${count}</span>
                    `;
                    formBidangList.appendChild(badgeDiv);
                });
            }

            // Tampilkan panel data
            showPanel('data');

        } else {
            // Tidak ada peserta — tampilkan panel kosong
            showPanel('notfound');
        }
    }


    // Event listeners untuk navigasi bulan form
    const prevMonthFormBtn = document.getElementById('prevMonthForm');
    const nextMonthFormBtn = document.getElementById('nextMonthForm');
    
    if (prevMonthFormBtn) {
        prevMonthFormBtn.addEventListener('click', function(e) {
            e.preventDefault();
            currentDateForm.setMonth(currentDateForm.getMonth() - 1);
            renderCalendarForm(currentDateForm);
        });
    }
    
    if (nextMonthFormBtn) {
        nextMonthFormBtn.addEventListener('click', function(e) {
            e.preventDefault();
            currentDateForm.setMonth(currentDateForm.getMonth() + 1);
            renderCalendarForm(currentDateForm);
        });
    }
    
    // Initialize calendar form
    renderCalendarForm(currentDateForm);
});
</script>
@endsection
