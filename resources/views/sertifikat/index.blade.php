<!DOCTYPE html>
<html lang="id" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sertifikat</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

</head>
<body>
    <h1>Daftar Sertifikat PKL</h1>
    <table>
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>Asal Sekolah</th>
                <th>NIM/NIS</th>
                <th>Jurusan</th>
                <th>Tanggal Sertifikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sertifikats as $sertifkat )
                <tr>
                    <td>{{ $sertifikat->nama_lengkap}}</td>
                    <td>{{ $sertifikat->asal_sekolah}}</td>
                    <td>{{ $sertifikat->nim_nis}}</td>
                    <td>{{ $sertifikat->jurusan}}</td>
                    <td>{{ $sertifikat->tgl_sertifikat}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>