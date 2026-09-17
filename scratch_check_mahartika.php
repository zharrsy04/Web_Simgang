<?php
require 'c:/laragon/www/Web_Simgang/vendor/autoload.php';
$app = require 'c:/laragon/www/Web_Simgang/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

\Illuminate\Pagination\Paginator::currentPageResolver(fn() => 5);
$user = \App\Models\User::where('username', 'mentor1')->first();
\Illuminate\Support\Facades\Auth::login($user);
$controller = new \App\Http\Controllers\SertifikatController();
$request = \Illuminate\Http\Request::create('/mentor?page=5', 'GET');
$html = $controller->index($request)->render();

if (strpos($html, 'Mahardika') !== false) {
    echo "Found Mahardika on page 5!\n";
} else {
    echo "Mahardika NOT found on page 5.\n";
}

if (strpos($html, 'Mahartika') !== false) {
    echo "Found Mahartika on page 5!\n";
}

