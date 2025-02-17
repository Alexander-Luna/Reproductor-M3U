<?php
// Activa la visualización de errores en caso de que haya alguno
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ruta de la carpeta donde se encuentran los archivos
$directoryPath = "../storage/";

// Busca el primer archivo .txt en el directorio
$filePaths = glob($directoryPath . "*.txt");

// Si no se encuentra ningún archivo .txt, muestra un error
if (empty($filePaths)) {
    http_response_code(404);
    echo "No se encontraron archivos .txt en la carpeta 'storage'.";
    exit;
}

// Obtiene el primer archivo encontrado
$filePath = $filePaths[0];

// Lee el contenido del archivo
$encodedData = file_get_contents($filePath);

// Decodifica los datos desde Base64
$decodedData = base64_decode($encodedData);

// Decodifica el JSON a un array PHP
$userData = json_decode($decodedData, true);

if ($userData === null) {
    http_response_code(500);
    echo "Error al decodificar los datos del archivo. El JSON es inválido.";
    exit;
}

// Respuesta exitosa: muestra los datos leídos
echo json_encode($userData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
