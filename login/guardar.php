<?php
// Activa la visualización de errores en caso de que haya alguno
ini_set('display_errors', 1);
error_reporting(E_ALL);

$inputJSON = file_get_contents('php://input');
$userData = json_decode($inputJSON, true);

if (!$userData) {
    http_response_code(400);
    echo "Datos JSON inválidos.";
    exit;
}

// Extrae el nombre de usuario del array de datos
$usuario = isset($userData['usuario']) ? $userData['usuario'] : 'default';

// Ruta dinámica del archivo usando el nombre de usuario
$filePath = "../storage/{$usuario}.txt";

// Codifica los datos en formato JSON
$jsonData = json_encode([$userData], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// Codifica los datos JSON a Base64
$encodedData = base64_encode($jsonData);

// Guarda los datos codificados en el archivo (reemplaza el archivo si ya existe)
if (file_put_contents($filePath, $encodedData) === false) {
    http_response_code(500);
    echo "Error al guardar los datos en el archivo. No se pudo escribir en el archivo.";
    exit;
}

// Respuesta exitosa
echo "Los datos se guardaron correctamente en el servidor:\n";
echo json_encode($userData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
