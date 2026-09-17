@extends('layouts.app')

@section('title', 'Detail Sertifikat')

@section('content')
<div class="container">
    <h1 class="mb-4">Detail Sertifikat</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $sertifikat->nama_lengkap }}</h2>
            <p><strong>Asal Sekolah:</strong> {{ $sertifikat->asal_sekolah }}</p>
            <p><strong>NIM/NIS:</strong> {{ $sertifikat->nim_nis }}</p>
            <p><strong>Jurusan:</strong> {{ $sertifikat->jurusan }}</p>
            <p><strong>Tanggal Sertifikat:</strong> 
                {{ \Carbon\Carbon::parse($sertifikat->tgl_sertifikat)->format('d-m-Y') }}
            </p>
        </div>
    </div>

    <!-- Daftar Portofolio -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="mb-3">Daftar Portofolio</h3>

            @if($sertifikat->portofolio && $sertifikat->portofolio->count() > 0)
                <ul>
                    @foreach($sertifikat->portofolio as $item)
                        <li class="mb-3">
                            <strong>{{ $item->nama }}</strong> 
                            <small>({{ $item->bidang }})</small><br>
                            {{ $item->deskripsi ?? '-' }} <br>
                            @if($item->file)
                                <img src="{{ asset('storage/'.$item->file) }}" 
                                     alt="{{ $item->nama }}" width="150" class="mt-2">
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p><i>Belum ada data portofolio.</i></p>
            @endif
        </div>
    </div>

    <!-- Form tambah portofolio (khusus siswa) -->
    @if(Auth::user()->role == 'siswa')
    <div class="card mt-4">
        <div class="card-body">
            <h3 class="mb-3">Tambah Portofolio</h3>

            <form action="{{ route('siswa.sertifikat.portofolio.store', $sertifikat->id_sertifikat) }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Portofolio</label>
                    <input type="text" class="form-control" name="nama" required>
                </div>

                <div class="mb-3">
                    <label for="bidang" class="form-label">Bidang</label>
                    <input type="text" class="form-control" name="bidang" required>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi"></textarea>
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label">Upload File</label>
                    <input type="file" class="form-control" name="file">
                </div>

                <button type="submit" class="btn btn-primary">Tambah Portofolio</button>
            </form>
        </div>
    </div>
    @endif

    <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
