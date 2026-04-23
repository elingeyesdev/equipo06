<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $producto = \App\Models\Producto::first();
    $user = \App\Models\User::where('email', 'test@example.com')->first();
    if ($user && $producto) {
        \Illuminate\Support\Facades\Notification::send($user, new \App\Notifications\ComenzarCultivoNotification($producto));
        echo "Notification sent to user ID: " . $user->id . "\n";
    } else {
        echo "User or product not found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}