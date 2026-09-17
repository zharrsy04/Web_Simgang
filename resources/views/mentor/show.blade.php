@extends('layouts.app')

@section('title', 'Detail Sertifikat')

@section('content')
    <h1 class="mb-4">Detail Sertifikat</h1>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title mb-4">{{ $sertifikat->nama_lengkap }}</h2>

            <!-- Informasi Umum -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Asal Sekolah:</strong> {{ $sertifikat->asal_sekolah }}</p>
                    <p><strong>NIM/NIS:</strong> {{ $sertifikat->nim_nis }}</p>
                    <p><strong>Tempat Lahir:</strong> {{ $sertifikat->tempat_lahir }}</p>
                    <p><strong>Tanggal Lahir:</strong> {{ \Carbon\Carbon::parse($sertifikat->tgl_lahir)->format('d-m-Y') }}</p>
                    <p><strong>Jurusan:</strong> {{ $sertifikat->jurusan }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($sertifikat->tgl_mulai)->format('d-m-Y') }}</p>
                    <p><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($sertifikat->tgl_selesai)->format('d-m-Y') }}</p>
                    <p><strong>Tanggal Sertifikat:</strong> {{ \Carbon\Carbon::parse($sertifikat->tgl_sertifikat)->format('d-m-Y') }}</p>
                </div>
            </div>

            <!-- Pembimbing -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Nama Pembimbing:</strong> {{ $sertifikat->nm_pembimbing }}</p>
                    <p><strong>NIP Pembimbing:</strong> {{ $sertifikat->nik_pembimbing }}</p>
                    <p><strong>Jabatan Pembimbing:</strong> {{ $sertifikat->jabatan_pembimbing }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>No Sertifikat:</strong> {{ $sertifikat->no_sertifikat }}</p>
                </div>
            </div>

            <!-- Kadis -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Nama Kadis:</strong> {{ $sertifikat->nm_kadis }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>NIP Kadis:</strong> {{ $sertifikat->nip_kadis }}</p>
                </div>
            </div>

            <!-- Status Approval -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <p>
                        <strong>Approve Pembimbing:</strong>
                        <br>
                        <button class="btn {{ $sertifikat->aprove_pembimbing ? 'btn-success' : 'btn-danger' }}">
                            {{ $sertifikat->aprove_pembimbing ? 'Sudah' : 'Belum' }}
                        </button>
                    </p>
                </div>
                <div class="col-md-4">
                    <p>
                        <strong>Approve Kadis:</strong>
                        <br>
                        <button class="btn {{ $sertifikat->approve_kadis ? 'btn-success' : 'btn-danger' }}">
                            {{ $sertifikat->approve_kadis ? 'Sudah' : 'Belum' }}
                        </button>
                    </p>
                </div>
                <div class="col-md-4">
                    <p>
                        <strong>Status Diambil:</strong>
                        <br>
                        <button class="btn {{ $sertifikat->sts_diambil ? 'btn-success' : 'btn-danger' }}">
                            {{ $sertifikat->sts_diambil ? 'Sudah' : 'Belum' }}
                        </button>
                    </p>
                </div>
            </div>

            <!-- Penempatan Bidang Section -->
            <div class="card mt-4">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-building me-2"></i>Penempatan Bidang</h5>
                        @php
                            $penempatan = $sertifikat->penempatan;
                        @endphp
                        @if(!$penempatan)
                            <button type="button" class="btn btn-light btn-sm" onclick="openPenempatanModal({{ $sertifikat->id_sertifikat }})">
                                <i class="fas fa-plus me-1"></i>Tempatkan Bidang
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($penempatan)
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Bidang Asal (Pilihan):</strong>
                                    <br>
                                    <span class="badge badge-warning">{{ $penempatan->bidang_asal }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Bidang Penempatan:</strong>
                                    <br>
                                    <span class="badge badge-success">{{ $penempatan->bidang_ditempatkan }}</span>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Ditempatkan oleh:</strong> {{ $penempatan->ditempatkan_oleh }}
                                    ({{ $penempatan->user->nama_lengkap ?? $penempatan->user->username }})
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Tanggal:</strong> {{ $penempatan->tanggal_penempatan->format('d-m-Y H:i') }}
                                </small>
                            </div>
                        </div>
                        @if($penempatan->keterangan)
                            <hr>
                            <p class="mb-0">
                                <strong>Keterangan:</strong> {{ $penempatan->keterangan }}
                            </p>
                        @endif
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Siswa ini belum ditempatkan ke bidang apapun. Klik tombol "Tempatkan Bidang" untuk melakukan penempatan.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Daftar Portofolio -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Daftar Portofolio</h4>
                </div>
                <div class="card-body">
                    @forelse($sertifikat->portofolio as $porto)
                        <div class="mb-3">
                            <h5>{{ $porto->nama }} <small class="text-muted">({{ $porto->bidang }})</small></h5>
                            <p>{{ $porto->deskripsi }}</p>
                            @if($porto->file)
                                <p>
                                    <img src="{{ asset('storage/'.$porto->file) }}" 
                                        alt="{{ $porto->nama }}" 
                                        class="img-fluid rounded" 
                                        style="max-width: 200px;">
                                </p>
                            @endif
                            <hr>
                        </div>
                    @empty
                        <p><i>Tidak ada portofolio untuk sertifikat ini.</i></p>
                    @endforelse
                </div>
            </div>
            <!-- Kembali Button -->
            <a href="{{ route('mentor.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <!-- Include Modal Penempatan Bidang -->
    @include('mentor.penempatan-bidang-modal')
@endsection
