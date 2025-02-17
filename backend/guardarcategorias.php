<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados por el cliente
    $data = json_decode(file_get_contents('php://input'), true);

    // Obtener el nombre del archivo y el contenido
    $nombreArchivo = $data['nombreArchivo'];
    $contenido = $data['contenido'];

    // Definir la ruta de almacenamiento
    $ruta =  "../storage/" . $nombreArchivo;

    // Guardar el contenido en el archivo
    if (file_put_contents($ruta, $contenido)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo guardar el archivo."]);
    }
}
?>
