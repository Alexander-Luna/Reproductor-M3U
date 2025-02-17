<?php

function contarCanales($archivo) {
    $contenido = file_get_contents($archivo);
    $lineas = explode("\n", $contenido);

    $canales = 0;
    foreach ($lineas as $linea) {
        if (strpos($linea, "#EXTINF:") === 0) {
            $canales++;
        }
    }
    return $canales;
}

// Definir las rutas de los archivos
$archivos = [
    'live' => 'storage/live.play',
    'movie' => 'storage/movie.play',
    'series' => 'storage/series.play'
];

// Contar los canales por categoría
$resultado = [];
foreach ($archivos as $categoria => $ruta) {
    $resultado[$categoria] = contarCanales($ruta);
}

// Devolver la cantidad de canales en formato JSON
echo json_encode($resultado);
