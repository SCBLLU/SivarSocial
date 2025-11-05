<?php

$token = '154|avGXLnIcB0vyxcfSJLpsYQeav5nhxWwtvnvRMa9x24abf84b';
$url = 'http://localhost:8000/api/notifications/register-device';

$data = [
    'device_token' => 'test-token-12345',
    'platform' => 'android'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
]);

echo "\n🚀 Enviando petición a: $url\n\n";
echo "📦 Datos enviados:\n";
echo json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "📡 Código de respuesta HTTP: $httpCode\n\n";
echo "📥 Respuesta:\n";
echo json_encode(json_decode($response), JSON_PRETTY_PRINT) . "\n\n";

if ($httpCode === 200) {
    echo "✅ ¡Prueba exitosa!\n\n";
} else {
    echo "❌ Error en la petición\n\n";
}
