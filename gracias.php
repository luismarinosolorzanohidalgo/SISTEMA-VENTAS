<?php
include("includes/header.php");

// Validar y obtener el ID del pedido
$id_pedido = isset($_GET['pedido']) ? intval($_GET['pedido']) : 0;
?>

<main class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="gracias-box text-center p-5 rounded-4 shadow-lg animate__animated animate__fadeIn">
        <canvas id="confetti-canvas" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 999;"></canvas>

        <div class="mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="#caa94a"
                class="bi bi-check-circle-fill mb-3 animate__animated animate__tada" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 10.97a.75.75 0 0 0 1.07 0l3.992-3.992a.75.75 0 1 0-1.06-1.06L7.5 9.44 5.53 7.47a.75.75 0 0 0-1.06 1.06l2.5 2.5z" />
            </svg>
            <h2 class="text-gold fw-bold">¡Gracias por tu compra!</h2>
            <p class="lead mt-3 text-muted">
                Tu pedido ha sido procesado correctamente. 🎉<br>
                Te hemos enviado un correo con los detalles.
            </p>
        </div>

        <div class="d-grid gap-3 d-sm-flex justify-content-sm-center mt-4">
            <a href="index.php" class="btn btn-gold btn-lg px-4 shadow-sm">
                🏠 Volver a la tienda
            </a>
            <a href="mis_pedidos.php" class="btn btn-outline-gold btn-lg px-4 shadow-sm">
                📦 Ver mis pedidos
            </a>
        </div>
    </div>
</main>

<?php if ($id_pedido > 0): ?>
    <!-- Botón flotante de factura -->
    <a href="factura.php?id=<?= $id_pedido ?>" target="_blank" class="btn-factura" title="Ver factura">
        🧾 Factura
    </a>
<?php endif; ?>

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<!-- Confetti Script -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script>
    const duration = 2000;
    const animationEnd = Date.now() + duration;
    const defaults = {
        startVelocity: 30,
        spread: 360,
        ticks: 60,
        zIndex: 999
    };

    function randomInRange(min, max) {
        return Math.random() * (max - min) + min;
    }

    const interval = setInterval(function() {
        const timeLeft = animationEnd - Date.now();
        if (timeLeft <= 0) return clearInterval(interval);

        const particleCount = 50 * (timeLeft / duration);
        confetti(Object.assign({}, defaults, {
            particleCount,
            origin: {
                x: randomInRange(0.1, 0.3),
                y: Math.random() - 0.2
            }
        }));
        confetti(Object.assign({}, defaults, {
            particleCount,
            origin: {
                x: randomInRange(0.7, 0.9),
                y: Math.random() - 0.2
            }
        }));
    }, 250);
</script>

<!-- Estilos personalizados -->
<style>
    body {
        background: linear-gradient(to right, #f5f5f5, #ffffff);
    }

    .gracias-box {
        background: #ffffff;
        border: 2px solid #f5e8b7;
        backdrop-filter: blur(10px);
        max-width: 600px;
    }

    .text-gold {
        color: #caa94a;
    }

    .btn-gold {
        background-color: #caa94a;
        color: white;
        border: none;
    }

    .btn-gold:hover {
        background-color: #b59734;
        color: white;
    }

    .btn-outline-gold {
        border: 2px solid #caa94a;
        color: #caa94a;
    }

    .btn-outline-gold:hover {
        background-color: #caa94a;
        color: white;
    }

    .btn-factura {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: #caa94a;
        color: #fff;
        padding: 12px 20px;
        border-radius: 50px;
        font-weight: bold;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        text-decoration: none;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .btn-factura:hover {
        background-color: #b59734;
        transform: scale(1.05);
    }
</style>

<?php include("includes/footer.php"); ?>