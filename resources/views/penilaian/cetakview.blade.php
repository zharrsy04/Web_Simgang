<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
			position: relative;
        }

        .background {
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/images/bg-sertif.png');
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            position: absolute;
            z-index: -1;
        }

        .container {
            width: 100%;
            padding: 20px;
            background-color: white;
            box-sizing: border-box;
			align-items: center; 
			justify-content: center;
			text-align: center;
        }

        .instansi-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            padding-bottom: 8px;
            border-bottom: 3px solid #000;
            margin-bottom: 4px;
        }

        .instansi-header img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .instansi-header .instansi-text {
            text-align: center;
            line-height: 1.3;
        }

        .instansi-header .instansi-prov {
            font-size: 11px;
            font-weight: normal;
            text-transform: uppercase;
        }

        .instansi-header .instansi-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .instansi-header .instansi-sub {
            font-size: 11px;
            font-weight: normal;
        }

        .instansi-address {
            text-align: center;
            font-size: 9px;
            color: #333;
            border-bottom: 1px solid #000;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .title {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
            line-height: 1.5;
        }

        .header-info {
            display: flex;
			justify-content: center; 
			width: 100%;
			margin-bottom: 15px;
        }

        .header-info table {
            font-size: 12px;
			border-collapse: collapse;
			width: auto; 
			text-align: left;
			margin: auto; 
        }

        .header-info td {
            padding: 5px 10px;
			vertical-align: top;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
            padding: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .table td[style="text-align: left;"] {
            text-align: left;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 30px;
            font-size: 12px;
        }

        .footer-left {
            width: 50%;
            text-align: left;
        }

        .footer-left table {
            border: 1px solid #000;
            width: auto;
            font-size: 12px;
        }

        .footer-left td {
            padding: 5px;
        }

        .footer-right {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <?php
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
    ?>

    <!--<div class="background"></div>-->

    <div class="container">
        {{-- Header Instansi --}}
        <div class="instansi-header">
            <img src="/images/logo-dinascilacap.png" alt="Logo Cilacap">
            <div class="instansi-text">
                <div class="instansi-prov">Pemerintah Kabupaten Cilacap</div>
                <div class="instansi-name">Dinas Komunikasi dan Informatika</div>
                <div class="instansi-sub">Kabupaten Cilacap</div>
            </div>
            <img src="/images/logo-Diskominfo.png" alt="Logo Diskominfo">
        </div>
        <div class="instansi-address">
            Jl. Kalimantan No. 24, Cilacap &nbsp;|&nbsp; Telp. (0282) 533 191 &nbsp;|&nbsp; Website: diskominfo.cilacapkab.go.id
        </div>

        <h2 class="title">DAFTAR NILAI<br>HASIL PELAKSANAAN<br>Praktik Kerja Lapangan</h2>

        <div class="header-info">
            <table>
                <tr>
                    <td>Nama Siswa/Mahasiswa</td>
                    <td>: {{ $sertifikat->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td>NIS/NIM</td>
                    <td>: {{ $sertifikat->nim_nis }}</td>
                </tr>
                <tr>
                    <td>Jurusan</td>
                    <td>: {{ $sertifikat->jurusan }}</td>
                </tr>
                <tr>
                    <td>Sekolah/Perguruan Tinggi</td>
                    <td>: {{ $sertifikat->asal_sekolah }}</td>
                </tr>
            </table>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>ASPEK YANG DINILAI</th>
                    <th>NILAI</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penilaians as $index => $penilaian)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="text-align: left;">{{ $penilaian->aspek }}</td>
                        <td>{{ $penilaian->nilai }}</td>
                        <td>{{ $penilaian->ket ?? '-' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="text-align: right;"><strong>Rata-rata</strong></td>
                    <td colspan="2">{{ number_format($penilaians->avg('nilai'), 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;"><strong>Predikat</strong></td>
                    <td colspan="2">
                        @php
                            $avg = $penilaians->avg('nilai');
                            if ($avg >= 90) {
                                echo 'Sangat Baik (A)';
                            } elseif ($avg >= 75) {
                                echo 'Baik (B)';
                            } elseif ($avg >= 60) {
                                echo 'Cukup (C)';
                            } else {
                                echo 'Kurang (D)';
                            }
                        @endphp
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <table style="width: 100%; text-align: center; margin-top: 40px; border-spacing: 0;">
                <tr>
                    
                    <td class="footer-left"
                        style="width: 40%; vertical-align: top; text-align: left; ">
                        <table style="width: 100%; border-spacing: 0;">
                            <tr>
                                <td colspan="2" style="font-weight: bold;">Kategori Nilai :</td>
                            </tr>
                            <tr>
                                <td width="40%">90,00 - 100</td>
                                <td>= Sangat Baik (A)</td>
                            </tr>
                            <tr>
                                <td>75,00 - 89,99</td>
                                <td>= Baik (B)</td>
                            </tr>
                            <tr>
                                <td>60,00 - 74,99</td>
                                <td>= Cukup (C)</td>
                            </tr>
                            <tr>
                                <td>00,00 - 59,00</td>
                                <td>= Kurang (D)</td>
                            </tr>
                        </table>
                    </td>

                    <td class="footer-right" style="width: 60%; vertical-align: top;">
                        <table style="width: 100%; border-spacing: 0;">
                            <tr>
                                <!-- Kolom Pembimbing Lapangan -->
                                <td style="width: 50%; text-align: center; vertical-align: top;">
                                    <p>Pembimbing Lapangan</p>
                                    <br><br><br>
                                    <p style="font-weight: bold; margin-top: 40px;"> 
                                        <span style="text-decoration: underline;" contenteditable="true" title="Klik untuk edit nama pembimbing secara manual jika diperlukan">{{ $sertifikat->nama_pembimbing }}</span><br>
                                        <span>NIP. {{ $sertifikat->nip_pembimbing }}</span>
                                    </p>
                                </td>
                                <!-- Kolom Kepala Bidang -->
                                <td style="width: 50%; text-align: center; vertical-align: top;">
                                    <p>Cilacap, <?= $ex4[2] . ' ' . $bulan[(int) $ex4[1]] . ' ' . $ex4[0] ?></p>
                                    <p>Kepala Bidang</p>
                                    <br><br><br>
                                    <p style="font-weight: bold; margin-top: 40px;"> 
                                        <span style="text-decoration: underline;">{{ $sertifikat->nama_kabid }}</span><br>
                                        <span>NIP. {{ $sertifikat->nip_kabid }}</span>
                                    </p>
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
