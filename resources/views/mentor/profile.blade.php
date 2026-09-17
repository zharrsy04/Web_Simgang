@extends('layouts.app')

@section('title', 'Profil Mentor')

@section('content')
<div class="content">
    <div class="container-fluid">
        <h3 class="mb-4">Profil Saya</h3>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('mentor.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
                    </div>

                    <div class="mb-3">
                        <label for="bidang" class="form-label fw-bold">Bidang</label>
                        <input type="text" class="form-control bg-light" id="bidang" value="{{ collect(\App\Constants\BidangConstants::getValidBidang())->first(fn($b) => str_contains($user->bidang, $b)) ?? $user->bidang }}" readonly>
                        <small class="text-muted">Perpindahan bidang harus dikelola melalui Administrator (Sekretariat).</small>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Ganti Password (Opsional)</h5>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                        <small class="text-muted">Minimal 6 karakter.</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
