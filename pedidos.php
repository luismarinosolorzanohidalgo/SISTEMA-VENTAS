<?php
session_start();
include("includes/db.php");

// Solo accesible para usuarios con rol 'ventas'
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ventas') {
    header("Location: login.php");
    exit();
}

// Procesar cambio de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['estado'])) {
    $pedido_id = intval($_POST['pedido_id']);
    $nuevo_estado = trim($_POST['estado']);

    $stmt = $conn->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $pedido_id);
    $stmt->execute();
    header("Location: pedidos.php?actualizado=1");
    exit();
}

// Obtener pedidos
$consulta = $conn->query("
    SELECT p.id, p.fecha, p.total, p.metodo_pago, p.estado, u.nombres AS cliente
    FROM pedidos p
    JOIN usuarios u ON p.usuario_id = u.id
    ORDER BY p.fecha DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedidos | PowerStreet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fefefe;
            font-family: 'Segoe UI', sans-serif;
            padding: 30px;
        }

        h2 {
            color: #d4af37;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .table th {
            background-color: #d4af37;
            color: white;
            text-align: center;
        }

        .btn-actualizar {
            background-color: #d4af37;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .btn-actualizar:hover {
            background-color: #b8992f;
        }

        select {
            border-radius: 6px;
            padding: 4px 8px;
        }

        .alert-success {
            background-color: #e8f8e3;
            color: #2b7a1b;
            font-weight: 500;
        }

        .volver {
            margin-top: 20px;
            display: inline-block;
            background-color: #d4af37;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
        }

        .volver:hover {
            background-color: #b8992f;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📦 Gestión de Pedidos</h2>

    <?php if (isset($_GET['actualizado'])): ?>
        <div class="alert alert-success text-center">✅ Estado actualizado correctamente.</div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center shadow-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Método de Pago</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pedido = $consulta->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $pedido['id'] ?></td>
                        <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($pedido['fecha'])) ?></td>
                        <td>S/ <?= number_format($pedido['total'], 2) ?></td>
                        <td><?= ucfirst($pedido['metodo_pago']) ?></td>
                        <td>
                            <form method="POST" class="d-flex justify-content-center align-items-center gap-2">
                                <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                <select name="estado" class="form-select form-select-sm" required>
                                    <?php
                                    $estados = ['Pendiente', 'Procesando', 'Enviado', 'Entregado', 'Cancelado'];
                                    foreach ($estados as $estado) {
                                        $selected = $pedido['estado'] === $estado ? 'selected' : '';
                                        echo "<option value=\"$estado\" $selected>$estado</option>";
                                    }
                                    ?>
                                </select>
                                <button type="submit" class="btn btn-actualizar"><i class="fas fa-sync-alt"></i></button>
                            </form>
                        </td>
                        <td>
                            <a href="detalle_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <a href="panel_ventas.php" class="volver"><i class="fas fa-arrow-left"></i> Volver al Panel</a>
</div>

</body>
</html>
