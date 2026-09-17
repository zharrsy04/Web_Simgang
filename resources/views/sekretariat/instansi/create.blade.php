@extends('layouts.app')

@section('title', 'Tambah Instansi')

@section('content')
    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ route('sekretariat.instansi.index') }}" class="text-white text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Instansi
            </a>
            <h2 class="mt-2 text-white fw-700">Tambah Instansi Baru</h2>
            <p class="text-white-50">Daftarkan sekolah atau universitas ke sistem database</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-600 text-muted"><i class="fas fa-university me-2 text-primary"></i> Formulir
                            Instansi Baru</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sekretariat.instansi.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="nama" class="form-label fw-600">Nama Sekolah / Universitas <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                    name="nama" value="{{ old('nama', request('nama')) }}"
                                    placeholder="Contoh: Universitas Jenderal Soedirman (UNSOED)" required autofocus>
                                @error('nama')
                                <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="logo" class="form-label fw-600">Logo Instansi</label>
                                <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo"
                                    name="logo" accept="image/*">
                                <div class="form-text mt-1 text-muted">Format gambar: PNG, JPG, JPEG, SVG, WEBP. Maksimal
                                    2MB.</div>
                                @error('logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="border-top pt-3 d-flex justify-content-end gap-2">
                                <a href="{{ route('sekretariat.instansi.index') }}"
                                    class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary"
                                    style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                                    <i class="fas fa-save me-1"></i> Simpan Instansi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection