<?php
session_start();
include("includes/db.php");
include("includes/header.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$es_admin = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin');

if (!isset($_GET['id'])) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>❌ Pedido no especificado.</div></div>";
    include("includes/footer.php");
    exit;
}

$pedido_id = intval($_GET['id']);
$id_usuario = $_SESSION['usuario_id'];

$sql = $es_admin ?
    "SELECT p.*, u.nombres AS nombre_usuario, u.email 
     FROM pedidos p 
     JOIN usuarios u ON p.usuario_id = u.id 
     WHERE p.id = ?" :
    "SELECT * FROM pedidos WHERE id = ? AND usuario_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

$es_admin ? $stmt->bind_param("i", $pedido_id) : $stmt->bind_param("ii", $pedido_id, $id_usuario);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();

if (!$pedido) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>🚫 Pedido no encontrado o acceso no autorizado.</div></div>";
    include("includes/footer.php");
    exit;
}

$productos = $conn->query("SELECT dp.*, pr.nombre FROM detalle_pedido dp JOIN productos pr ON dp.producto_id = pr.id WHERE dp.pedido_id = $pedido_id");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
 <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #fdf7e3; /* fondo dorado suave */
        color: #333;
        padding: 20px;
    }

    .container {
        max-width: 900px;
        background: #ffffff;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(184, 145, 75, 0.15);
    }

    h2 {
        font-weight: 600;
        margin-bottom: 25px;
        color: #b8914b;
        text-align: center;
    }

    .badge {
        font-size: 0.95rem;
        padding: 6px 12px;
        border-radius: 20px;
    }

    .list-group-item {
        border-radius: 12px;
        margin-bottom: 6px;
        background-color: #fffaf3;
    }

    .btn {
        border-radius: 14px;
        font-weight: 500;
    }

    .logo-header {
        width: 120px;
        display: block;
        margin: 0 auto 25px;
    }

    footer {
        font-size: 0.9rem;
        text-align: center;
        margin-top: 40px;
        padding-top: 20px;
        color: #888;
    }
</style>

</head>
<body>

<div class="container my-5">
    <img src="img/logo.png" alt="Logo PowerStreet" class="logo-header">

    <h2>📦 Detalle del Pedido #<?php echo $pedido['id']; ?></h2>

    <div class="row mb-3">
        <div class="col-md-6">
            <p><i class="bi bi-calendar-check"></i> <strong>Fecha:</strong> <?php echo date("d/m/Y H:i", strtotime($pedido['fecha'])); ?></p>
            <p><i class="bi bi-credit-card-2-front"></i> <strong>Método de Pago:</strong> <?php echo ucfirst($pedido['metodo_pago']); ?></p>
            <p><i class="bi bi-house-door"></i> <strong>Dirección:</strong> <?php echo htmlspecialchars($pedido['direccion'] ?? 'Sin dirección'); ?></p>
        </div>
        <div class="col-md-6">
            <p><i class="bi bi-cash-stack"></i> <strong>Total:</strong> 
                <span class="badge bg-success fs-6">$<?php echo number_format($pedido['total'], 2); ?></span>
            </p>
            <?php if ($es_admin): ?>
                <p><i class="bi bi-person-circle"></i> <strong>Cliente:</strong> 
                    <?php echo htmlspecialchars($pedido['nombre_usuario']); ?> (<?php echo htmlspecialchars($pedido['email']); ?>)
                </p>
            <?php endif; ?>
        </div>
    </div>

    <h4 class="mt-4 mb-3">🛍 Productos:</h4>
    <ul class="list-group mb-4">
        <?php while ($prod = $productos->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong><?php echo htmlspecialchars($prod['nombre']); ?></strong> 
                    <small class="text-muted">(x<?php echo $prod['cantidad']; ?>)</small>
                </div>
                <span class="text-success fw-semibold">
                    $<?php echo number_format($prod['precio'] * $prod['cantidad'], 2); ?>
                </span>
            </li>
        <?php endwhile; ?>
    </ul>

    <a href="<?php echo $es_admin ? 'admin/pedidos.php' : 'mis_pedidos.php'; ?>" class="btn btn-secondary">
        ← Volver
    </a>
</div>

<footer>
    &copy; <?= date('Y') ?> PowerStreet | Todos los derechos reservados.
</footer>

<?php include("includes/footer.php"); ?>
</body>
</html>
