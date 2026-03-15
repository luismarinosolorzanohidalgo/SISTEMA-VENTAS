<?php
session_start();
include 'includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM productos WHERE id = $id");

if ($result->num_rows == 0) {
    echo "Producto no encontrado.";
    exit;
}

$producto = $result->fetch_assoc();
$ruta_base = "admin/img/{$producto['imagen']}";
?>

<?php include 'includes/header.php'; ?>

<style>
    body {
        background: linear-gradient(to right, #fdfbf8, #f5f3ef);
        color: #1c1c1c;
        font-family: 'Montserrat', sans-serif;
        margin: 0;
        padding: 0;
    }

    .container-detalle {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 60px 20px;
    }

    .card-detalle {
        background: #ffffff;
        border-radius: 20px;
        padding: 40px;
        max-width: 1100px;
        width: 100%;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    .img-container {
        text-align: center;
    }

    .img-360 {
        width: 100%;
        height: 450px;
        object-fit: contain;
        border-radius: 15px;
        border: 2px solid #c2b280;
        background: #fffefc;
        transition: transform 0.3s ease-in-out;
    }

    @keyframes giro360 {
        from {
            transform: rotateY(0deg);
        }

        to {
            transform: rotateY(360deg);
        }
    }

    .girar {
        animation: giro360 6s linear infinite;
    }

    .btn {
        padding: 12px 18px;
        margin: 10px 10px 0 0;
        font-weight: bold;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-carrito {
        background-color: #c2b280;
        color: #1c1c1c;
    }

    .btn-carrito:hover {
        background-color: #b3a369;
        transform: scale(1.05);
    }

    .btn-back {
        background-color: #e6dcc0;
        color: #1c1c1c;
    }

    .btn-back:hover {
        background-color: #d8cba3;
        transform: scale(1.05);
    }

    .precio {
        font-size: 1.6rem;
        font-weight: bold;
        color: #a38d58;
        margin: 20px 0;
    }

    .cantidad-input {
        width: 80px;
        padding: 10px;
        margin-top: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-weight: bold;
        text-align: center;
    }

    .info-detalle h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .info-detalle p {
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .etiqueta-oferta {
        display: inline-block;
        background-color: #d9534f;
        color: white;
        font-size: 0.9rem;
        padding: 5px 10px;
        border-radius: 5px;
        margin-bottom: 10px;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .card-detalle {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-detalle">
    <div class="card-detalle">
        <div class="img-container">
            <img
                src="<?= file_exists($ruta_base) ? $ruta_base : 'assets/img/no-image.png'; ?>"
                id="imagen-producto"
                class="img-360"
                alt="Producto">

            <div style="margin-top: 15px;">
                <button class="btn btn-carrito" onclick="iniciarGiro()">Girar 360°</button>
                <button class="btn btn-carrito" onclick="detenerGiro()">Detener</button>
            </div>
        </div>

        <div class="info-detalle">
            <?php if (!empty($producto['promocion']) && !empty($producto['precio_promocion'])): ?>
                <div class="etiqueta-oferta">¡En oferta!</div>
            <?php endif; ?>

            <h2><?= htmlspecialchars($producto['nombre']) ?></h2>
            <p><?= htmlspecialchars($producto['descripcion']) ?></p>

            <?php if (!empty($producto['promocion']) && !empty($producto['precio_promocion'])): ?>
                <p class="precio">
                    <span class="text-decoration-line-through text-muted me-2">
                        $<?= number_format($producto['precio'], 2) ?>
                    </span>
                    <span class="text-danger">
                        $<?= number_format($producto['precio_promocion'], 2) ?>
                    </span>
                </p>
            <?php else: ?>
                <p class="precio">$<?= number_format($producto['precio'], 2) ?></p>
            <?php endif; ?>

            <form action="carrito.php" method="POST">
                <input type="hidden" name="id_producto" value="<?= $producto['id']; ?>">
                <label for="cantidad">Cantidad:</label><br>
                <input type="number" name="cantidad" id="cantidad" class="cantidad-input" min="1" value="1" required>
                <br><br>
                <button type="submit" name="agregar_carrito" class="btn btn-carrito">Agregar al carrito</button>
            </form>

            <br>
            <a href="producto.php" class="btn btn-back">← Volver a productos</a>
            <a href="index.php" class="btn btn-back">← Volver al inicio</a>
        </div>
    </div>
</div>

<script>
    const imagen = document.getElementById('imagen-producto');

    function iniciarGiro() {
        imagen.classList.add('girar');
    }

    function detenerGiro() {
        imagen.classList.remove('girar');
    }
</script>

<?php include 'includes/footer.php'; ?>
