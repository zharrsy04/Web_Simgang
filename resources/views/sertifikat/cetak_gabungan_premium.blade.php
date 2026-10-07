<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat dan Daftar Nilai</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            background: #fff;
        }

        /* Page container */
        .page {
            width: 297mm;
            height: 210mm;
            position: relative;
            page-break-after: always;
            background: #fff;
            margin: 0 auto;
        }

        /* Triple border - Premium Design */
        .border-outer {
            position: absolute;
            top: 4mm;
            left: 4mm;
            right: 4mm;
            height: 200mm;
            border: 3px solid #d4af37;
            background: #fff;
        }

        .border-middle {
            position: absolute;
            top: 2.5mm;
            left: 2.5mm;
            right: 2.5mm;
            height: 195mm;
            border: 3px solid #1e3a5f;
        }

        .border-inner {
            position: absolute;
            top: 2mm;
            left: 2mm;
            right: 2mm;
            height: 191mm;
            border: 1px solid #d4af37;
            background: #fff;
        }

        /* Corner L decorations */
        .corner {
            position: absolute;
            width: 18px;
            height: 18px;
            border-color: #d4af37;
        }
        .corner-tl { top: 3px; left: 3px; border-top: 2px solid; border-left: 2px solid; }
        .corner-tr { top: 3px; right: 3px; border-top: 2px solid; border-right: 2px solid; }
        .corner-bl { bottom: 3px; left: 3px; border-bottom: 2px solid; border-left: 2px solid; }
        .corner-br { bottom: 3px; right: 3px; border-bottom: 2px solid; border-right: 2px solid; }

        /* Watermark KOMINFO */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 160px;
            font-weight: 900;
            color: rgba(245, 245, 245, 0.25);
            letter-spacing: 20px;
            z-index: 0;
            font-family: Arial, sans-serif;
        }

        /* Content wrapper - EXPANDED AREA */
        .content {
            position: absolute;
            top: 8mm;
            left: 10mm;
            right: 10mm;
            bottom: 8mm;
            z-index: 1;
        }

        /* Header with logos - BIGGER & NO CUT OFF */
        .header {
            width: 100%;
            margin-bottom: 12px;
        }

        .logo-left {
            width: 150px;
            height: auto;
            display: block;
        }

        .logo-right {
            width: 195px;
            height: auto;
            display: block;
            float: right;
        }

        /* Certificate Title - CLEANER DESIGN */
        .cert-title {
            font-size: 32px;
            font-weight: bold;
            color: #1e3a5f;
            letter-spacing: 12px;
            text-align: center;
            margin: 12px 0 8px;
            font-family: Georgia, serif;
        }

        .title-line {
            width: 280px;
            height: 0;
            border-bottom: 2px solid #d4af37;
            margin: 0 auto 8px;
            position: relative;
        }

        .diamond {
            position: absolute;
            top: -7px;
            left: 50%;
            margin-left: -6px;
            color: #d4af37;
            font-size: 14px;
            background: #fff;
            padding: 0 5px;
        }

        .cert-number {
            font-size: 13px;
            color: #888;
            text-align: center;
            margin: 0 auto 12px;
            padding: 8px 20px;
            background: #f5f5f5;
            border: 1px solid #e0e0e0;
            display: inline-block;
            letter-spacing: 1px;
            font-weight: 500;
            border-radius: 3px;
        }

        .intro {
            font-size: 13.5px;
            color: #444;
            font-style: italic;
            text-align: center;
            line-height: 1.5;
            margin: 10px 60px 12px;
        }

        .student-name {
            font-size: 40px;
            font-weight: bold;
            color: #1e3a5f;
            text-align: center;
            margin: 10px 0 5px;
            font-style: italic;
        }

        .name-line {
            width: 260px;
            height: 0;
            border-bottom: 2px solid #d4af37;
            margin: 0 auto 12px;
        }

        /* Info table - CLEANER */
        .info-table {
            width: 500px;
            margin: 0 auto 14px;
            font-size: 13.5px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 3px 0;
            color: #444;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 185px;
            color: #666;
            font-weight: 500;
        }

        .info-table td:nth-child(2) {
            width: 15px;
            text-align: center;
            color: #d4af37;
            font-weight: bold;
            font-size: 14px;
        }

        .info-table td:last-child {
            font-weight: 600;
            color: #000;
        }

        .description {
            font-size: 13.5px;
            color: #444;
            text-align: center;
            line-height: 1.6;
            margin: 14px 50px 20px;
        }

        .description strong {
            color: #1e3a5f;
            font-weight: bold;
        }

        /* Signatures - BIGGER TEXT */
        .sig-table {
            width: 100%;
            margin-top: 30px;
            font-size: 13px;
            border-collapse: collapse;
        }

        .sig-table td {
            vertical-align: bottom;
            text-align: center;
            padding: 0 10px;
        }

        .sig-title {
            margin-bottom: 48px;
            line-height: 1.4;
            color: #333;
        }

        .sig-name {
            font-weight: bold;
            color: #1e3a5f;
            text-decoration: underline;
            font-size: 14px;
        }

        .sig-detail {
            color: #555;
            line-height: 1.3;
            margin-top: 2px;
            font-size: 12.5px;
        }

        .qr-box {
            border: 2px solid #d4af37;
            padding: 6px;
            display: inline-block;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .qr-label {
            font-size: 11px;
            color: #555;
            margin-top: 4px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* PAGE 2 - Daftar Nilai - ELEGANT CONSTANT OUTER DESIGN */
        .nilai-title {
            font-size: 28px;
            font-weight: bold;
            color: #1e3a5f;
            letter-spacing: 8px;
            text-align: center;
            margin: 8px 0 4px;
            font-family: Georgia, serif;
        }

        .nilai-subtitle {
            font-size: 13.5px;
            color: #555;
            text-align: center;
            margin: 0 0 6px;
            line-height: 1.3;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .subtitle-line {
            width: 280px;
            height: 0;
            border-bottom: 2px solid #d4af37;
            margin: 0 auto 12px;
            position: relative;
        }

        /* Nilai table - DYNAMIC ROW PADDING & FONT */
        .table-nilai {
            width: 100%;
            border-collapse: collapse;
        }

        .table-nilai thead {
            background: #1e3a5f !important;
            color: #fff !important;
        }

        .table-nilai th {
            font-weight: bold;
            border: 1.5px solid #c9a227;
            text-align: center;
            letter-spacing: 0.3px;
            background: #1e3a5f !important;
            color: #fff !important;
        }

        .table-nilai tbody tr {
            background-color: #fff;
        }

        .table-nilai td {
            border: 1.5px solid #c9a227;
            text-align: center;
            color: #333;
        }

        .table-nilai td:nth-child(2) {
            text-align: left;
            padding-left: 10px;
        }

        /* Footer rows styling */
        .table-nilai .row-rata-rata td, .table-nilai .row-predikat td {
            background-color: #f5f0e8 !important;
            font-weight: bold;
            color: #1e3a5f;
            border: 1.5px solid #c9a227;
        }

        /* Bottom section - CONSTANT ELEGANT SIZES LIKE IMAGE 1 */
        .bottom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bottom-table td {
            vertical-align: top;
            padding: 0;
        }

        .kategori-box {
            width: 250px;
            border: 1.5px solid #d4af37;
            padding: 10px 12px;
            background: linear-gradient(to bottom, #fefbf3, #fef9ee);
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        .kategori-title {
            font-weight: bold;
            font-size: 12.5px;
            text-align: center;
            margin-bottom: 6px;
            color: #1e3a5f;
            letter-spacing: 0.5px;
        }

        .kategori-list {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
        }

        .kategori-list td {
            padding: 2.5px 0;
            color: #333;
        }

        .kategori-list td:first-child {
            width: 48%;
        }

        .kategori-list td:last-child {
            width: 52%;
            text-align: right;
            font-weight: bold;
            color: #1e3a5f;
        }

        .sig-nilai {
            padding-left: 16px;
        }

        .sig-nilai-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .sig-nilai-table td {
            vertical-align: bottom;
            text-align: center;
            padding: 0 10px;
        }

        .sig-nilai-table .sig-title {
            margin-bottom: 35px;
            line-height: 1.35;
            color: #333;
        }

        /* Prevent blank pages */
        .page:last-child {
            page-break-after: avoid !important;
        }

        .bottom {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    @php
    $tgl_lahir = new DateTime($sertifikat->tgl_lahir);
    $tgl_mulai = new DateTime($sertifikat->tgl_mulai);
    $tgl_selesai = new DateTime($sertifikat->tgl_selesai);
    $tgl_sertifikat = new DateTime($sertifikat->tgl_sertifikat);

    $format_tgl_lahir = $tgl_lahir->format('Y-m-d');
    $format_tgl_mulai = $tgl_mulai->format('Y-m-d');
    $format_tgl_selesai = $tgl_selesai->format('Y-m-d');
    $format_tgl_sertifikat = $tgl_sertifikat->format('Y-m-d');

    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $ex = explode('-', $format_tgl_lahir);
    $ex2 = explode('-', $format_tgl_mulai);
    $ex3 = explode('-', $format_tgl_selesai);
    $ex4 = explode('-', $format_tgl_sertifikat);

    $totalNilai = 0;
    $jumlahNilai = $penilaians->count();
    foreach ($penilaians as $penilaian) {
        $totalNilai += $penilaian->nilai;
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

    // HANYA TABEL YANG BERUBAH DINAMIS BERDASARKAN JUMLAH NILAI
    // Elemen Luar (Judul, Info Siswa, Kategori Nilai, Tanda Tangan) tetap konsisten & rapi seperti Gambar 1
    $countNilai = $penilaians->count();
    if ($countNilai <= 5) {
        $tdPadding = '9px 12px';
        $thPadding = '10px 12px';
        $tdFontSize = '13.5px';
        $thFontSize = '14px';
        $tableMargin = '14px 0';
        $bottomMargin = '30px';
        $sigMarginBottom = '45px';
    } elseif ($countNilai <= 8) {
        $tdPadding = '6px 10px';
        $thPadding = '7px 10px';
        $tdFontSize = '12.5px';
        $thFontSize = '13px';
        $tableMargin = '10px 0';
        $bottomMargin = '25px';
        $sigMarginBottom = '38px';
    } else {
        $tdPadding = '4px 8px';
        $thPadding = '5px 8px';
        $tdFontSize = '11.5px';
        $thFontSize = '12px';
        $tableMargin = '10px 0';
        $bottomMargin = '22px';
        $sigMarginBottom = '34px';
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

                    <div class="watermark">KOMINFO</div>

                    <div class="content">
                        <div class="header">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="width: 50%; vertical-align: middle; text-align: left; padding-right: 25px;">
                                        <img class="logo-left" src="{{ public_path('images/logo-dinascilacap.png') }}" />
                                    </td>
                                    <td style="width: 50%; vertical-align: middle; text-align: right; padding-left: 25px;">
                                        <img class="logo-right" src="{{ public_path('images/logo-Diskominfo.png') }}" />
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div style="text-align: center;">
                            <div class="cert-title">S E R T I F I K A T</div>
                            <div class="title-line"><span class="diamond">&#8226;</span></div>
                            <div class="cert-number">NOMOR : {{ $sertifikat->no_sertifikat }}</div>

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
                            @php
                                $kabidNama = $sertifikat->nama_kabid;
                                $kabidNip = $sertifikat->nip_kabid;
                                $kadisNama = $sertifikat->nama_kadis;
                                $kadisNip = $sertifikat->nip_kadis;
                            @endphp

                            <table class="sig-table">
                                <tr>
                                    <td style="width: 33%;">
                                        <div class="sig-title">
                                            Kepala Bidang
                                        </div>
                                        <div class="sig-name">{{ $kabidNama }}</div>
                                        <div class="sig-detail">
                                            NIP. {{ $kabidNip }}
                                        </div>
                                    </td>
                                    <td style="width: 34%;">
                                        <div class="qr-box">
                                            <img src="{{ $qrCodeImage ?? \App\Helpers\QrCodeHelper::generatePngBase64($qrUrl ?? route('sertifikat.cetak-gabungan-v5', ['id_sertifikat' => $sertifikat->id_sertifikat]), 200) }}" alt="QR Code" style="width: 70px; height: 70px; display: block;" />
                                        </div>
                                        <div class="qr-label">Scan Validasi</div>
                                    </td>
                                    <td style="width: 33%;">
                                        <div class="sig-title">
                                            Cilacap, {{ $ex4[2] . ' ' . $bulan[(int) $ex4[1]] . ' ' . $ex4[0] }}<br>
                                            Kepala Dinas Komunikasi dan<br>Informatika<br>Kabupaten Cilacap
                                        </div>
                                        <div class="sig-name">{{ $kadisNama }}</div>
                                        <div class="sig-detail">
                                            NIP. {{ $kadisNip }}
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

                    <div class="watermark">KOMINFO</div>

                    <div class="content">
                        <div style="text-align: center;">
                            <div class="nilai-title">DAFTAR NILAI</div>
                            <div class="nilai-subtitle">HASIL PELAKSANAAN PRAKTIK KERJA LAPANGAN</div>
                            <div class="subtitle-line"><span class="diamond">&#8226;</span></div>
                        </div>

                        <table class="info-table">
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

                        <table class="table-nilai" style="margin: {{ $tableMargin }}; font-size: {{ $tdFontSize }};">
                            <thead>
                                <tr>
                                    <th style="width: 8%; padding: {{ $thPadding }}; font-size: {{ $thFontSize }};">No</th>
                                    <th style="width: 52%; padding: {{ $thPadding }}; font-size: {{ $thFontSize }};">Aspek yang dinilai</th>
                                    <th style="width: 20%; padding: {{ $thPadding }}; font-size: {{ $thFontSize }};">Nilai</th>
                                    <th style="width: 20%; padding: {{ $thPadding }}; font-size: {{ $thFontSize }};">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach($penilaians as $penilaian)
                                <tr>
                                    <td style="padding: {{ $tdPadding }}; font-size: {{ $tdFontSize }};">{{ $no++ }}</td>
                                    <td style="padding: {{ $tdPadding }}; font-size: {{ $tdFontSize }};">{{ $penilaian->kriteria ?? $penilaian->aspek }}</td>
                                    <td style="padding: {{ $tdPadding }}; font-size: {{ $tdFontSize }};">{{ $penilaian->nilai ?? '-' }}</td>
                                    <td style="padding: {{ $tdPadding }}; font-size: {{ $tdFontSize }};">{{ $penilaian->ket ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tbody>
                                <tr class="row-rata-rata">
                                    <td colspan="2" style="text-align: right; padding-right: 14px; padding-top: {{ explode(' ', $tdPadding)[0] }}; padding-bottom: {{ explode(' ', $tdPadding)[0] }}; font-size: {{ $tdFontSize }};"><strong>Rata-rata</strong></td>
                                    <td colspan="2" style="padding-top: {{ explode(' ', $tdPadding)[0] }}; padding-bottom: {{ explode(' ', $tdPadding)[0] }}; font-size: {{ $tdFontSize }};"><strong>{{ number_format($rataRata, 2) }}</strong></td>
                                </tr>
                                <tr class="row-predikat">
                                    <td colspan="2" style="text-align: right; padding-right: 14px; padding-top: {{ explode(' ', $tdPadding)[0] }}; padding-bottom: {{ explode(' ', $tdPadding)[0] }}; font-size: {{ $tdFontSize }};"><strong>Predikat</strong></td>
                                    <td colspan="2" style="padding-top: {{ explode(' ', $tdPadding)[0] }}; padding-bottom: {{ explode(' ', $tdPadding)[0] }}; font-size: {{ $tdFontSize }};"><strong>{{ $predikat }} @if($rataRata >= 90)(A)@elseif($rataRata >= 75)(B)@elseif($rataRata >= 60)(C)@else(D)@endif</strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="bottom" style="margin-top: {{ $bottomMargin }};">
                            <table class="bottom-table">
                                <tr>
                                    <td style="width: 250px;">
                                        <div class="kategori-box">
                                            <div class="kategori-title">Kategori Nilai</div>
                                            <table class="kategori-list">
                                                <tr><td>90,00 - 100</td><td>Sangat Baik (A)</td></tr>
                                                <tr><td>75,00 - 89,99</td><td>Baik (B)</td></tr>
                                                <tr><td>60,00 - 74,99</td><td>Cukup (C)</td></tr>
                                                <tr><td>00,00 - 59,00</td><td>Kurang (D)</td></tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td class="sig-nilai">
                                        @php
                                            $mentorNama = $sertifikat->nama_pembimbing;
                                            $mentorNip = $sertifikat->nip_pembimbing;
                                        @endphp
                                        <table class="sig-nilai-table">
                                            <tr>
                                                <td style="width: 50%;">
                                                    <div class="sig-title" style="margin-bottom: {{ $sigMarginBottom }};">
                                                        Mentor Lapangan
                                                    </div>
                                                    <div class="sig-name">{{ $mentorNama }}</div>
                                                    <div class="sig-detail">NIP. {{ $mentorNip }}</div>
                                                </td>
                                                <td style="width: 50%;">
                                                    <div class="sig-title" style="margin-bottom: {{ $sigMarginBottom }};">
                                                        Cilacap, {{ $ex4[2] . ' ' . $bulan[(int) $ex4[1]] . ' ' . $ex4[0] }}<br>
                                                        Kepala Bidang
                                                    </div>
                                                    <div class="sig-name">{{ $kabidNama }}</div>
                                                    <div class="sig-detail">NIP. {{ $kabidNip }}</div>
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
    </div>
</body>
</html>

