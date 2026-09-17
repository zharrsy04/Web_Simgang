<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sertifikat PKL</title>
    <!-- VERSION 2.0 - UPDATED {{ date('Y-m-d H:i:s') }} -->
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #fff;
        }
        
        /* Page container */
        .page-wrap {
            width: 100%;
            height: 100%;
            position: relative;
        }
        
        /* Triple border effect */
        .border-gold-outer {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 3px solid #d4a650;
        }
        
        .border-blue {
            position: absolute;
            top: 28px;
            left: 28px;
            right: 28px;
            bottom: 28px;
            border: 3px solid #14447b;
        }
        
        .border-gold-inner {
            position: absolute;
            top: 38px;
            left: 38px;
            right: 38px;
            bottom: 38px;
            border: 1px solid #d4a650;
        }
        
        /* Corner ornaments */
        .corner {
            position: absolute;
            width: 18px;
            height: 18px;
            border-color: #d4a650;
        }
        .corner-tl { top: -1px; left: -1px; border-top: 1px solid; border-left: 1px solid; }
        .corner-tr { top: -1px; right: -1px; border-top: 1px solid; border-right: 1px solid; }
        .corner-bl { bottom: -1px; left: -1px; border-bottom: 1px solid; border-left: 1px solid; }
        .corner-br { bottom: -1px; right: -1px; border-bottom: 1px solid; border-right: 1px solid; }
        
        /* Content wrapper */
        .content-wrapper {
            position: absolute;
            top: 60px;
            left: 60px;
            right: 60px;
            bottom: 60px;
        }
        
        /* Watermark */
        .watermark {
            position: absolute;
            top: 380px;
            left: 50%;
            margin-left: -300px;
            font-size: 140px;
            font-weight: 900;
            color: rgba(20, 68, 123, 0.02);
            letter-spacing: 20px;
            z-index: 0;
            width: 600px;
            text-align: center;
        }
        
        /* Header section */
        .header-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .header-table td {
            vertical-align: top;
        }
        .logo-left {
            width: 60px;
            height: auto;
        }
        .header-text {
            padding-left: 10px;
            padding-top: 5px;
        }
        .header-title {
            font-size: 14px;
            font-weight: bold;
            color: #14447b;
            line-height: 1.2;
        }
        .header-subtitle {
            font-size: 12px;
            font-weight: bold;
            color: #14447b;
            margin-top: 2px;
        }
        .logo-right {
            width: 120px;
            height: auto;
        }
        
        /* Certificate content */
        .cert-title {
            font-size: 42px;
            font-weight: bold;
            color: #14447b;
            letter-spacing: 16px;
            text-align: center;
            margin: 10px 0;
        }
        
        .title-line {
            width: 200px;
            height: 2px;
            background: #d4a650;
            margin: 0 auto 5px;
            position: relative;
        }
        
        .diamond {
            position: absolute;
            top: -8px;
            left: 50%;
            margin-left: -6px;
            color: #d4a650;
            font-size: 14px;
        }
        
        .cert-number-box {
            background: rgba(212, 166, 80, 0.08);
            border: 1px solid #d4a650;
            border-radius: 8px;
            padding: 6px 18px;
            display: inline-block;
            margin: 10px 0;
        }
        
        .cert-number {
            font-size: 11px;
            color: #666;
            letter-spacing: 1px;
        }
        
        .intro {
            font-size: 11px;
            color: #444;
            font-style: italic;
            text-align: center;
            line-height: 1.5;
            margin: 10px 60px;
        }
        
        .student-name {
            font-size: 36px;
            font-weight: bold;
            color: #14447b;
            text-align: center;
            margin: 12px 0;
            font-family: 'Times New Roman', Times, serif;
            font-style: italic;
        }
        
        .name-line {
            width: 240px;
            height: 2px;
            background: #d4a650;
            margin: -5px auto 15px;
        }
        
        .info-table {
            width: 550px;
            margin: 0 auto 15px;
            font-size: 10.5px;
        }
        
        .info-table td {
            padding: 3px 0;
            color: #444;
        }
        
        .info-table td:first-child {
            width: 190px;
            color: #666;
        }
        
        .info-table td:nth-child(2) {
            width: 15px;
            text-align: center;
        }
        
        .info-table td:last-child {
            font-weight: bold;
            color: #333;
        }
        
        .description {
            font-size: 10.5px;
            color: #444;
            text-align: justify;
            line-height: 1.6;
            margin: 15px 40px 20px;
        }
        
        .description strong {
            color: #14447b;
            font-weight: bold;
        }
        
        /* Signature section */
        .sig-table {
            width: 100%;
            margin-top: 25px;
            font-size: 10px;
        }
        
        .sig-table td {
            vertical-align: bottom;
            text-align: center;
            padding: 0 10px;
        }
        
        .sig-title {
            margin-bottom: 55px;
            line-height: 1.3;
            color: #444;
        }
        
        .sig-name {
            font-weight: bold;
            color: #14447b;
            text-decoration: underline;
            margin-bottom: 2px;
        }
        
        .sig-detail {
            color: #666;
            line-height: 1.3;
            margin-top: 2px;
        }
        
        .qr-border {
            border: 2px solid #d4a650;
            padding: 6px;
            display: inline-block;
            margin-bottom: 4px;
        }
        
        .qr-label {
            font-size: 8px;
            color: #666;
            margin-top: 2px;
        }
        
        /* Page 2 styles */
        .nilai-title {
            font-size: 25px;
            font-weight: bold;
            color: #14447b;
            letter-spacing: 7px;
            text-align: center;
            margin: 6px 0 3px;
        }
        
        .nilai-subtitle {
            font-size: 11px;
            color: #666;
            letter-spacing: 1px;
            text-align: center;
            margin-bottom: 5px;
        }
        
        .subtitle-line {
            width: 270px;
            height: 2px;
            background: #d4a650;
            margin: 0 auto 10px;
            position: relative;
        }
        
        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 11px;
        }
        
        .table-nilai thead {
            background: #14447b;
            color: #fff;
        }
        
        .table-nilai th {
            padding: 6px 8px;
            font-weight: bold;
            border: 1px solid #0c2f50;
            text-align: center;
        }
        
        .table-nilai tbody tr:nth-child(odd) {
            background: #fff;
        }
        
        .table-nilai tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .table-nilai td {
            padding: 5px 8px;
            border: 1px solid #ddd;
            text-align: center;
            color: #333;
            font-size: 11px;
        }
        
        .table-nilai td:nth-child(2) {
            text-align: left;
            padding-left: 10px;
        }
        
        .table-nilai tfoot {
            background: #f5eed8;
        }
        
        .table-nilai tfoot td {
            padding: 5px 8px;
            border: 1px solid #d4a650;
            font-weight: bold;
            color: #333;
        }
        
        /* Bottom section */
        .bottom-table {
            width: 100%;
            margin-top: 20px;
        }
        
        .bottom-table td {
            vertical-align: top;
        }
        
        .kategori-box {
            border: 1px solid #d4a650;
            border-radius: 6px;
            padding: 12px;
            width: 260px;
        }
        
        .kategori-title {
            font-weight: bold;
            font-size: 10px;
            text-align: center;
            margin-bottom: 8px;
            color: #d4a650;
        }
        
        .kategori-table {
            width: 100%;
            font-size: 9px;
            color: #444;
        }
        
        .kategori-table td:first-child {
            width: 50%;
        }
        
        .kategori-table td:last-child {
            width: 50%;
            text-align: right;
            font-weight: bold;
        }
        
        .sig-nilai-table {
            width: 100%;
            font-size: 10px;
        }
        
        .sig-nilai-table td {
            vertical-align: bottom;
            text-align: center;
        }
    </style>
</head>

<body>
    @php
    $tgl_lahir = new DateTime($sertifikat->tgl_lahir);
    $tgl_mulai = new DateTime($sertifikat->tgl_mulai);
    $tgl_selesai = new DateTime($sertifikat->tgl_selesai);
    $tgl_sekarang = new DateTime();

    $format_tgl_lahir = $tgl_lahir->format('Y-m-d');
    $format_tgl_mulai = $tgl_mulai->format('Y-m-d');
    $format_tgl_selesai = $tgl_selesai->format('Y-m-d');
    $format_tgl_sekarang = $tgl_sekarang->format('Y-m-d');

    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $ex = explode('-', $format_tgl_lahir);
    $ex2 = explode('-', $format_tgl_mulai);
    $ex3 = explode('-', $format_tgl_selesai);
    $ex4 = explode('-', $format_tgl_sekarang);

    $penilaianData = DB::table('tbl_penilaian')
        ->where('id_sertifikat', $sertifikat->id_sertifikat)
        ->get();
    
    $totalNilai = 0;
    $jumlahNilai = 0;
    foreach ($penilaianData as $nilai) {
        if ($nilai->nilai !== null) {
            $totalNilai += $nilai->nilai;
            $jumlahNilai++;
        }
    }
    
    $rataRata = $jumlahNilai > 0 ? $totalNilai / $jumlahNilai : 0;
    
    $predikat = '';
    if ($rataRata >= 90) {
        $predikat = 'Sangat Baik';
    } elseif ($rataRata >= 75) {
        $predikat = 'Baik';
    } elseif ($rataRata >= 60) {
        $predikat = 'Cukup';
    } else {
        $predikat = 'Kurang';
    }
    @endphp

    <!-- Halaman 1: Sertifikat -->
    <div class="page-wrap" style="page-break-after: always;">
        <div class="border-gold-outer"></div>
        <div class="border-blue"></div>
        <div class="border-gold-inner">
            <div class="corner corner-tl"></div>
            <div class="corner corner-tr"></div>
            <div class="corner corner-bl"></div>
            <div class="corner corner-br"></div>
        </div>
        
        <div class="watermark">KOMINFO</div>
        
        <div class="content-wrapper">
            <!-- Header -->
            <table class="header-table">
                <tr>
                    <td style="width: 70%;">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 70px; vertical-align: top;">
                                    <img class="logo-left" src="{{ public_path('images/logo-dinascilacap.png') }}" />
                                </td>
                                <td class="header-text">
                                    <div class="header-title">PEMERINTAH KABUPATEN CILACAP</div>
                                    <div class="header-subtitle">DINAS KOMUNIKASI DAN INFORMATIKA</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 30%; text-align: right; vertical-align: top;">
                        <img class="logo-right" src="{{ public_path('images/logo-Diskominfo.png') }}" />
                    </td>
                </tr>
            </table>

            <!-- Certificate Body -->
            <div style="text-align: center;">
                <div class="cert-title">S E R T I F I K A T</div>
                <div class="title-line"><span class="diamond">♦</span></div>
                
                <div class="cert-number-box">
                    <div class="cert-number">NOMOR : {{ $sertifikat->no_sertifikat }}</div>
                </div>

                <p class="intro">
                    Diberikan oleh Kepala Dinas Komunikasi dan Informatika Kabupaten Cilacap untuk menerangkan bahwa:
                </p>

                <div class="student-name">{{ $sertifikat->nama_lengkap }}</div>
                <div class="name-line"></div>

                <table class="info-table">
                    <tr>
                        <td>Tempat, Tanggal Lahir</td>
                        <td>:</td>
                        <td>{{ $sertifikat->tempat_lahir . ', ' . $ex[2] . ' ' . $bulan[(int) $ex[1]] . ' ' . $ex[0] }}</td>
                    </tr>
                    <tr>
                        <td>NIS/NIM</td>
                        <td>:</td>
                        <td>{{ $sertifikat->nim_nis }}</td>
                    </tr>
                    <tr>
                        <td>Paket Keahlian</td>
                        <td>:</td>
                        <td>{{ $sertifikat->jurusan }}</td>
                    </tr>
                    <tr>
                        <td>Sekolah/Perguruan Tinggi</td>
                        <td>:</td>
                        <td>{{ $sertifikat->asal_sekolah }}</td>
                    </tr>
                </table>

                <p class="description">
                    Telah melaksanakan Praktek Kerja Lapangan/Magang Mandiri pada Dinas Komunikasi dan Informatika Kabupaten Cilacap,
                    terhitung mulai tanggal {{ $ex2[2] . ' ' . $bulan[(int) $ex2[1]] . ' ' . $ex2[0] }} sampai dengan
                    {{ $ex3[2] . ' ' . $bulan[(int) $ex3[1]] . ' ' . $ex3[0] }}, dengan hasil <strong>{{ $predikat }}</strong>.
                </p>

                <!-- Signatures -->
                <table class="sig-table">
                    <tr>
                        <td style="width: 33%;">
                            <div class="sig-title">
                                Kepala Bidang
                            </div>
                            <div class="sig-name">{{ $sertifikat->nama_kabid }}</div>
                            <div class="sig-detail">
                                NIP. {{ $sertifikat->nip_kabid }}
                            </div>
                        </td>
                        <td style="width: 34%;">
                            <div class="qr-border">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(70)->generate('https://simgang.cilacapkab.go.id/verifikasi/' . $sertifikat->id_sertifikat) !!}
                            </div>
                            <div class="qr-label">Scan Validasi</div>
                        </td>
                        <td style="width: 33%;">
                            <div class="sig-title">
                                Cilacap, {{ $ex4[2] . ' ' . $bulan[(int) $ex4[1]] . ' ' . $ex4[0] }}<br>
                                Kepala Dinas Komunikasi dan<br>Informatika<br>Kabupaten Cilacap
                            </div>
                            <div class="sig-name">BUDDY HARYANTO, S.Sos., M.M.</div>
                            <div class="sig-detail">
                                NIP. 19711203 199803 1 002
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Halaman 2: Daftar Nilai -->
    <div class="page-wrap">
        <div class="border-gold-outer"></div>
        <div class="border-blue"></div>
        <div class="border-gold-inner">
            <div class="corner corner-tl"></div>
            <div class="corner corner-tr"></div>
            <div class="corner corner-bl"></div>
            <div class="corner corner-br"></div>
        </div>
        
        <div class="watermark">KOMINFO</div>
        
        <div class="content-wrapper">
            <div style="text-align: center;">
                <div class="nilai-title">DAFTAR NILAI</div>
                <div class="nilai-subtitle">HASIL PELAKSANAAN PRAKTIK KERJA LAPANGAN</div>
                <div class="subtitle-line"><span class="diamond">♦</span></div>
            </div>

            <table class="info-table" style="margin-bottom: 10px;">
                <tr>
                    <td>Nama Siswa/Mahasiswa</td>
                    <td>:</td>
                    <td>{{ $sertifikat->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td>NIS/NIM</td>
                    <td>:</td>
                    <td>{{ $sertifikat->nim_nis }}</td>
                </tr>
                <tr>
                    <td>Jurusan</td>
                    <td>:</td>
                    <td>{{ $sertifikat->jurusan }}</td>
                </tr>
                <tr>
                    <td>Sekolah/Perguruan Tinggi</td>
                    <td>:</td>
                    <td>{{ $sertifikat->asal_sekolah }}</td>
                </tr>
            </table>

            <table class="table-nilai">
                <thead>
                    <tr>
                        <th style="width: 8%;">NO</th>
                        <th style="width: 52%;">ASPEK YANG DINILAI</th>
                        <th style="width: 20%;">NILAI</th>
                        <th style="width: 20%;">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($penilaianData as $penilaian)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $penilaian->aspek ?? '-' }}</td>
                        <td>{{ $penilaian->nilai ?? '-' }}</td>
                        <td>{{ $penilaian->ket ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right; padding-right: 15px;">Rata-rata</td>
                        <td>{{ number_format($rataRata, 2) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; padding-right: 15px;">Predikat</td>
                        <td colspan="2">{{ $predikat }} @if($rataRata >= 90)(A)@elseif($rataRata >= 75)(B)@elseif($rataRata >= 60)(C)@else(D)@endif</td>
                    </tr>
                </tfoot>
            </table>

            <table class="bottom-table">
                <tr>
                    <td style="width: 280px;">
                        <div class="kategori-box">
                            <div class="kategori-title">Kategori Nilai</div>
                            <table class="kategori-table">
                                <tr>
                                    <td>90,00 - 100</td>
                                    <td>Sangat Baik (A)</td>
                                </tr>
                                <tr>
                                    <td>75,00 - 89,99</td>
                                    <td>Baik (B)</td>
                                </tr>
                                <tr>
                                    <td>60,00 - 74,99</td>
                                    <td>Cukup (C)</td>
                                </tr>
                                <tr>
                                    <td>00,00 - 59,00</td>
                                    <td>Kurang (D)</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td style="padding-left: 20px;">
                        <table class="sig-nilai-table">
                            <tr>
                                <td style="width: 50%; padding-right: 15px;">
                                    <div class="sig-title">
                                        Pembimbing Lapangan
                                    </div>
                                    <div class="sig-name">{{ $sertifikat->nama_pembimbing }}</div>
                                    <div class="sig-detail">NIP. {{ $sertifikat->nip_pembimbing }}</div>
                                </td>
                                <td style="width: 50%; padding-left: 15px;">
                                    <div class="sig-title">
                                        Cilacap, {{ $ex4[2] . ' ' . $bulan[(int) $ex4[1]] . ' ' . $ex4[0] }}<br>
                                        Kepala Bidang
                                    </div>
                                    <div class="sig-name">{{ $sertifikat->nama_kabid }}</div>
                                    <div class="sig-detail">NIP. {{ $sertifikat->nip_kabid }}</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
