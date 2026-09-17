<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$instansis = DB::table('instansi')->select('id', 'nama', 'logo')->get();

echo "=== DATA INSTANSI & LOGO PATH ===\n\n";

foreach ($instansis as $inst) {
    echo "ID: {$inst->id}\n";
    echo "Nama: {$inst->nama}\n";
    echo "Logo DB: " . ($inst->logo ?? 'NULL') . "\n";
    
    if ($inst->logo) {
        $fullPath = public_path('storage/' . $inst->logo);
        $exists = file_exists($fullPath) ? '✓ EXISTS' : '✗ NOT FOUND';
        echo "Full Path: {$fullPath}\n";
        echo "Status: {$exists}\n";
    }
    
    echo "\n";
}
