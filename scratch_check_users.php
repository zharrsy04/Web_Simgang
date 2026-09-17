<?php
require 'c:/laragon/www/Web_Simgang/vendor/autoload.php';
$app = require 'c:/laragon/www/Web_Simgang/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

$request = \Illuminate\Http\Request::create('/login', 'POST', [
    'email' => 'mentor1',
    'password' => 'mentor123',
]);
$response = $kernel->handle($request);

echo "Status Code: " . $response->getStatusCode() . "\n";
echo "Target URL: " . ($response->headers->get('Location') ?? 'none') . "\n";
$session = $request->session();
echo "Session error: " . ($session->get('error') ?? 'none') . "\n";
echo "Session success: " . ($session->get('success') ?? 'none') . "\n";







