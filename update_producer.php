<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $p = \App\Models\Producer::first();
    if ($p) {
        echo "Producer ID: " . $p->id . "\n";
        echo "Full name: " . $p->full_name . "\n";
        echo "Email: " . ($p->email ?? 'null') . "\n";
        $p->email = 'test@example.com';
        $p->save();
        echo "Email updated to " . $p->email . "\n";
    } else {
        echo "No producer found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}