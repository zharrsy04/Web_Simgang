<!DOCTYPE html>
<html>

<head>
    <title>Cetak Gabungan</title>
    <style>
        /* Define page breaks between sections */
        .page-break {
            page-break-after: always;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

    </style>
</head>

<body>
    <div id="sertifikat">
    <!-- First page: Certificate -->
    <div class="certificate-page">
        @include('mentor.cetak', ['sertifikat' => $sertifikat])
    </div>
    
    <!-- Page break -->
    <div class="page-break"></div>
    
    <!-- Second page: Evaluation -->
    <div class="evaluation-page">
        @include('penilaian.cetak', ['sertifikat' => $sertifikat, 'penilaians' => $penilaians])
    </div>
    </div>
    <script>
        window.onload = function () {
            const element = document.getElementById('sertifikat');
            const opt = {
                margin:       0,
                filename:     'sertifikat.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' } // 👈 penting!
            }; 

            // Generate and download PDF
            html2pdf().set(opt).from(element).save();
        };
        // window.location.href = "{{ url('mentor') }}";
        setTimeout(function() {
        window.location.href = "{{ url('mentor') }}";
    }, 3000); // 2000 ms = 2 detik
    </script>
    {{die();}}
</body>

</html>