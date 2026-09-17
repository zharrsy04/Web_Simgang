<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$sertifikat = \App\Models\SertifikatPKL::first();

// Bypass CSRF by removing the middleware for testing
$app->instance('middleware.disable', true);
Illuminate\Support\Facades\Route::withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

$req = Illuminate\Http\Request::create('/api/tasks/assign', 'POST', [
    'sertifikat_pkl_id' => $sertifikat ? $sertifikat->id_sertifikat : 1, 
    'title' => 'Test Task'
]);
$req->headers->set('Accept', 'application/json');

$res = app()->handle($req);
echo "STATUS: " . $res->getStatusCode() . "\n";
echo "BODY: " . $res->getContent() . "\n";
