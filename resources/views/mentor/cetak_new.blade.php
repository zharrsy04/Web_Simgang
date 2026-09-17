<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sertifikat PKL</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0mm;
        }
        
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        
        /* Fixed page container - CRITICAL for 2 pages only */
        .page {
            width: 297mm;
            height: 210mm;
            position: relative;
            page-break-after: always;
            overflow: hidden;
        }
        
        /* Border system using nested tables */
        .border-outer {
            width: 277mm;
            height: 190mm;
            margin: 10mm auto 0;
            border: 3px solid #d4a650;
            background: #fff;
        }
        
        .border-middle {
            width: 269mm;
            height: 182mm;
            margin: 4mm auto 0;
            border: 3px solid #14447b;
        }
        
        .border-inner {
            width: 261mm;
            height: 174mm;
            margin: 4mm auto 0;
            border: 1px solid #d4a650;
            position: relative;
            background: #fff;
        }
        
        /* Corner ornaments - simplified */
        .corner {
            position: absolute;
            width: 15px;
            height: 15px;
        }
        .corner-tl { top: 0; left: 0; border-top: 1px solid #d4a650; border-left: 1px solid #d4a650; }
        .corner-tr { top: 0; right: 0; border-top: 1px solid #d4a650; border-right: 1px solid #d4a650; }
        .corner-bl { bottom: 0; left: 0; border-bottom: 1px solid #d4a650; border-left: 1px solid #d4a650; }
        .corner-br { bottom: 0; right: 0; border-bottom: 1px solid #d4a650; border-right: 1px solid #d4a650; }
        
        /* Watermark - VERY faint text, with white background on content to prevent overlap */
        .watermark {
            position: absolute;
            top: 70mm;
            left: 50%;
            width: 600px;
            margin-left: -300px;
            font-size: 120px;
            font-weight: 900;
            color: #f0f0f0;
            letter-spacing: 15px;
            text-align: center;
            z-index: 0;
        }
        
        /* Content area with white background */
        .content {
            position: relative;
            padding: 15mm 20mm;
            background: #fff;
            z-index: 1;
        }
        
        /* Header - pure table layout */
        .header-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        
        .header-table td {
            vertical-align: middle;
            padding: 0;
        }
        
        .logo-cell {
            width: 60px;
            padding-right: 10px;
        }
        
        .logo-left {
            width: 55px;
            height: auto;
            display: block;
        }
        
        .header-text-cell {
            text-align: left;
        }
        
        .header-title {
            font-size: 13px;
            font-weight: bold;
            color: #14447b;
            line-height: 1.2;
            margin: 0;
        }
        
        .header-subtitle {
            font-size: 11px;
            font-weight: bold;
            color: #14447b;
            margin: 1px 0 0;
        }
        
        .logo-right-cell {
            width: 120px;
            text-align: right;
        }
        
        .logo-right {
            width: 115px;
            height: auto;
            display: inline-block;
        }
        
        /* Certificate title */
        .cert-title {
            font-size: 38px;
            font-weight: bold;
            color: #14447b;
            letter-spacing: 14px;
            text-align: center;
            margin: 8px 0 5px;
        }
        
        .title-divider {
            width: 180px;
            height: 0;
            border-bottom: 2px solid #d4a650;
            margin: 0 auto;
            position: relative;
        }
        
        .diamond {
            position: absolute;
            top: -9px;
            left: 50%;
            margin-left: -6px;
            color: #d4a650;
            font-size: 12px;
            background: #fff;
            padding: 0 4px;
        }
        
        .cert-number-box {
            background: #fef9f0;
            border: 1px solid #d4a650;
            padding: 5px 16px;
            display: inline-block;
            margin: 10px 0;
        }
        
        .cert-number {
            font-size: 10px;
            color: #666;
            letter-spacing: 0.8px;
        }
        
        .intro {
            font-size: 10.5px;
            color: #444;
            font-style: italic;
            text-align: center;
            line-height: 1.4;
            margin: 10px 50px;
        }
        
        .student-name {
            font-size: 32px;
            font-weight: bold;
            color: #14447b;
            text-align: center;
            margin: 10px 0 5px;
            font-family: 'Times New Roman', Times, serif;
            font-style: italic;
        }
        
        .name-divider {
            width: 220px;
            height: 0;
            border-bottom: 2px solid #d4a650;
            margin: 0 auto 12px;
        }
        
        /* Info table */
        .info-table {
            width: 520px;
            margin: 0 auto 12px;
            font-size: 10px;
            border-collapse: collapse;
        }
        
        .info-table td {
            padding: 2px 0;
            color: #444;
        }
        
        .info-table td:first-child {
            width: 180px;
            color: #666;
        }
        
        .info-table td:nth-child(2) {
            width: 12px;
            text-align: center;
        }
        
        .info-table td:last-child {
            font-weight: bold;
            color: #333;
        }
        
        .description {
            font-size: 10px;
            color: #444;
            text-align: justify;
            line-height: 1.5;
            margin: 12px 35px 18px;
        }
        
        .description strong {
            color: #14447b;
            font-weight: bold;
        }
        
        /* Signature table */
        .sig-table {
            width: 100%;
            margin-top: 20px;
            font-size: 9.5px;
            border-collapse: collapse;
        }
        
        .sig-table td {
            vertical-align: bottom;
            text-align: center;
            padding: 0 8px;
        }
        
        .sig-title {
            margin-bottom: 50px;
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
            line-height: 1.2;
            margin-top: 1px;
        }
        
        .qr-wrapper {
            border: 2px solid #d4a650;
            padding: 5px;
            display: inline-block;
            margin-bottom: 3px;
        }
        
        .qr-label {
            font-size: 8px;
            color: #666;
        }
        
        /* Page 2 - Nilai */
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
            text-align: center;
            margin: 0 0 5px;
            line-height: 1.3;
        }
        
        .subtitle-divider {
            width: 270px;
            height: 0;
            border-bottom: 2px solid #d4a650;
            margin: 0 auto 10px;
            position: relative;
        }
        
        /* Nilai table */
        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 11px;
        }
        
        .table-nilai thead {
            background-color: #14447b;
            color: #fff;
        }
        
        .table-nilai th {
            padding: 6px 8px;
            font-weight: bold;
            border: 1px solid #0c2f50;
            text-align: center;
        }
        
        .table-nilai tbody tr {
            background-color: #fff;
        }
        
        .table-nilai tbody tr:nth-child(even) {
            background-color: #f9f9f9;
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
            background-color: #f5eed8;
        }
        
        .table-nilai tfoot td {
            padding: 5px 8px;
            border: 1px solid #d4a650;
            font-weight: bold;
            color: #333;
            font-size: 11px;
        }
        
        /* Bottom section table */
        .bottom-table {
            width: 100%;
            margin-top: 6px;
            border-collapse: collapse;
        }
        
        .bottom-table td {
            vertical-align: top;
            padding: 0;
        }
        
        /* Kategori box - simplified for DomPDF */
        .kategori-cell {
            width: 250px;
        }
        
        .kategori-box {
            border: 1px solid #d4a650;
            padding: 10px;
            background: #fff;
        }
        
        .kategori-title {
            font-weight: bold;
            font-size: 9.5px;
            text-align: center;
            margin-bottom: 6px;
            color: #d4a650;
        }
        
        .kategori-list {
            width: 100%;
            border-collapse: collapse;
        }
        
        .kategori-list td {
            padding: 3px 0;
            font-size: 8.5px;
            color: #444;
        }
        
        .kategori-list td:first-child {
            width: 45%;
            text-align: left;
        }
        
        .kategori-list td:last-child {
            width: 55%;
            text-align: right;
            font-weight: bold;
        }
        
        /* Signature nilai table */
        .sig-nilai-cell {
            padding-left: 15px;
        }
        
        .sig-nilai-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .sig-nilai-table td {
            vertical-align: bottom;
            text-align: center;
            font-size: 9.5px;
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

    $countNilai = isset($penilaians) ? $penilaians->count() : (isset($penilaianData) ? count($penilaianData) : 0);
    if ($countNilai <= 5) {
        $tdPadding = '9px 12px';
        $thPadding = '10px 12px';
        $tdFontSize = '13.5px';
        $thFontSize = '14px';
        $infoMargin = '0 auto 14px';
        $infoFontSize = '13.5px';
        $infoTdPadding = '3.5px 0';
        $tableMargin = '14px 0';
        $bottomMargin = '30px';
        $sigMarginBottom = '45px';
        $sigFontSize = '13px';
        $titleFontSize = '28px';
        $subtitleFontSize = '13.5px';
    } elseif ($countNilai <= 8) {
        $tdPadding = '6px 10px';
        $thPadding = '7px 10px';
        $tdFontSize = '12.5px';
        $thFontSize = '13px';
        $infoMargin = '0 auto 10px';
        $infoFontSize = '13px';
        $infoTdPadding = '2.5px 0';
        $tableMargin = '10px 0';
        $bottomMargin = '25px';
        $sigMarginBottom = '36px';
        $sigFontSize = '12.5px';
        $titleFontSize = '26px';
        $subtitleFontSize = '13px';
    } else {
        $tdPadding = '4px 8px';
        $thPadding = '5px 8px';
        $tdFontSize = '11.5px';
        $thFontSize = '12px';
        $infoMargin = '0 auto 6px';
        $infoFontSize = '12px';
        $infoTdPadding = '1.5px 0';
        $tableMargin = '10px 0';
        $bottomMargin = '22px';
        $sigMarginBottom = '34px';
        $sigFontSize = '12px';
        $titleFontSize = '28px';
        $subtitleFontSize = '13.5px';
    }
    @endphp

    <!-- HALAMAN 1: SERTIFIKAT -->
    <div class="page">
        <div class="border-outer">
            <div class="border-middle">
                <div class="border-inner">
                    <div class="corner corner-tl"></div>
                    <div class="corner corner-tr"></div>
                    <div class="corner corner-bl"></div>
                    <div class="corner corner-br"></div>
                    
                    <!-- Watermark - very faint -->
                    <div class="watermark">KOMINFO</div>
                    
                    <div class="content">
                        <!-- Header -->
                        <table class="header-table">
                            <tr>
                                <td class="logo-cell">
                                    <img class="logo-left" src="{{ public_path('images/logo-dinascilacap.png') }}" alt="Logo Cilacap" />
                                </td>
                                <td class="header-text-cell">
                                    <div class="header-title">PEMERINTAH KABUPATEN CILACAP</div>
                                    <div class="header-subtitle">DINAS KOMUNIKASI DAN INFORMATIKA</div>
                                </td>
                                <td class="logo-right-cell">
                                    <img class="logo-right" src="{{ public_path('images/logo-Diskominfo.png') }}" alt="Logo Diskominfo" />
                                </td>
                            </tr>
                        </table>

                        <!-- Certificate Body -->
                        <div style="text-align: center;">
                            <div class="cert-title">S E R T I F I K A T</div>
                            <div class="title-divider"><span class="diamond">♦</span></div>
                            
                            <div class="cert-number-box">
                                <div class="cert-number">NOMOR : {{ $sertifikat->no_sertifikat }}</div>
                            </div>

                            <p class="intro">
                                Diberikan oleh Kepala Dinas Komunikasi dan Informatika Kabupaten Cilacap untuk menerangkan bahwa:
                            </p>

                            <div class="student-name">{{ $sertifikat->nama_lengkap }}</div>
                            <div class="name-divider"></div>

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
                                        <div class="qr-wrapper">
                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(65)->generate('https://simgang.cilacapkab.go.id/verifikasi/' . $sertifikat->id_sertifikat) !!}
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
            </div>
        </div>
    </div>

    <!-- HALAMAN 2: DAFTAR NILAI -->
    <div class="page">
        <div class="border-outer">
            <div class="border-middle">
                <div class="border-inner">
                    <div class="corner corner-tl"></div>
                    <div class="corner corner-tr"></div>
                    <div class="corner corner-bl"></div>
                    <div class="corner corner-br"></div>
                    
                    <!-- Watermark -->
                    <div class="watermark">KOMINFO</div>
                    
                    <div class="content">
                        <div style="text-align: center;">
                            <div class="nilai-title" style="font-size: {{ $titleFontSize }};">DAFTAR NILAI</div>
                            <div class="nilai-subtitle" style="font-size: {{ $subtitleFontSize }};">HASIL PELAKSANAAN PRAKTIK KERJA LAPANGAN</div>
                            <div class="subtitle-divider"><span class="diamond">♦</span></div>
                        </div>

                        <table class="info-table" style="margin: {{ $infoMargin }}; font-size: {{ $infoFontSize }};">
                            <tr>
                                <td style="padding: {{ $infoTdPadding }};">Nama Siswa/Mahasiswa</td>
                                <td style="padding: {{ $infoTdPadding }};">:</td>
                                <td style="padding: {{ $infoTdPadding }};">{{ $sertifikat->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td style="padding: {{ $infoTdPadding }};">NIS/NIM</td>
                                <td style="padding: {{ $infoTdPadding }};">:</td>
                                <td style="padding: {{ $infoTdPadding }};">{{ $sertifikat->nim_nis }}</td>
                            </tr>
                            <tr>
                                <td style="padding: {{ $infoTdPadding }};">Jurusan</td>
                                <td style="padding: {{ $infoTdPadding }};">:</td>
                                <td style="padding: {{ $infoTdPadding }};">{{ $sertifikat->jurusan }}</td>
                            </tr>
                            <tr>
                                <td style="padding: {{ $infoTdPadding }};">Sekolah/Perguruan Tinggi</td>
                                <td style="padding: {{ $infoTdPadding }};">:</td>
                                <td style="padding: {{ $infoTdPadding }};">{{ $sertifikat->asal_sekolah }}</td>
                            </tr>
                        </table>

                        <table class="table-nilai" style="margin: {{ $tableMargin }}; font-size: {{ $tdFontSize }};">
                            <thead>
                                <tr>
                                    <th style="width: 8%; padding: {{ $thPadding }}; font-size: {{ $thFontSize }};">NO</th>
                                    <th style="width: 52%; padding: {{ $thPadding }}; font-size: {{ $thFontSize }};">ASPEK YANG DINILAI</th>
                                    <th style="width: 20%; padding: {{ $thPadding }}; font-size: {{ $thFontSize }};">NILAI</th>
                                    <th style="width: 20%; padding: {{ $thPadding }}; font-size: {{ $thFontSize }};">KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach($penilaianData as $penilaian)
                                <tr>
                                    <td style="padding: {{ $tdPadding }}; font-size: {{ $tdFontSize }};">{{ $no++ }}</td>
                                    <td style="padding: {{ $tdPadding }}; font-size: {{ $tdFontSize }};">{{ $penilaian->kriteria ?? $penilaian->aspek ?? '-' }}</td>
                                    <td style="padding: {{ $tdPadding }}; font-size: {{ $tdFontSize }};">{{ $penilaian->nilai ?? '-' }}</td>
                                    <td style="padding: {{ $tdPadding }}; font-size: {{ $tdFontSize }};">{{ $penilaian->ket ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" style="text-align: right; padding-right: 12px; padding-top: {{ explode(' ', $tdPadding)[0] }}; padding-bottom: {{ explode(' ', $tdPadding)[0] }}; font-size: {{ $tdFontSize }};">Rata-rata</td>
                                    <td style="padding-top: {{ explode(' ', $tdPadding)[0] }}; padding-bottom: {{ explode(' ', $tdPadding)[0] }}; font-size: {{ $tdFontSize }};">{{ number_format($rataRata, 2) }}</td>
                                    <td style="padding-top: {{ explode(' ', $tdPadding)[0] }}; padding-bottom: {{ explode(' ', $tdPadding)[0] }}; font-size: {{ $tdFontSize }};"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: right; padding-right: 12px; padding-top: {{ explode(' ', $tdPadding)[0] }}; padding-bottom: {{ explode(' ', $tdPadding)[0] }}; font-size: {{ $tdFontSize }};">Predikat</td>
                                    <td colspan="2" style="padding-top: {{ explode(' ', $tdPadding)[0] }}; padding-bottom: {{ explode(' ', $tdPadding)[0] }}; font-size: {{ $tdFontSize }};">{{ $predikat }} @if($rataRata >= 90)(A)@elseif($rataRata >= 75)(B)@elseif($rataRata >= 60)(C)@else(D)@endif</td>
                                </tr>
                            </tfoot>
                        </table>

                        <table class="bottom-table" style="margin-top: {{ $bottomMargin }};">
                            <tr>
                                <td class="kategori-cell">
                                    <div class="kategori-box">
                                        <div class="kategori-title">Kategori Nilai</div>
                                        <table class="kategori-list">
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
                                <td class="sig-nilai-cell">
                                    <table class="sig-nilai-table" style="font-size: {{ $sigFontSize }};">
                                        <tr>
                                            <td style="width: 50%; padding-right: 12px;">
                                                <div class="sig-title" style="margin-bottom: {{ $sigMarginBottom }};">
                                                    Pembimbing Lapangan
                                                </div>
                                                <div class="sig-name">{{ $sertifikat->nama_pembimbing }}</div>
                                                <div class="sig-detail">NIP. {{ $sertifikat->nip_pembimbing }}</div>
                                            </td>
                                            <td style="width: 50%; padding-left: 12px;">
                                                <div class="sig-title" style="margin-bottom: {{ $sigMarginBottom }};">
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
            </div>
        </div>
    </div>
</body>
</html>
