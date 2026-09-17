<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat PDF</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #2b2b2b;
            overflow: hidden;
        }
        
        .pdf-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .toolbar {
            background-color: #323232;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #444;
        }
        
        .toolbar-title {
            color: #fff;
            font-size: 16px;
            font-weight: 500;
        }
        
        .toolbar-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-download {
            background-color: #10b981;
            color: white;
        }
        
        .btn-download:hover {
            background-color: #059669;
        }
        
        .btn-close {
            background-color: #ef4444;
            color: white;
        }
        
        .btn-close:hover {
            background-color: #dc2626;
        }
        
        .pdf-viewer {
            flex: 1;
            width: 100%;
            height: calc(100vh - 60px);
            border: none;
        }
        
        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            font-size: 18px;
            text-align: center;
        }
        
        .spinner {
            border: 4px solid #444;
            border-top: 4px solid #10b981;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="pdf-container">
        <div class="toolbar">
            <div class="toolbar-title">📄 Sertifikat Gabungan</div>
            <div class="toolbar-actions">
                <button onclick="downloadPDF()" class="btn btn-download">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Download
                </button>
                <button onclick="window.close()" class="btn btn-close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Tutup
                </button>
            </div>
        </div>
        
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <div>Memuat PDF...</div>
        </div>
        
        <iframe id="pdfFrame" class="pdf-viewer" src="{{ $pdfUrl }}"></iframe>
    </div>
    
    <script>
        // Hide loading when PDF is loaded
        document.getElementById('pdfFrame').onload = function() {
            document.getElementById('loading').style.display = 'none';
        };
        
        // Download function
        function downloadPDF() {
            const link = document.createElement('a');
            link.href = "{{ $downloadUrl }}";
            link.download = "{{ $filename }}";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>
