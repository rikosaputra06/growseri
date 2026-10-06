<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Class Product exists: " . class_exists('App\Models\Product') . "\n";
$p = new App\Models\Product();
echo "Method variants exists: " . method_exists($p, 'variants') . "\n";
