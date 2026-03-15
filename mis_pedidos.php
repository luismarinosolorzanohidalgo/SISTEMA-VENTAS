<?php
session_start();
include("includes/db.php");
include("includes/header.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$resultado = $conn->query("SELECT * FROM pedidos WHERE usuario_id = $id_usuario ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos | PowerStreet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fff9ec, #ffffff);
            color: #111;
            padding: 30px 10px;
        }

        .container {
            max-width: 980px;
            margin: auto;
            background: #fff;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 12px 28px rgba(0,0,0,0.08);
            position: relative;
        }

        h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 30px;
            color: #b38e2e;
        }

        .logo-header {
            width: 110px;
            display: block;
            margin: 0 auto 20px;
        }

        .table thead th {
            position: sticky;
            top: 0;
            z-index: 5;
            background-color: #d4af37;
            color: white;
            text-align: center;
        }

        .table tbody td {
            vertical-align: middle;
        }

        .toolbar {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #fff;
            padding-bottom: 15px;
            margin-bottom: 10px;
        }

        .btn {
            border-radius: 12px;
            font-weight: 500;
        }

        .form-control {
            border-radius: 10px;
        }

        .volver {
            margin: 30px auto 10px;
            display: block;
            width: fit-content;
            padding: 10px 24px;
            background-color: #d4af37;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: background 0.3s;
        }
        .volver:hover {
            background-color: #b38e2e;
        }

        footer {
            font-size: 0.9rem;
            text-align: center;
            margin-top: 30px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <img src="img/logo.png" alt="Logo PowerStreet" class="logo-header">
    <h2>Mis Pedidos 🛍️</h2>

    <?php if ($resultado->num_rows === 0): ?>
        <div class="alert alert-warning text-center">No has realizado ningún pedido todavía.</div>
    <?php else: ?>
        <div class="toolbar row g-3 align-items-center">
            <div class="col-md-6">
                <input type="text" id="buscador" class="form-control" placeholder="🔍 Buscar en mis pedidos...">
            </div>
            <div class="col-md-3">
                <a href="exportar_excel.php" class="btn btn-success w-100"><i class="fas fa-file-excel"></i> Excel</a>
            </div>
            <div class="col-md-3">
                <a href="exportar_pdf.php" class="btn btn-danger w-100"><i class="fas fa-file-pdf"></i> PDF</a>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-hover table-bordered align-middle text-center shadow-sm" id="tablaPedidos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Método</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pedido = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $pedido['id'] ?></td>
                            <td><?= date("d/m/Y H:i", strtotime($pedido['fecha'])) ?></td>
                            <td>S/ <?= number_format($pedido['total'], 2) ?></td>
                            <td><?= ucfirst($pedido['metodo_pago']) ?></td>
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
    <?php endif; ?>
</div>

<script>
document.getElementById('buscador').addEventListener('keyup', function () {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaPedidos tbody tr');
    filas.forEach(fila => {
        let texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
    });
});
</script>

<a href="panel_cliente.php" class="volver"><i class="fas fa-arrow-left"></i> Volver</a>
<footer>
    &copy; <?= date('Y') ?> PowerStreet | Todos los derechos reservados.
</footer>

</body>
</html>
