@extends('layouts.landing')

@section('title', 'Tambah Data Riwayat Magang')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

    .form-container {
        max-width: 700px;
        margin: 50px auto;
    }

    .form-header {
        background: linear-gradient(135deg, rgba(90, 96, 255, 0.9), rgba(138, 86, 242, 0.9));
        color: white;
        padding: 40px;
        border-radius: 20px 20px 0 0;
        text-align: center;
    }

    .form-header h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 2rem;
        margin: 0;
    }

    .form-header p {
        margin: 10px 0 0 0;
        opacity: 0.9;
    }

    .form-body {
        background: white;
        padding: 40px;
        border-radius: 0 0 20px 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-family: 'Poppins', sans-serif;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #5a60ff;
        box-shadow: 0 0 0 3px rgba(90, 96, 255, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .error-text {
        color: #ff6b6b;
        font-size: 0.85rem;
        display: block;
        margin-top: 5px;
    }

    .required {
        color: #ff6b6b;
    }

    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit {
        background: linear-gradient(135deg, #5a60ff, #8a56f2);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(90, 96, 255, 0.3);
    }

    .btn-cancel {
        background: #f0f0f0;
        color: #333;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
    }

    .alert {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: none;
    }

    .alert-error {
        background: #fdd4d4;
        color: #7a2d2d;
    }

    @media (max-width: 600px) {
        .form-container {
            margin: 20px;
        }

        .form-header {
            padding: 30px 20px;
        }

        .form-header h1 {
            font-size: 1.5rem;
        }

        .form-body {
            padding: 30px 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .btn-group {
            flex-direction: column;
        }
    }
</style>

@if (!Auth::check() || Auth::user()->role !== 'mentor')
    <div style="text-align: center; padding: 60px 20px;">
        <i class="fas fa-lock" style="font-size: 3rem; color: #ddd; margin-bottom: 20px; display: block;"></i>
        <h2>Akses Ditolak</h2>
        <p>Hanya mentor yang dapat menambahkan data riwayat magang.</p>
        <a href="{{ route('history') }}" class="btn" style="display: inline-block; background: #5a60ff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; margin-top: 20px;">
            Kembali ke Riwayat
        </a>
    </div>
@else
<div class="form-container">
    <div class="form-header">
        <h1><i class="fas fa-plus-circle me-2"></i>Tambah Data Riwayat Magang</h1>
        <p>Form untuk menambahkan data peserta magang baru</p>
    </div>

    <div class="form-body">
        @if ($errors->any())
            <div class="alert alert-error">
                <strong><i class="fas fa-times-circle me-2"></i>Terjadi Kesalahan:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('history.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="nama_lengkap" 
                    name="nama_lengkap" 
                    placeholder="Masukkan nama lengkap peserta"
                    value="{{ old('nama_lengkap') }}"
                    required
                >
                @error('nama_lengkap')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nim_nis">NIM/NIS <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="nim_nis" 
                    name="nim_nis" 
                    placeholder="Masukkan NIM/NIS peserta"
                    value="{{ old('nim_nis') }}"
                    required
                >
                @error('nim_nis')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="asal_sekolah">Asal Sekolah/Perguruan Tinggi <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="asal_sekolah" 
                    name="asal_sekolah" 
                    placeholder="Masukkan nama sekolah/PT"
                    value="{{ old('asal_sekolah') }}"
                    required
                >
                @error('asal_sekolah')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="bidang">Bidang Magang <span class="required">*</span></label>
                <select id="bidang" name="bidang" required>
                    <option value="">-- Pilih Bidang --</option>
                    @foreach ($bidangList as $bid)
                        <option value="{{ $bid }}" @selected(old('bidang') === $bid)>{{ $bid }}</option>
                    @endforeach
                </select>
                @error('bidang')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tgl_mulai">Tanggal Mulai <span class="required">*</span></label>
                    <input 
                        type="date" 
                        id="tgl_mulai" 
                        name="tgl_mulai" 
                        value="{{ old('tgl_mulai') }}"
                        required
                    >
                    @error('tgl_mulai')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tgl_selesai">Tanggal Selesai <span class="required">*</span></label>
                    <input 
                        type="date" 
                        id="tgl_selesai" 
                        name="tgl_selesai" 
                        value="{{ old('tgl_selesai') }}"
                        required
                    >
                    @error('tgl_selesai')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save me-2"></i>Simpan Data
                </button>
                <a href="{{ route('history') }}" class="btn btn-cancel" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
