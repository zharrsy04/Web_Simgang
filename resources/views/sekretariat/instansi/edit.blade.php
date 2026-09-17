@extends('layouts.app')

@section('title', 'Edit Instansi')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('sekretariat.instansi.index') }}" class="text-white text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Instansi
        </a>
        <h2 class="mt-2 text-white fw-700">Edit Instansi</h2>
        <p class="text-white-50">Perbarui detail sekolah atau universitas mitra</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-600 text-muted"><i class="fas fa-university me-2 text-primary"></i> Edit Data Instansi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('sekretariat.instansi.update', $instansi->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-600">Nama Sekolah / Universitas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                id="nama" name="nama" value="{{ old('nama', $instansi->nama) }}" 
                                placeholder="Contoh: Universitas Gadjah Mada" required autofocus>
                            @error('nama')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-600 d-block">Logo Saat Ini</label>
                            @if($instansi->getLogoUrl())
                                <div class="mb-3 p-2 border rounded d-inline-block bg-light">
                                    <img src="{{ $instansi->getLogoUrl() }}" alt="Logo" class="img-thumbnail" style="max-height: 120px; object-fit: contain;">
                                </div>
                            @else
                                <div class="mb-3 p-3 bg-light text-muted rounded text-center border d-inline-block" style="width: 120px; height: 120px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-university fa-3x"></i>
                                </div>
                            @endif

                            <label for="logo" class="form-label fw-600 d-block">Ganti Logo</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                id="logo" name="logo" accept="image/*">
                            <div class="form-text mt-1 text-muted">Biarkan kosong jika tidak ingin mengubah logo. Format: PNG, JPG, JPEG, SVG, WEBP. Maksimal 2MB.</div>
                            @error('logo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="border-top pt-3 d-flex justify-content-end gap-2">
                            <a href="{{ route('sekretariat.instansi.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
