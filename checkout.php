<?php
session_start();
include("includes/db.php");
include("includes/header.php");

$carrito = $_SESSION['carrito'] ?? [];

if (empty($carrito)) {
    echo "<div class='container mt-5 alert alert-info text-center'>Tu carrito está vacío. <a href='index.php'>Volver a la tienda</a></div>";
    include("includes/footer.php");
    exit;
}

// Calcular total
$total = 0;
foreach ($carrito as $id => $cantidad) {
    $res = $conn->query("SELECT * FROM productos WHERE id = " . intval($id));
    if ($res && $res->num_rows > 0) {
        $producto = $res->fetch_assoc();
        $total += $producto['precio'] * $cantidad;
    }
}
?>

<main class="container mt-5">
    <h2 class="mb-4 text-center text-primary">🧾 Finalizar Compra</h2>

    <form action="procesar_pedido.php" method="POST" class="row g-4">
        <!-- Datos del cliente -->
        <div class="col-md-6">
            <label for="nombre" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" name="nombre" required>
        </div>

        <div class="col-md-6">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="correo" required>
        </div>

        <div class="col-12">
            <label for="direccion" class="form-label">Dirección de envío</label>
            <input type="text" class="form-control" name="direccion" required>
        </div>

        <!-- Métodos de pago -->
        <div class="col-12">
            <label class="form-label">Método de pago</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="pago" value="Tarjeta" id="pago_tarjeta" required>
                <label class="form-check-label" for="pago_tarjeta">Tarjeta de crédito / débito</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="pago" value="PayPal" id="pago_paypal">
                <label class="form-check-label" for="pago_paypal">PayPal</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="pago" value="Yape" id="pago_yape">
                <label class="form-check-label" for="pago_yape">Yape</label>
            </div>
        </div>

        <!-- QR de Yape -->
        <div id="qr_yape" class="col-12 text-center mt-3" style="display: none;">
            <p class="text-muted">Escanea el código QR con tu app Yape:</p>
            <img src="assets/img/qr_yape.png" alt="QR Yape" style="max-width: 200px;" class="img-fluid shadow rounded">
        </div>

        <!-- Resumen de compra -->
        <div class="col-12">
            <div class="alert alert-success text-end fw-bold">
                Total a pagar: $<?php echo number_format($total, 2); ?>
            </div>
        </div>

        <div class="col-12 text-end">
            <button type="submit" class="btn btn-success">Confirmar Pedido</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</main>

<script>
// Mostrar QR de Yape si se selecciona esa opción
document.addEventListener("DOMContentLoaded", () => {
    const radioYape = document.getElementById("pago_yape");
    const qr = document.getElementById("qr_yape");

    document.querySelectorAll("input[name='pago']").forEach(radio => {
        radio.addEventListener("change", () => {
            qr.style.display = radioYape.checked ? "block" : "none";
        });
    });
});
</script>

<?php include("includes/footer.php"); ?>
