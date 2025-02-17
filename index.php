<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reproductor M3U</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="container mt-4 text-center bg-dark text-white">
    <h1 class="mb-4">Reproductor M3U</h1>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card bg-primary text-white" onclick="location.href='player.php?category=live'">
                <div class="card-body text-center">
                    <i class="fas fa-tv fa-2x"></i>
                    <h5 class="mt-2">LIVE TV</h5>
                    <p id="liveCount" class="mt-2"></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white" onclick="location.href='player.php?category=movie'">
                <div class="card-body text-center">
                    <i class="fas fa-film fa-2x"></i>
                    <h5 class="mt-2">MOVIES</h5>
                    <p id="movieCount" class="mt-2"></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white" onclick="location.href='player.php?category=series'">
                <div class="card-body text-center">
                    <i class="fas fa-tv fa-2x"></i>
                    <h5 class="mt-2">SERIES</h5>
                    <p id="seriesCount" class="mt-2"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            obtenerConteoCategorias();
        });

        async function obtenerConteoCategorias() {
            try {
                // Realizamos la solicitud al backend para obtener el conteo de canales por categoría
                const response = await fetch("backend/contarCategorias.php");
                if (!response.ok) {
                    throw new Error("No se pudo obtener el conteo de categorías");
                }

                const data = await response.json();
                console.log(data);

                // Asignamos los valores al contenido de los elementos correspondientes
                document.getElementById("liveCount").textContent = `${data.live} canales`;
                document.getElementById("movieCount").textContent = `${data.movie} películas`;
                document.getElementById("seriesCount").textContent = `${data.series} series y temporadas`;

            } catch (error) {
                console.error("Error al obtener los conteos de categorías:", error);
            }
        }
    </script>
</body>

</html>
