<?php
function parseM3U($filePath, $type)
{
    if (!file_exists($filePath)) return [];

    $channels = [];
    $fileContents = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $currentChannel = null;

    // Recorre cada línea del archivo .m3u
    foreach ($fileContents as $line) {
        // Detecta la línea con metadatos
        if (strpos($line, '#EXTINF:') === 0) {
            // Captura atributos (tvg-id, tvg-name, tvg-logo, group-title) y el nombre
            preg_match('/tvg-id="([^"]*)"?.*?tvg-name="([^"]*)"?.*?tvg-logo="([^"]*)"?.*?group-title="([^"]*)"?.*?,(.*)$/', $line, $matches);
            $currentChannel = [
                'id'    => $matches[1] ?? '',
                'name'  => $matches[2] ?? ($matches[5] ?? 'Sin nombre'),
                'logo'  => $matches[3] ?? 'default.jpg',
                'group' => $matches[4] ?? 'Sin categoría'
            ];
        }
        // Detecta la URL y clasifica según $type
        elseif ($currentChannel && filter_var($line, FILTER_VALIDATE_URL)) {
            // Clasifica según el tipo (live, series, movie)
            $pathParts = explode('/', parse_url($line, PHP_URL_PATH));
            if (match ($type) {
                'live' => in_array('live', $pathParts)
                    || pathinfo($line, PATHINFO_EXTENSION) === 'ts'
                    || pathinfo($line, PATHINFO_EXTENSION) === 'm3u8',
                'series' => in_array('series', $pathParts),
                'movie'  => in_array('movie', $pathParts),
                default  => false
            }) {
                // Agrupa el canal según el group-title
                $channels[$currentChannel['group']][] = [
                    'name' => $currentChannel['name'],
                    'logo' => $currentChannel['logo'],
                    'url'  => $line
                ];
            }
            // Reset para el siguiente canal
            $currentChannel = null;
        }
    }

    // Función para limpiar los nombres solo para el ordenamiento
    $normalizeForSort = function ($text) {
        // Elimina todo lo que no sea una letra (español o inglés) o espacios
        return preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]/', '', $text);
    };

    // Ordena los grupos alfabéticamente (normalizando las claves de los grupos)
    uksort($channels, function ($a, $b) use ($normalizeForSort) {
        return strcasecmp($normalizeForSort($a), $normalizeForSort($b));
    });

    // Ordena los canales dentro de cada grupo alfabéticamente por nombre
    foreach ($channels as &$group) {
        usort($group, function ($a, $b) use ($normalizeForSort) {
            // Compara los nombres de los canales después de limpiarlos para ordenarlos
            return strcasecmp($normalizeForSort($a['name']), $normalizeForSort($b['name']));
        });
    }

    return $channels;
}
