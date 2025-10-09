<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
// bootstrap minimal kernel to use Eloquent
// load env
// Note: this script runs outside artisan; we'll directly use DB via Eloquent models

use App\Models\Category;

echo "Checking categories and image files...\n\n";

$storagePublic = realpath(__DIR__ . '/../storage/app/public');
$publicStorageLink = realpath(__DIR__ . '/../public/storage');

echo "storage/app/public -> " . ($storagePublic ?: 'not found') . "\n";
echo "public/storage -> " . ($publicStorageLink ?: 'not found') . "\n\n";

$cats = Category::all();
foreach ($cats as $c) {
    $img = $c->image ?: 'NULL';
    $diskPath = $storagePublic && $c->image ? $storagePublic . DIRECTORY_SEPARATOR . $c->image : 'N/A';
    $exists = ($storagePublic && $c->image && file_exists($diskPath)) ? 'exists' : 'missing';
    $perms = ($storagePublic && $c->image && file_exists($diskPath)) ? substr(sprintf('%o', fileperms($diskPath)), -4) : '-';
    $publicUrl = ($c->image) ? url('storage/' . $c->image) : 'N/A';

    echo sprintf("%3d | %-25s | %-30s | %-7s | perms=%s | url=%s\n", $c->id, $c->name, $img, $exists, $perms, $publicUrl);
}

echo "\nDone.\n";