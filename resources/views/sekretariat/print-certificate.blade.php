@extends('layouts.app')

@section('title', 'Cetak Sertifikat')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('sekretariat.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div>
                    <button type="button" class="btn btn-primary me-2" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Cetak
                    </button>
                    <a href="{{ route('sertifikat.cetak-gabungan-v5', $sertifikat->id_sertifikat) }}" target="_blank" class="btn btn-warning">
                        <i class="fas fa-download me-1"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div id="certificate-preview" style="background: white; padding: 60px 40px; border: 2px solid #667eea; min-height: 600px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                <!-- Header -->
                <div style="text-align: center; border-bottom: 3px solid #667eea; padding-bottom: 30px; margin-bottom: 30px;">
                    <h2 style="margin: 0 0 10px 0; color: #333; font-size: 28px; font-weight: 700;">SERTIFIKAT</h2>
                    <p style="margin: 0; color: #667eea; font-weight: 600; font-size: 14px;">PRAKTIK KERJA LAPANGAN (PKL)</p>
                </div>

                <!-- Content -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <p style="margin: 0 0 20px 0; color: #666; font-size: 13px;">Kami yang bertanda tangan di bawah ini,</p>
                    
                    <div style="text-align: left; display: inline-block; width: 100%; margin: 20px 0;">
                        <p style="margin: 10px 0; color: #333;">
                            <strong>Nama Peserta:</strong> <u>{{ $sertifikat->nama_lengkap }}</u>
                        </p>
                        <p style="margin: 10px 0; color: #333;">
                            <strong>Asal Sekolah/Institusi:</strong> <u>{{ $sertifikat->asal_sekolah }}</u>
                        </p>
                        <p style="margin: 10px 0; color: #333;">
                            <strong>NIM/NIS:</strong> <u>{{ $sertifikat->nim_nis }}</u>
                        </p>
                        <p style="margin: 10px 0; color: #333;">
                            <strong>Jurusan:</strong> <u>{{ $sertifikat->jurusan }}</u>
                        </p>
                        <p style="margin: 10px 0; color: #333;">
                            <strong>Bidang:</strong> <u>{{ $sertifikat->bidang }}</u>
                        </p>
                        <p style="margin: 10px 0; color: #333;">
                            <strong>Periode Magang:</strong> <u>{{ $sertifikat->tgl_mulai->format('d M Y') }} s/d {{ $sertifikat->tgl_selesai->format('d M Y') }}</u>
                        </p>
                    </div>

                    <p style="margin: 30px 0 20px 0; color: #666; font-size: 13px; line-height: 1.6;">
                        Dengan ini menyatakan bahwa yang tersebut di atas telah melaksanakan kegiatan Praktik Kerja Lapangan (PKL) 
                        di {{ $sertifikat->bidang }}, Dinas Komunikasi dan Informatika Kabupaten Cilacap dengan baik dan memuaskan.
                    </p>
                </div>

                <!-- Signature Section -->
                <div style="display: flex; justify-content: space-around; margin-top: 60px;">
                    <div style="text-align: center; width: 45%;">
                        <p style="margin: 0 0 60px 0; color: #333; font-weight: 600;">Kepala Bidang,</p>
                        <p style="margin: 0; color: #333; font-weight: 600; min-height: 20px;">{{ $sertifikat->nama_kabid }}</p>
                        <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">NIP. {{ $sertifikat->nip_kabid }}</p>
                    </div>
                    <div style="text-align: center; width: 45%;">
                        <p style="margin: 0 0 60px 0; color: #333; font-weight: 600;">Kepala Dinas Komunikasi dan Informatika,</p>
                        <p style="margin: 0; color: #333; font-weight: 600; min-height: 20px;">{{ $sertifikat->nama_kadis }}</p>
                        <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">NIP. {{ $sertifikat->nip_kadis }}</p>
                    </div>
                </div>

                <!-- Footer -->
                <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd;">
                    @if($sertifikat->no_sertifikat)
                        <p style="margin: 0; color: #666; font-size: 12px;">No. Sertifikat: {{ $sertifikat->no_sertifikat }}</p>
                    @endif
                    <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">
                        Diterbitkan: {{ $sertifikat->tgl_sertifikat->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body, .container-fluid {
            background: white !important;
        }
        .btn, .row:first-child {
            display: none !important;
        }
        #certificate-preview {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
            page-break-after: avoid;
        }
    }

    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    #certificate-preview {
        font-family: 'Georgia', serif;
        line-height: 1.8;
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-warning:hover {
        transform: translateY(-2px);
    }
</style>
@endsection
