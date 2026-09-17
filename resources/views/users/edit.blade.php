@extends('layouts.app')

@section('title', 'Edit Pengguna')

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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                <h4>Edit Pengguna</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required placeholder="Masukkan username">
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Password <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru">
                        </div>

                        <div class="form-group">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="" disabled>Pilih Role</option>
                                <option value="mentor" {{ $user->role == 'mentor' ? 'selected' : '' }}>Mentor</option>
                                <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection