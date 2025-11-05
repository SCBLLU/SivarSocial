<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Obtener el primer usuario
$user = App\Models\User::first();

if ($user) {
    // Crear un token
    $token = $user->createToken('postman-test-token')->plainTextToken;

    echo "\n=================================\n";
    echo "✅ TOKEN GENERADO EXITOSAMENTE\n";
    echo "=================================\n\n";
    echo "User ID: " . $user->id . "\n";
    echo "User Email: " . $user->email . "\n";
    echo "User Name: " . $user->name . "\n\n";
    echo "🔑 TOKEN:\n";
    echo $token . "\n\n";
    echo "=================================\n";
    echo "Copia este token y úsalo en Postman\n";
    echo "=================================\n\n";
} else {
    echo "\n⚠️  No hay usuarios en la base de datos.\n";
    echo "Crea un usuario primero con:\n";
    echo "POST http://localhost:8000/api/auth/register\n\n";
}
