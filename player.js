
const player = new Plyr('#player');
let hls = null;
let hideTimer = null;

// Carga el stream en el reproductor
function loadVideo(url) {
    if (hls) {
        hls.destroy();
    }
    const video = document.querySelector('#player');

    if (url.match(/\.(m3u8|ts)$/)) {
        if (Hls.isSupported()) {
            const config = {
                maxBufferLength: 15,
                liveSyncDuration: 3,
                liveMaxLatencyDuration: 10,
            };
            hls = new Hls(config);
            hls.loadSource(url);
            hls.attachMedia(video);
            hls.on(Hls.Events.MANIFEST_PARSED, () => {
                video.play();
            });
        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            video.src = url;
            video.play();
        }
    } else {
        video.src = url;
        video.play();
    }
    player.play();
}

// Muestra los ítems (canales) de la categoría seleccionada
function showGroup(groupTitle) {
    const channelList = document.getElementById('channelList');
    channelList.innerHTML = ''; // Limpia la vista

    if (channelsData[groupTitle]) {
        channelsData[groupTitle].forEach(item => {
            const card = document.createElement('div');
            card.classList.add('channel-card');
            card.onclick = () => loadVideo(item.url);

            const img = document.createElement('img');
            img.src = item.logo_url;
            card.appendChild(img);

            const text = document.createElement('div');
            text.textContent = item.name;
            card.appendChild(text);

            channelList.appendChild(card);
        });
    }
}

// Filtro de búsqueda
function filterChannels() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const filteredChannels = [];

    for (const group in channelsData) {
        filteredChannels[group] = channelsData[group].filter(item => 
            item.name.toLowerCase().includes(query)
        );
    }

    updateChannelList(filteredChannels);
}

// Actualiza el listado de canales
function updateChannelList(filteredChannels) {
    const channelList = document.getElementById('channelList');
    channelList.innerHTML = '';

    for (const group in filteredChannels) {
        filteredChannels[group].forEach(item => {
            const card = document.createElement('div');
            card.classList.add('channel-card');
            card.onclick = () => loadVideo(item.url);

            const img = document.createElement('img');
            img.src = item.logo_url;
            card.appendChild(img);

            const text = document.createElement('div');
            text.textContent = item.name;
            card.appendChild(text);

            channelList.appendChild(card);
        });
    }
}
