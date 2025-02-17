<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Login (Neón Morado)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/main.css">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="login-card col-md-8 col-lg-6">
            <h1 class="text-center">Formulario de Login</h1>
            <form id="loginForm" class="row g-3 needs-validation" novalidate>
                <div class="col-md-12">
                    <label for="nombre" class="form-label">Nombre Lista</label>
                    <input type="text" class="form-control" id="nombre" value="Jumangis" placeholder="Ej. Juan Pérez" required />
                    <div class="invalid-feedback">
                        Por favor ingresa tu nombre.
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="usuario" value="jumangispaul123" placeholder="Ej. jperez" required />
                    <div class="invalid-feedback">
                        Por favor ingresa tu usuario.
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" value="jmQdjGBvuF" id="password" placeholder="Ingresa tu contraseña" required />
                    <div class="invalid-feedback">
                        Por favor ingresa tu contraseña.
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="url" class="form-label">URL</label>
                    <input type="url" class="form-control" id="url" value="http://jumangis.cloud:2082" placeholder="http://dominio:2028" required />
                    <div class="invalid-feedback">
                        Por favor ingresa una URL válida (sin barra final).
                    </div>
                </div>

                <button class="btn btn-primary" type="submit">Guardar</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ajusta la URL si acaba con '/'
        function sanitizeUrl(url) {
            return url.endsWith("/") ? url.slice(0, -1) : url;
        }

        // Validación nativa Bootstrap
        (function() {
            "use strict";
            const forms = document.querySelectorAll(".needs-validation");
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener("submit", function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add("was-validated");
                }, false);
            });
        })();

        const loginForm = document.getElementById("loginForm");

        // Al enviar el formulario -> Se envía la data al backend vía fetch
        loginForm.addEventListener("submit", async (event) => {
            // Evita el envío por defecto del formulario
            event.preventDefault();

            // Si formulario no es válido, salir
            if (!loginForm.checkValidity()) {
                return;
            }

            // Recolecta datos
            const userData = {
                nombre: document.getElementById("nombre").value.trim(),
                usuario: document.getElementById("usuario").value.trim(),
                password: document.getElementById("password").value.trim(),
                url: sanitizeUrl(document.getElementById("url").value.trim()),
            };

            try {
                // Envía al servidor
                const response = await fetch("../login/guardar.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(userData),
                });

                if (!response.ok) {
                    throw new Error("Error al guardar datos en el servidor");
                }

                const result = await response.text();
                window.redirect("../index.php");
            } catch (error) {
                console.error(error);
                alert("Ocurrió un error al enviar datos al servidor.");
            }

            // Resetea form
            loginForm.reset();
            loginForm.classList.remove("was-validated");
        });
    </script>
</body>

</html>