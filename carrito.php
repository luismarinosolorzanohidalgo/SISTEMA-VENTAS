<?php
session_start();
include("includes/db.php");
include("includes/header.php");

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
$carrito = $_SESSION['carrito'];

// Manejo de acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agregar_carrito'])) {
        $id = intval($_POST['id_producto']);
        $cantidad = intval($_POST['cantidad']);
        if ($id > 0 && $cantidad > 0) {
            $carrito[$id] = ($carrito[$id] ?? 0) + $cantidad;
        }
    } elseif (isset($_POST['actualizar'])) {
        $id = intval($_POST['id']);
        $cantidad = intval($_POST['cantidad']);
        if ($cantidad > 0) {
            $carrito[$id] = $cantidad;
        } else {
            unset($carrito[$id]);
        }
    }
    $_SESSION['carrito'] = $carrito;
    header("Location: carrito.php");
    exit();
}

// Manejo de acciones GET
if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    unset($carrito[$idEliminar]);
    $_SESSION['carrito'] = $carrito;
    header("Location: carrito.php");
    exit();
}
if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
    header("Location: carrito.php");
    exit();
}
?>

<style>
    body {
        background: linear-gradient(to right, #fff9e6, #ffffff);
        font-family: 'Segoe UI', sans-serif;
    }
    .carrito-container {
        max-width: 1200px;
        margin: 40px auto;
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        padding: 10px;
    }
    .tabla-carrito, .resumen {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.05);
        padding: 30px;
    }
    .tabla-carrito {
        flex: 3;
        overflow-x: auto;
    }
    .resumen {
        flex: 1;
        min-width: 300px;
        position: sticky;
        top: 20px;
    }
    th {
        background: #212529;
        color: #fff;
        text-align: center;
    }
    td {
        vertical-align: middle;
    }
    .producto-img {
        max-width: 60px;
        height: auto;
        border-radius: 8px;
        margin-right: 10px;
    }
    .form-control-sm {
        max-width: 70px;
    }
    .btn {
        border-radius: 10px;
    }
    .btn-factura {
        background: #111;
        color: white;
        width: 100%;
    }
    #qr_yape img {
        max-width: 100%;
        border-radius: 12px;
        border: 2px solid #111;
    }
</style>

<div class="carrito-container">
    <div class="tabla-carrito">
        <h2 class="mb-4">🛒 Tu Carrito</h2>
        <?php if (empty($carrito)): ?>
            <div class="alert alert-warning text-center">Tu carrito está vacío. <a href="index.php">Volver a la tienda</a></div>
        <?php else: ?>
            <table class="table table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Quitar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($carrito as $id => $cantidad):
                        $res = $conn->prepare("SELECT * FROM productos WHERE id = ?");
                        $res->bind_param("i", $id);
                        $res->execute();
                        $producto = $res->get_result()->fetch_assoc();
                        if ($producto):
                            $subtotal = $producto['precio'] * $cantidad;
                            $total += $subtotal;
                    ?>
                    <tr>
                        <td class="text-start">
                            <img src="admin/img/<?= $producto['imagen'] ?>" class="producto-img" alt="">
                            <?= htmlspecialchars($producto['nombre']) ?>
                        </td>
                        <td>$<?= number_format($producto['precio'], 2) ?></td>
                        <td>
                            <form method="POST" class="d-flex justify-content-center">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="number" name="cantidad" class="form-control form-control-sm me-2" value="<?= $cantidad ?>" min="1">
                                <button name="actualizar" class="btn btn-sm btn-outline-primary">✔</button>
                            </form>
                        </td>
                        <td>$<?= number_format($subtotal, 2) ?></td>
                        <td><a href="?eliminar=<?= $id ?>" class="btn btn-sm btn-outline-danger">🗑️</a></td>
                    </tr>
                    <?php endif; endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="resumen">
        <h4 class="mb-4">Resumen del Pedido</h4>
        <?php if (!empty($carrito)): ?>
            <p><strong>Subtotal:</strong> $<?= number_format($total, 2) ?></p>
            <p><strong>Envío:</strong> $5.00</p>
            <p><strong>Impuesto (18%):</strong> $<?= number_format($total * 0.18, 2) ?></p>
            <hr>
            <p class="h5">Total: <strong>$<?= number_format($total + 5 + $total * 0.18, 2) ?></strong></p>

            <form action="procesar_pedido.php" method="POST" class="mt-3">
                <div class="mb-3">
                    <label for="metodo_pago" class="form-label">Método de pago</label>
                    <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                        <option value="">Selecciona uno</option>
                        <option value="Yape">Yape</option>
                        <option value="Efectivo">Efectivo</option>
                    </select>
                </div>

                <div id="qr_yape" class="mb-3" style="display:none;">
                    <p class="text-muted">Escanea este código con Yape:</p>
                    <img src="admin/img/qr_yape.png" alt="QR Yape">
                </div>

                <button type="submit" class="btn btn-success mb-2">✅ Confirmar Pedido</button>
                <a href="?vaciar=1" class="btn btn-outline-danger mb-2">🗑 Vaciar Carrito</a>
                <a href="index.php" class="btn btn-secondary">← Seguir Comprando</a>
            </form>
        <?php else: ?>
            <p class="text-muted">No hay productos para procesar.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    const metodoPago = document.getElementById("metodo_pago");
    const qrYape = document.getElementById("qr_yape");

    metodoPago.addEventListener("change", () => {
        qrYape.style.display = metodoPago.value === "Yape" ? "block" : "none";
    });
</script>

<?php include("includes/footer.php"); ?>
