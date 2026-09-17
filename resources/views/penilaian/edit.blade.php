@extends('layouts.app')

@section('title', 'Edit Penilaian')

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

    .form-control, .form-select {
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

    .form-control:focus, .form-select:focus {
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
    
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.25rem;
        }
        
        .form-control, .form-select {
            padding: 0.6rem;
            font-size: 0.85rem;
        }
        
        .btn {
            padding: 0.6rem 0.9rem;
            font-size: 0.85rem;
        }
    }
</style>

<h3 class="dashboard-header">Sistem Penilaian</h3>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Penilaian</h4>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: '{!! session('error') !!}',
                                    confirmButtonColor: '#4f46e5'
                                });
                            });
                        </script>
                    @endif

                    <form action="{{ route('penilaian.update', $penilaian->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @php
                            $commonAspek = ['Kedisiplinan', 'Kerjasama Tim', 'Tanggung Jawab', 'Inisiatif dan Kreativitas', 'Sikap dan Perilaku', 'Keterampilan Praktik / Teknis'];
                            $isManual = !in_array($penilaian->aspek, $commonAspek);
                        @endphp

                        <div class="form-group">
                            <label class="form-label">Input Aspek Penilaian</label>
                            <div class="form-control" style="height: auto; padding: 0.75rem;">
                                <div class="form-check form-check-inline" style="margin-right: 1.5rem; display: inline-block;">
                                    <input class="form-check-input" type="radio" name="input_mode" id="mode_select" value="select" {{ !$isManual ? 'checked' : '' }} onchange="toggleAspekMode()">
                                    <label class="form-check-label mb-0" for="mode_select" style="cursor: pointer;">Pilih dari Daftar</label>
                                </div>
                                <div class="form-check form-check-inline" style="display: inline-block;">
                                    <input class="form-check-input" type="radio" name="input_mode" id="mode_manual" value="manual" {{ $isManual ? 'checked' : '' }} onchange="toggleAspekMode()">
                                    <label class="form-check-label mb-0" for="mode_manual" style="cursor: pointer;">Ketik Manual</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="group_aspek_select" style="display: {{ !$isManual ? 'block' : 'none' }};">
                            <label class="form-label">Daftar Aspek Penilaian (Pilih salah satu)</label>
                            <div class="mt-2">
                                @foreach($commonAspek as $aspek)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input check-aspek" type="radio" name="aspek[]" value="{{ $aspek }}" id="aspek_{{ $loop->index }}"
                                            {{ !$isManual && $penilaian->aspek == $aspek ? 'checked' : '' }}>
                                        <label class="form-check-label" for="aspek_{{ $loop->index }}">
                                            {{ $aspek }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group" id="group_aspek_manual" style="display: {{ $isManual ? 'block' : 'none' }};">
                            <label for="aspek_manual" class="form-label">Ketik Aspek Penilaian</label>
                            <input type="text" class="form-control" id="aspek_manual"
                                name="{{ $isManual ? 'aspek_manual' : '' }}"
                                value="{{ $isManual ? $penilaian->aspek : '' }}"
                                placeholder="Contoh: Kejujuran, Kerajinan (pisahkan dengan koma jika lebih dari satu)">
                            <small class="text-muted mt-1 d-block">Masukkan satu kriteria.</small>
                        </div>

                        <div class="form-group">
                            <label for="id_sertifikat" class="form-label">Sertifikat</label>
                            <select name="id_sertifikat" id="id_sertifikat" class="form-select" required>
                                @foreach ($sertifikats as $sertifikat)
                                    <option value="{{ $sertifikat->id_sertifikat }}"
                                        {{ $penilaian->id_sertifikat == $sertifikat->id_sertifikat ? 'selected' : '' }}>
                                        {{ $sertifikat->no_sertifikat }} - {{ $sertifikat->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nilai" class="form-label">Nilai (0 - 100)</label>
                            <input type="number" class="form-control" id="nilai" name="nilai" min="0" max="100" step="any"
                                value="{{ old('nilai', $penilaian->nilai) }}" placeholder="Masukkan nilai (0-100)" required oninput="hitungPredikat()">
                        </div>

                        <div class="form-group">
                            <label for="ket" class="form-label">Keterangan Nilai</label>
                            <select class="form-select" id="ket" name="ket" required>
                                <option value="">Pilih Keterangan</option>
                                @foreach (['A', 'AB', 'B', 'BC', 'C', 'D', 'E'] as $grade)
                                    <option value="{{ $grade }}" {{ old('ket', $penilaian->ket) == $grade ? 'selected' : '' }}>
                                        {{ $grade }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('siswa.shownilai', $penilaian->id_sertifikat) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function hitungPredikat() {
    var nilaiInput = document.getElementById('nilai');
    var nilai = parseFloat(nilaiInput.value);
    var ketSelect = document.getElementById('ket');
    
    if (isNaN(nilai)) {
        ketSelect.value = '';
        return;
    }
    
    if (nilai < 0) {
        nilai = 0;
        nilaiInput.value = 0;
    } else if (nilai > 100) {
        nilai = 100;
        nilaiInput.value = 100;
    }
    
    if (nilai >= 85) {
        ketSelect.value = 'A';
    } else if (nilai >= 80) {
        ketSelect.value = 'AB';
    } else if (nilai >= 75) {
        ketSelect.value = 'B';
    } else if (nilai >= 70) {
        ketSelect.value = 'BC';
    } else if (nilai >= 65) {
        ketSelect.value = 'C';
    } else if (nilai >= 55) {
        ketSelect.value = 'D';
    } else {
        ketSelect.value = 'E';
    }
}

function toggleAspekMode() {
    var modeSelect = document.getElementById('mode_select').checked;
    
    var groupSelect = document.getElementById('group_aspek_select');
    var groupManual = document.getElementById('group_aspek_manual');
    
    var inputManual = document.getElementById('aspek_manual');
    
    if (modeSelect) {
        // Show Select, Hide Manual
        groupSelect.style.display = 'block';
        groupManual.style.display = 'none';
        
        inputManual.removeAttribute('name');
    } else {
        // Show Manual, Hide Select
        groupSelect.style.display = 'none';
        groupManual.style.display = 'block';
        
        inputManual.setAttribute('name', 'aspek_manual');
    }
}
</script>
@endsection
