<!DOCTYPE html>
<html>

<head>
    <title>Cetak Gabungan</title>
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
        
        /* Style untuk memastikan page break yang tepat */
        .page-break {
            page-break-before: always;
            clear: both;
            display: block;
            height: 20px;
        }
    </style>
</head>

<body>
    @include('mentor.cetakview', ['sertifikat' => $sertifikat])

    <div class="page-break"></div>

    @include('penilaian.cetakview', ['sertifikat' => $sertifikat, 'penilaians' => $penilaians])
</body>

</html>