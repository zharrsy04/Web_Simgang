@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">Bidang Magang Saya</h2>
            <p class="text-muted">Lihat detail siswa magang per bidang yang Anda tangani</p>
        </div>
    </div>

    @if($mentorBidang->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Anda belum menangani siswa di bidang manapun.
        </div>
    @else
        <div class="row">
            @foreach($mentorBidang as $bidang)
                @php
                    $siswaCount = \App\Models\SertifikatPKL::where('bidang', $bidang)
                        ->where('created_id', Auth::id())
                        ->count();
                    $siswaAktif = \App\Models\SertifikatPKL::where('bidang', $bidang)
                        ->where('created_id', Auth::id())
                        ->where('sts_diambil', false)
                        ->whereDate('tgl_mulai', '<=', now())
                        ->whereDate('tgl_selesai', '>=', now())
                        ->count();
                @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm hover-shadow" style="cursor: pointer; transition: all 0.3s;">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div>
                                    <h5 class="card-title mb-1" style="color: #2c3e50;">{{ $bidang }}</h5>
                                    <small class="text-muted">Bidang Magang</small>
                                </div>
                                <span class="badge badge-primary">{{ $siswaCount }} Siswa</span>
                            </div>
                            
                            <div class="row text-center mt-3 pt-3 border-top">
                                <div class="col-6">
                                    <small class="text-muted d-block">Aktif</small>
                                    <h5 class="mb-0" style="color: #27ae60;">{{ $siswaAktif }}</h5>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Selesai</small>
                                    <h5 class="mb-0" style="color: #3498db;">
                                        {{ $siswaCount - $siswaAktif }}
                                    </h5>
                                </div>
                            </div>

                            <a href="{{ route('sekretariat.field-detail', ['bidang' => $bidang]) }}" 
                               class="btn btn-outline-primary btn-sm mt-3 w-100">
                                <i class="fas fa-arrow-right"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-2px);
    }
</style>
@endsection
