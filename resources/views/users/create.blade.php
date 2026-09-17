@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')

<style>
    /* Reset container padding for full-width design */
    .container {
        padding-right: 0;
        padding-left: 0;
        max-width: 1240px;
    }

    /* Modern dashboard header */
    .dashboard-header {
        font-size: 1.75rem;
        font-weight: 600;
        color: #f8f9fa;
        margin-bottom: 1.5rem;
        text-align: center;
        margin-top: 2rem;
        letter-spacing: 0.5px;
    }

    /* Card styling */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
        color: white;
        font-weight: 600;
        text-align: center;
        padding: 1.25rem;
        border-bottom: none;
        letter-spacing: 0.5px;
    }

    .card-header h4 {
        margin: 0;
        font-size: 1.25rem;
    }

    .card-body {
        padding: 1.5rem;
        background-color: #fff;
    }

    /* Form styling */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #4b5563;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .form-control {
        display: block;
        width: 100%;
        padding: 0.75rem;
        font-size: 0.9rem;
        font-weight: 400;
        line-height: 1.5;
        color: #1f2937;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    /* Hilangkan ikon error bootstrap agar tidak tabrakan dengan ikon mata */
    .form-control.is-invalid {
        border-color: #dc3545 !important;
        background-image: none !important;
        padding-right: 40px !important;
    }

    .invalid-feedback {
        font-size: 0.85rem;
        color: #dc3545;
        margin-top: 0.25rem;
    }

    /* Button styling */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.75rem 1rem;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn i {
        font-size: 0.9rem;
    }

    .btn-primary {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }

    .btn-primary:hover {
        background-color: #4338ca;
        border-color: #4338ca;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.25rem;
        }

        .form-control {
            padding: 0.6rem;
            font-size: 0.85rem;
        }

        .btn {
            padding: 0.6rem 0.9rem;
            font-size: 0.85rem;
        }
    }
</style>

<h3 class="dashboard-header">Sistem Pengguna</h3>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">

            {{-- Alert Error Global --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-3 border-0 shadow-sm" style="border-radius: 12px;">
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h4>Tambah Pengguna</h4>
                    </div>

                    <div class="card-body">

                        {{-- USERNAME --}}
                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>

                            <input
                                type="text"
                                class="form-control @error('username') is-invalid @enderror"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                placeholder="Masukkan username"
                            >

                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <small class="text-muted" style="display:block;margin-top:5px;font-size:12px;color:#6c757d;">
                                    <i class="fas fa-info-circle"></i>
                                    Minimal 5 karakter, boleh NIS/NIM ataupun bebas (belum dipakai).
                                </small>
                            @enderror
                        </div>

                        {{-- PASSWORD --}}
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>

                            <div class="input-group-password" style="position: relative;">
                                <input
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    required
                                    placeholder="Masukkan password"
                                    style="padding-right: 50px;"
                                >

                                {{-- TOGGLE EYE --}}
                                <span
                                    id="togglePassword"
                                    style="
                                        position:absolute;
                                        right:10px;
                                        top:50%;
                                        transform:translateY(-50%);
                                        cursor:pointer;
                                        z-index:100;
                                        color:#6b7280;
                                        width:35px;
                                        height:35px;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        border-radius:50%;
                                        transition:all 0.2s ease;
                                    "
                                    onmouseover="this.style.backgroundColor='#f3f4f6'; this.style.color='#4f46e5'"
                                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='#6b7280'"
                                >
                                    <i class="fas fa-eye-slash" id="eyeIcon" style="font-size:1rem;"></i>
                                </span>
                            </div>

                            @error('password')
                                <div class="invalid-feedback" style="display:block;">
                                    {{ $message }}
                                </div>
                            @else
                                <small class="text-muted" style="display:block;margin-top:5px;font-size:12px;color:#6c757d;">
                                    <i class="fas fa-info-circle"></i>
                                    Minimal 6 karakter, Disarankan kombinasi huruf besar, angka, dan simbol.
                                </small>
                            @enderror
                        </div>

                        {{-- ROLE --}}
                        <div class="form-group">
                            <label for="role" class="form-label">Role</label>

                            <select
                                name="role"
                                id="role"
                                class="form-control @error('role') is-invalid @enderror"
                                required
                            >
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="mentor" {{ old('role') == 'mentor' ? 'selected' : '' }}>Mentor</option>
                                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            </select>

                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ========================================== --}}
                        {{-- DROPDOWN PILIH SISWA  --}}
                        {{-- ========================================== --}}
                        <div class="form-group mb-4" id="pilih-siswa-container" style="display: none; background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px dashed #ced4da;">
                            <label for="id_sertifikat" class="form-label text-primary font-weight-bold">
                                <i class="fas fa-link mr-1"></i> Hubungkan ke Data Siswa
                            </label>
                            
                            <select name="id_sertifikat" id="id_sertifikat" class="form-control">
                                <option value="">-- Pilih Data Siswa --</option>
                                
                                @if(isset($siswas) && count($siswas) > 0)
                                    @foreach($siswas as $siswa)
                                        <option value="{{ $siswa->id_sertifikat }}">
                                            {{ $siswa->nama_lengkap }} (NIS: {{ $siswa->nim_nis }}) - {{ $siswa->asal_sekolah }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Semua siswa sudah punya akun / Data kosong</option>
                                @endif
                            </select>
                            
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle"></i> Jika nama dipilih, Username boleh bebas (tidak wajib pakai NIS/NIM).
                            </small>
                        </div>
                        {{-- ========================================== --}}


                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- SCRIPT TOGGLE PASSWORD --}}
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField  = document.querySelector('#password');
    const eyeIcon        = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password'
            ? 'text'
            : 'password';

        passwordField.setAttribute('type', type);

        if (type === 'text') {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    });
</script>

{{-- SCRIPT SHOW/HIDE DROPDOWN SISWA --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.querySelector('#role');
        const siswaContainer = document.getElementById('pilih-siswa-container');

        // Fungsi untuk mengecek role saat ini
        function checkRole() {
            if(roleSelect.value === 'siswa') {
                siswaContainer.style.display = 'block';
            } else {
                siswaContainer.style.display = 'none';
                // Reset pilihan jika bukan siswa (opsional)
                document.getElementById('id_sertifikat').value = "";
            }
        }

        // Jalankan saat role berubah
        roleSelect.addEventListener('change', checkRole);

        // Jalankan saat halaman dimuat (jika validasi gagal & kembali ke form)
        checkRole();
    });
</script>

@endsection