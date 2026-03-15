<?php
session_start();
include("includes/db.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_pedido = intval($_GET['id']);

// Validar si el pedido existe y pertenece al usuario actual
$stmt = $conn->prepare("SELECT p.*, u.nombres as cliente FROM pedidos p 
                        JOIN usuarios u ON u.id = p.usuario_id 
                        WHERE p.id = ?");
if (!$stmt) {
    die("Error al preparar consulta: " . $conn->error);
}
$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Pedido no encontrado.";
    exit();
}

$pedido = $resultado->fetch_assoc();
$total = $pedido['total'];
$fecha = date("d/m/Y H:i", strtotime($pedido['fecha']));
$cliente = htmlspecialchars($pedido['cliente']);
$metodo_pago = $pedido['metodo_pago'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #<?= $id_pedido ?> - PowerStreet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f2f4f8;
            font-family: 'Segoe UI', sans-serif;
        }
        .invoice {
            background: white;
            padding: 40px;
            max-width: 800px;
            margin: 40px auto;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .invoice h1 {
            font-size: 2rem;
            color: #000;
        }
        .invoice hr {
            border-color: #ccc;
        }
        .logo {
            height: 60px;
        }
        .btn-print {
            margin: 20px auto;
            display: block;
        }
    </style>
</head>
<body>

<div class="invoice" id="factura">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <img src="img/logo.png" alt="Logo" class="logo">
        <h1>Factura</h1>
    </div>

    <p><strong>Fecha:</strong> <?= $fecha ?></p>
    <p><strong>Cliente:</strong> <?= $cliente ?></p>
    <p><strong>Método de Pago:</strong> <?= $metodo_pago ?></p>
    <p><strong>ID de Pedido:</strong> #<?= $id_pedido ?></p>

    <hr>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cant.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $detalle = $conn->prepare("SELECT d.*, p.nombre FROM detalle_pedido d 
                                       JOIN productos p ON p.id = d.producto_id 
                                       WHERE d.pedido_id = ?");
            $detalle->bind_param("i", $id_pedido);
            $detalle->execute();
            $res = $detalle->get_result();

            while ($fila = $res->fetch_assoc()):
                $nombre = htmlspecialchars($fila['nombre']);
                $precio = $fila['precio'];
                $cantidad = $fila['cantidad'];
                $subtotal = $precio * $cantidad;
            ?>
                <tr>
                    <td><?= $nombre ?></td>
                    <td>$<?= number_format($precio, 2) ?></td>
                    <td><?= $cantidad ?></td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end fw-bold">Total:</td>
                <td class="fw-bold">$<?= number_format($total, 2) ?></td>
            </tr>
        </tfoot>
    </table>

    <p class="text-center text-muted mt-4">Gracias por tu compra en <strong>PowerStreet</strong> 🛍️</p>
</div>

<!-- Botón para imprimir o guardar como PDF -->
<button class="btn btn-primary btn-print" onclick="window.print()">🖨️ Imprimir / Guardar como PDF</button>

</body>
</html>
