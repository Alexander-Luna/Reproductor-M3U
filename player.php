<?php

include_once('backend/cargar-m3u.php');

$type = $_GET['category'] ?? 'live';

$filePath = __DIR__ . '/storage/' . $type . '.play';

if (file_exists($filePath)) {

    $channels = parseM3U($filePath, $type);
} else {

    echo json_encode(["error" => "El archivo para la categoría '$type' no existe."]);

    exit;
}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reproductor M3U - TV</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">

   <link rel="stylesheet" href="css/player.css">

</head>

<body>

    <div class="channel-name-header" id="channelNameHeader">Selecciona un canal</div>

    <button class="btn toggle-sidebar-btn" id="toggleSidebarBtn">
        <i class="bi bi-list"></i>
    </button>

    <div class="container-tv">

        <div class="video-container">

            <div class="ratio ratio-16x9">

                <video id="player" playsinline controls></video>

            </div>

        </div>

        <div class="sidebar" id="sidebar">

            <h4>Categorías</h4>

            <input type="text" class="search-bar" id="search" placeholder="Buscar canal...">

            <ul id="suggestions" class="suggestions"></ul>

            <ul class="list-group" id="categoriesList">

                <?php foreach ($channels as $groupTitle => $items): ?>

                    <li class="list-group-item bg-dark text-white category-item" data-category="<?= addslashes($groupTitle) ?>">

                        <?= htmlspecialchars($groupTitle) ?>

                    </li>

                    <div class="channel-list" id="<?= addslashes($groupTitle) ?>">

                        <?php foreach ($items as $channel): ?>

                            <div class="channel-card" tabindex="0" data-category="<?= addslashes($groupTitle) ?>" data-url="<?= addslashes($channel['url']) ?>" data-name="<?= addslashes($channel['name']) ?>">

                                <img data-src="<?= htmlspecialchars($channel['logo']) ?>" alt="<?= htmlspecialchars($channel['name']) ?>">

                                <span><?= htmlspecialchars($channel['name']) ?></span>

                            </div>

                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        // Función para verificar si el canal está disponible
        function checkChannelAvailability(url, callback) {
            const video = document.createElement('video');
            video.src = url;
            video.oncanplaythrough = () => callback(true); // Si el canal se puede reproducir
            video.onerror = () => callback(false); // Si ocurre un error al intentar cargar el canal
        }

        // Reproducir el primer canal automáticamente al cargar la página
        window.addEventListener('load', function() {
            const firstChannelCard = document.querySelector('.channel-card');
            if (firstChannelCard) {
                const firstChannelUrl = firstChannelCard.getAttribute('data-url');
                checkChannelAvailability(firstChannelUrl, function(isAvailable) {
                    if (isAvailable) {
                        playChannel(firstChannelUrl);
                        updateChannelName(firstChannelCard.getAttribute('data-name'));
                    } else {
                        showErrorMessage('Canal no disponible');
                    }
                });
            }
        });

        // Función para mostrar el mensaje de error si el canal no está disponible
        function showErrorMessage(message) {
            const channelNameHeader = document.getElementById('channelNameHeader');
            channelNameHeader.textContent = message;
        }

    

        // Actualizar el nombre del canal en la cabecera
        function updateChannelName(name) {
            const channelNameHeader = document.getElementById('channelNameHeader');
            channelNameHeader.textContent = name;
        }

        document.querySelectorAll('.category-item').forEach(item => {

            item.addEventListener('click', function() {

                const category = this.getAttribute('data-category');

                const list = document.getElementById(category);

                if (list.style.display === 'flex') {

                    list.style.display = 'none';

                } else {

                    document.querySelectorAll('.channel-list').forEach(el => el.style.display = 'none');

                    list.style.display = 'flex';

                    list.querySelectorAll('img').forEach(img => img.src = img.dataset.src);

                }

            });

        });

        document.querySelectorAll('.channel-card').forEach(card => {

            card.addEventListener('click', function() {

                playChannel(this.getAttribute('data-url'));

                updateChannelName(this.getAttribute('data-name'));

            });

        });

        document.getElementById('search').addEventListener('input', function() {

            const filter = this.value.toLowerCase();

            const suggestions = document.getElementById('suggestions');

            const channelCards = document.querySelectorAll('.channel-card');

            suggestions.innerHTML = ''; // Limpiar sugerencias anteriores

            channelCards.forEach(card => {

                const text = card.innerText.toLowerCase();

                if (text.includes(filter)) {

                    card.style.display = 'flex';

                    // Crear elemento de sugerencia

                    const suggestionItem = document.createElement('li');

                    suggestionItem.textContent = text;

                    suggestionItem.addEventListener('click', () => {

                        playChannel(card.dataset.url);

                        showCategory(card.dataset.category);

                        suggestions.style.display = 'none';

                    });

                    suggestions.appendChild(suggestionItem);

                } else {

                    card.style.display = 'none';

                }

            });

            suggestions.style.display = suggestions.children.length > 0 ? 'block' : 'none';

        });

        function showCategory(category) {

            const categoryList = document.getElementById(category);

            categoryList.style.display = 'flex';

        }

        document.getElementById('toggleSidebarBtn').addEventListener('click', () => {

            const sidebar = document.getElementById('sidebar');

            sidebar.classList.toggle('show');

        });
            // Función para reproducir el canal
            function playChannel(url) {
            const video = document.getElementById('player');

            if (Hls.isSupported()) {
                let hls = new Hls();
                hls.loadSource(url);
                hls.attachMedia(video);
                video.play();
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = url;
                video.play();
            }
        }
    </script>

    <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>