<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            text-align: center;
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
/*
        .container {
            width: 100%;
            position: relative;
            padding: 20px;
            box-sizing: border-box;
        }*/

        .container {
            width: 100%;
            padding: 20px;
            background-color: white;
            box-sizing: border-box;
            align-items: center; 
            justify-content: center;
            text-align: center;
        }

        .header {
            position: relative;
            width: 100%;
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-left {
            position: absolute;
            left: 20px;
            top: 0;
            max-width: 220px;
            height: auto;
        }

        .logo-right {
            position: absolute;
            right: 20px;
            top: 0;
            max-width: 240px;
            height: auto;
        }

        .certificate-title {
            font-size: 30px;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0;
        }

        .certificate-nomor {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .nama {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }

        .center-table {
            width: 50%;
            margin: 0 auto;
            font-size: 14px;
            text-align: center;
            border-collapse: collapse;
        }

        .center-table td {
            padding: 5px 10px;
            vertical-align: top;
            text-align: left;
        }

        .kata-2 {
            font-size: 12px;
            line-height: 1.5;
            margin: 20px auto;
            width: 65%;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 40px;
            font-size: 12px;
        }

        .signature {
            text-align: center;
            width: 45%;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .signature-details {
            margin-top: 10px;
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
    @endphp

    <div class="background"></div>
    <div class="container">
        <div class="header">
            <img class="logo-left" src="{{URL::asset('/images/logo-dinascilacap.png')}}" alt="Logo Dinas Cilacap" />
            <img class="logo-right" src="{{URL::asset('/images/logo-Diskominfo.png')}}" alt="Logo Dinas Cilacap" />
            <!-- <img class="logo-left" src="{{ public_path('images/logo-dinascilacap.png') }}" alt="Logo Dinas Cilacap" /> -->
            <!-- <img class="logo-right" src="{{ public_path('images/logo-Diskominfo.png') }}" alt="Logo Dinas Cilacap" /> -->

            <!-- <img class="logo-left" src="{{URL::asset('/images/logo-dinascilacap.png')}}" alt="Logo Dinas Cilacap" />
            <img class="logo-right" src="{{URL::asset('/images/logo-Diskominfo.png')}}" alt="Logo Dinas Kominfo" />  -->
        </div>
        <center>
            <h1 class="certificate-title">S E R T I F I K A T</h1>
            <p class="certificate-nomor">NOMOR: {{ $sertifikat->no_sertifikat }}</p>
        </center>
        <p class="kata-2">
            Diberikan oleh Kepala Dinas Komunikasi dan Informatika Kabupaten Cilacap untuk menerangkan bahwa:
        </p>

        <h2 class="nama">{{ $sertifikat->nama_lengkap }}</h2>

        <table class="center-table">
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>: {{ $sertifikat->tempat_lahir . ', ' . $ex[2] . ' ' . $bulan[(int) $ex[1]] . ' ' . $ex[0] }}</td>
            </tr>
            <tr>
                <td>NIS/NIM</td>
                <td>: {{ $sertifikat->nim_nis }}</td>
            </tr>
            <tr>
                <td>Paket Keahlian</td>
                <td>: {{ $sertifikat->jurusan }}</td>
            </tr>
            <tr>
                <td>Sekolah/Perguruan Tinggi</td>
                <td>: {{ $sertifikat->asal_sekolah }}</td>
            </tr>
        </table>

        <p class="kata-2">
            Telah melaksanakan Praktek Kerja Lapangan/Magang Mandiri pada Dinas Komunikasi dan Informatika Kabupaten Cilacap, terhitung mulai
            tanggal {{ $ex2[2] . ' ' . $bulan[(int) $ex2[1]] . ' ' . $ex2[0] }} sampai dengan
            {{ $ex3[2] . ' ' . $bulan[(int) $ex3[1]] . ' ' . $ex3[0] }},
            dengan hasil Sangat Baik.
        </p>

        <div class="signature-section">
            <table style="width: 100%; text-align: center; margin-top: 40px;">
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        {{ $sertifikat->jabatan_kabid }}<br><br><br><br>
                        <span class="signature-name">{{ $sertifikat->nama_kabid }}</span><br>
                        <span class="signature-details">NIP. {{ $sertifikat->nip_kabid }}</span>
                    </td>

                    <td style="width: 50%; vertical-align: top;">
                        Cilacap, {{ $ex4[2] . ' ' . $bulan[(int) $ex4[1]] . ' ' . $ex4[0] }}<br>
                        Kepala Dinas Komunikasi dan Informatika<br>
                        Kabupaten Cilacap<br><br><br><br>
                        <span class="signature-name">{{ $sertifikat->nama_kadis }}</span><br>
                        <span class="signature-details">NIP. {{ $sertifikat->nip_kadis }}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>