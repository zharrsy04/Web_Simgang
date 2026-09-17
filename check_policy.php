<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$s = App\Models\SertifikatPKL::first();
if ($s) {
    echo 'id_sertifikat: ' . $s->id_sertifikat . PHP_EOL;
    echo 'created_id: ' . (is_null($s->created_id) ? 'NULL' : $s->created_id) . PHP_EOL;
    echo 'created_id type: ' . gettype($s->created_id) . PHP_EOL;
}
$u = App\Models\User::where('role','mentor')->first();
if ($u) {
    echo 'Mentor id: ' . $u->id . PHP_EOL;
    echo 'Mentor id type: ' . gettype($u->id) . PHP_EOL;
    if ($s) {
        echo 'Strict match (===): ' . ($s->created_id === $u->id ? 'TRUE' : 'FALSE') . PHP_EOL;
        echo 'Loose match (==): ' . ($s->created_id == $u->id ? 'TRUE' : 'FALSE') . PHP_EOL;
    }
}
