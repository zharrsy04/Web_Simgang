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
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("{{ asset('images/bg-sertif.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -900;
            opacity: 1;
        }
        /*.container {
            width: 100%;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.95);
            box-sizing: border-box;
        }*/

        .container {
            width: 100%;
            padding: 20px;
            background-color: white;
            box-sizing: border-box;
/*            align-items: center; */
/*            justify-content: center;*/
/*            text-align: center;*/
        }

        .title {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 12px;
        }

        .header-info {
/*            text-align: center;*/
            margin-bottom: 11px;
        }

        .header-info table {
            margin: 0 auto;
            font-size: 11px;
        }

        .header-info td {
            padding: 2px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
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

        .footer {
            display: flex;
			justify-content: space-between;
            align-items: flex-start;
            margin-top: 30px;
            font-size: 10px;
        }

        .footer-left {
            width: 50%;
        }

        .footer-left table {
            border: 1px solid #000;
            width: fit-content;
            font-size: 10px;
        }

        .footer-left td {
            padding: 5px;
        }

        .footer-right {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
		}

        .signature {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
            font-size: 10px;
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
    <div class="background"><img src="{{ asset('images/bg-sertif.png') }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;" /></div> 

    <div class="container">
        <h2 class="title">DAFTAR NILAI<br>HASIL PELAKSANAAN<br>Praktik Kerja Lapangan</h2>

        <div class="header-infoo">
            <table style="margin-left: 33%; font-size: 11;">
                <tr>
                    <td style="text-align:left; font-size: 11;">Nama Siswa/Mahasiswa</td>
                    <td style="text-align:left; font-size: 11;">: {{ $sertifikat->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td style="text-align:left; font-size: 11;">NIS/NIM</td>
                    <td style="text-align:left; font-size: 11;">: {{ $sertifikat->nim_nis }}</td>
                </tr>
                <tr>
                    <td style="text-align:left; font-size: 11;">Jurusan</td>
                    <td style="text-align:left; font-size: 11;">: {{ $sertifikat->jurusan }}</td>
                </tr>
                <tr>
                    <td style="text-align:left; font-size: 11;">Sekolah/Perguruan Tinggi</td>
                    <td style="text-align:left; font-size: 11;">: {{ $sertifikat->asal_sekolah }}</td>
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
                        <td>{{ $loop->iteration }}</td>
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
                        <table style="width: 50%; border-spacing: 0;">
                            <tr>
                                <td style="font-weight: bold;">Kategori Nilai</td>
                            </tr>
                            <tr>
                                <td>90,00 - 100</td>
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
                                <td style="width: 50%; text-align: center; vertical-align: top;">
                                    <p>Pembimbing Lapangan</p>
                                    <br><br><br>
                                    <p style="font-weight: bold; margin-top: 40px;"> 
                                        <span class="signature-name" style="text-decoration: underline;">{{ $sertifikat->nama_pembimbing }}</span><br>
                                        <span class="signature-details">NIP. {{ $sertifikat->nip_pembimbing }}</span>
                                    </p>
                                </td>
                                <td style="width: 50%; text-align: center; vertical-align: top;">
                                    <p>Cilacap, <?= $ex4[2] . ' ' . $bulan[(int) $ex4[1]] . ' ' . $ex4[0] ?></p>
                                    <p>{{ $sertifikat->jabatan_kabid }}</p>
                                    <br><br><br>
                                    <p style="font-weight: bold; margin-top: 40px;"> 
                                        <span class="signature-name" style="text-decoration: underline;">{{ $sertifikat->nama_kabid }}</span><br>
                                        <span class="signature-details">NIP. {{ $sertifikat->nip_kabid }}</span>
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
