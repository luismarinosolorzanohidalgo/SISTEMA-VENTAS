<?php include("includes/header.php"); ?>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(135deg, #fffdf7, #f8f5e9);
        font-family: 'Montserrat', sans-serif;
        color: #1c1c1c;
    }

    .logo-container {
        text-align: center;
        margin-top: 30px;
        margin-bottom: 10px;
    }

    .logo-container img {
        max-width: 160px;
        height: auto;
        filter: drop-shadow(0px 4px 8px rgba(0,0,0,0.15));
    }

    h2.text-gold {
        color: #d4af37;
        font-weight: 700;
    }

    .form-container {
        background: #ffffff;
        padding: 40px;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }

    .form-container:hover {
        transform: translateY(-3px);
    }

    .form-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 6px;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #ccc;
        padding: 12px 14px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #d4af37;
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
    }

    .btn-gold {
        background: #d4af37;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        padding: 12px;
        font-size: 1rem;
        transition: background 0.3s ease, transform 0.2s;
        color: white;
    }

    .btn-gold i {
        margin-right: 8px;
    }

    .btn-gold:hover {
        background: #c19e2e;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
    }

    .btn-outline-dark {
        font-weight: 600;
        border-radius: 50px;
        padding: 10px 25px;
        transition: all 0.3s ease;
        border: 2px solid #d4af37;
        color: #d4af37;
        background: transparent;
    }

    .btn-outline-dark:hover {
        background-color: #d4af37;
        color: white;
    }

    .map-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        margin-top: 30px;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 25px;
        }
    }
</style>

<div class="container my-4">
    <!-- LOGO -->
    <div class="logo-container">
        <img src="img/logo.png" alt="Logo">
    </div>

    <!-- Título y descripción -->
    <h2 class="text-center text-gold mb-2">Contáctanos</h2>
    <p class="text-center text-muted mb-4 fs-5">¿Tienes preguntas, sugerencias o necesitas ayuda? ¡Escríbenos con confianza!</p>

    <!-- Formulario -->
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <form action="enviar_mensaje.php" method="post" class="form-container">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required>
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="mb-3">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea class="form-control" id="mensaje" name="mensaje" rows="5" placeholder="¿Cómo podemos ayudarte?" required></textarea>
                </div>

                <button type="submit" class="btn btn-gold w-100">
                    <i class="bi bi-send-fill"></i> Enviar mensaje
                </button>
            </form>

            <!-- Botón para volver -->
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-outline-dark">
                    <i class="bi bi-arrow-left-circle me-2"></i> Volver al inicio
                </a>
            </div>
        </div>
    </div>

    <!-- Mapa -->
    <div class="text-center mt-5">
        <h5 class="text-secondary fw-semibold">📍 Nuestra ubicación</h5>
        <div class="map-container mt-3">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3900.896799091154!2d-77.03785258471912!3d-12.04637399148206!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c8c5126e2d6f%3A0x7b2e6c3f26c8e8a5!2sLima!5e0!3m2!1ses!2spe!4v1616442684282"
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
