<?php
$file = 'd:\laragon-new\www\web-simgang\resources\views\dashboard\siswaaktif.blade.php';
$content = file_get_contents($file);

$start = strpos($content, '<!-- Modal for Penilaian -->');
$end = strpos($content, '@endforeach', $start);
$modals = substr($content, $start, $end - $start);

// Hapus modal dari dalam tabel
$content = str_replace($modals, '', $content);

// Tambahkan modal di bagian bawah (sebelum @endsection)
$wrappedModals = "@foreach (\$siswaAktif as \$siswa)\n" . $modals . "@endforeach\n\n@endsection";
$content = str_replace('@endsection', $wrappedModals, $content);

file_put_contents($file, $content);
echo 'Fixed!';
