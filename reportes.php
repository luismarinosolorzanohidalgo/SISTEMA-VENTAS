<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

$fecha_inicio = $_GET['inicio'] ?? '';
$fecha_fin = $_GET['fin'] ?? '';

$where = "";
$params = [];

if ($fecha_inicio && $fecha_fin) {
    $where = "WHERE p.fecha BETWEEN ? AND ?";
    $params[] = $fecha_inicio . " 00:00:00";
    $params[] = $fecha_fin . " 23:59:59";
}

$sql = "
    SELECT p.id, u.nombres AS cliente, p.fecha, p.total, p.estado
    FROM pedidos p
    JOIN usuarios u ON p.usuario_id = u.id
    $where
    ORDER BY p.fecha DESC
";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param("ss", ...$params);
}
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Pedidos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        :root {
            --dorado: #d4af37;
            --dorado-suave: #f5e6b3;
            --blanco: #ffffff;
        }

        body {
            background-color: var(--blanco);
            color: #333;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            margin-top: 30px;
        }

        h1 {
            color: var(--dorado);
            text-align: center;
            font-weight: bold;
            margin-bottom: 30px;
        }

        thead th {
            background-color: var(--dorado-suave);
            color: #5a4d2f;
        }

        .badge {
            font-size: 0.9rem;
            padding: 6px 12px;
            border-radius: 8px;
        }

        .btn-volver {
            background-color: var(--dorado);
            border: none;
            color: white;
            font-weight: bold;
        }

        .btn-volver:hover {
            background-color: #b38e2e;
        }

        .logo {
            height: 100px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- LOGO -->
    <div class="text-center mb-3">
        <img src="img/logo.png" alt="Logo Empresa" class="logo">
    </div>

    <h1>📊 Reportes de Pedidos</h1>

    <!-- FILTRO -->
    <form method="GET" class="row g-3 align-items-center justify-content-center mb-4 no-print">
        <div class="col-auto">
            <label for="inicio" class="col-form-label">Desde:</label>
        </div>
        <div class="col-auto">
            <input type="date" class="form-control" name="inicio" id="inicio" value="<?= htmlspecialchars($fecha_inicio) ?>" required>
        </div>
        <div class="col-auto">
            <label for="fin" class="col-form-label">Hasta:</label>
        </div>
        <div class="col-auto">
            <input type="date" class="form-control" name="fin" id="fin" value="<?= htmlspecialchars($fecha_fin) ?>" required>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">🔍 Filtrar</button>
        </div>
        <div class="col-auto">
            <a href="reportes.php" class="btn btn-outline-secondary">🔁 Limpiar</a>
        </div>
    </form>

    <!-- BOTONES -->
    <div class="text-center mb-3 no-print">
        <a href="exportar_excel.php?inicio=<?= urlencode($fecha_inicio) ?>&fin=<?= urlencode($fecha_fin) ?>" class="btn btn-success me-2">📥 Exportar a Excel</a>
        <button onclick="window.print()" class="btn btn-outline-secondary">🖨️ Imprimir / PDF</button>
    </div>

    <!-- TABLA -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total (S/)</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($resultado->num_rows > 0): ?>
                <?php while ($pedido = $resultado->fetch_assoc()): ?>
                    <?php
                        $estado = strtolower($pedido['estado']);
                        $color = match ($estado) {
                            'entregado' => 'success',
                            'pendiente' => 'warning text-dark',
                            'en camino' => 'primary',
                            'en proceso' => 'info text-dark',
                            'rechazado' => 'danger',
                            default => 'secondary'
                        };
                    ?>
                    <tr>
                        <td><?= $pedido['id'] ?></td>
                        <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($pedido['fecha'])) ?></td>
                        <td>S/ <?= number_format($pedido['total'], 2) ?></td>
                        <td><span class="badge bg-<?= $color ?>"><?= ucfirst($estado) ?></span></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-muted">No hay pedidos en este rango.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4 no-print">
        <a href="panel_almacen.php" class="btn btn-volver px-4 py-2">⬅️ Volver al Panel</a>
    </div>
</div>
    <!-- GRÁFICOS -->
    <div class="mt-5">
        <h3 class="text-center mb-4">📈 Estadísticas de Pedidos</h3>
        <div class="row justify-content-center">
            <div class="col-md-6 mb-4">
                <canvas id="graficoCircular"></canvas>
            </div>
            <div class="col-md-8">
                <canvas id="graficoBarras"></canvas>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script>
    document.addEventListener("DOMContentLoaded", function () {
        const params = new URLSearchParams({
            inicio: "<?= urlencode($fecha_inicio) ?>",
            fin: "<?= urlencode($fecha_fin) ?>"
        });

        fetch('graficos_data.php?' + params.toString())
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('graficoBarras').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.fechas.labels,
                        datasets: [{
                            label: 'Ventas Totales (S/)',
                            data: data.fechas.valores,
                            backgroundColor: '#d4af37'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (v) => 'S/ ' + v
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error("Error al obtener los datos del gráfico:", error);
            });
    });
</script>
</body>
</html>
