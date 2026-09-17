@extends('layouts.landing')

@section('title', 'Download Sertifikat Magang')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

    .cert-container {
        max-width: 600px;
        margin: 50px auto;
    }

    .cert-header {
        background: linear-gradient(135deg, rgba(90, 96, 255, 0.9), rgba(138, 86, 242, 0.9));
        color: white;
        padding: 40px;
        border-radius: 20px 20px 0 0;
        text-align: center;
    }

    .cert-header h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 2rem;
        margin: 0;
    }

    .cert-header p {
        margin: 10px 0 0 0;
        opacity: 0.9;
    }

    .cert-form {
        background: white;
        padding: 40px;
        border-radius: 0 0 20px 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        font-family: 'Poppins', sans-serif;
    }

    .form-group input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-group input:focus {
        outline: none;
        border-color: #5a60ff;
        box-shadow: 0 0 0 3px rgba(90, 96, 255, 0.1);
    }

    .form-info {
        background: #f0f4ff;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #5a60ff;
        margin-bottom: 20px;
        font-size: 0.95rem;
        color: #555;
    }

    .form-info strong {
        color: #5a60ff;
    }

    .btn-submit {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #5a60ff, #8a56f2);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 25px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(90, 96, 255, 0.3);
    }

    .btn-back {
        width: 100%;
        padding: 12px;
        background: #f0f0f0;
        color: #333;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0;
    }

    .btn-back:hover {
        background: #e0e0e0;
    }

    .alert {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: none;
    }

    .alert-success {
        background: #d4f4dd;
        color: #2d7a4a;
    }

    .alert-error {
        background: #fdd4d4;
        color: #7a2d2d;
    }

    @media (max-width: 600px) {
        .cert-container {
            margin: 20px;
        }

        .cert-header {
            padding: 30px 20px;
        }

        .cert-header h1 {
            font-size: 1.5rem;
        }

        .cert-form {
            padding: 30px 20px;
        }
    }
</style>

<div class="cert-container">
    <div class="cert-header">
        <h1><i class="fas fa-certificate me-2"></i>Download Sertifikat</h1>
        <p>Validasi data peserta untuk mengunduh sertifikat magang</p>
    </div>

    <div class="cert-form">
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <div class="form-info">
            <strong><i class="fas fa-info-circle me-2"></i>Petunjuk:</strong><br>
            Masukkan nama lengkap dan NIM/NIS yang sesuai dengan data registrasi Anda untuk memverifikasi dan mengunduh sertifikat magang.
        </div>

        <form action="{{ route('certificate.validate') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama_peserta">Nama Lengkap <span style="color: #ff6b6b;">*</span></label>
                <input 
                    type="text" 
                    id="nama_peserta" 
                    name="nama_peserta" 
                    placeholder="Masukkan nama lengkap Anda"
                    value="{{ old('nama_peserta') }}"
                    required
                >
                @error('nama_peserta')
                    <small style="color: #ff6b6b; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="nim_nis">NIM/NIS <span style="color: #ff6b6b;">*</span></label>
                <input 
                    type="text" 
                    id="nim_nis" 
                    name="nim_nis" 
                    placeholder="Masukkan NIM/NIS Anda"
                    value="{{ old('nim_nis') }}"
                    required
                >
                @error('nim_nis')
                    <small style="color: #ff6b6b; display: block; margin-top: 5px;">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-download me-2"></i>Download Sertifikat
            </button>
        </form>

        <a href="{{ route('history') }}" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

@endsection
