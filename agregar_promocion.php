<?php
session_start();
include("includes/db.php");

// Verificación de sesión y rol
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ventas') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_promociones_ventas.php");
    exit();
}

$id = intval($_GET['id']);

// Obtener datos del producto
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Producto no encontrado.";
    exit();
}

$producto = $resultado->fetch_assoc();

// Al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $precio_promocion = floatval($_POST['precio_promocion']);

    // Validar que el precio promocional sea menor al original
    if ($precio_promocion >= $producto['precio']) {
        $error = "⚠ El precio promocional debe ser menor al precio original.";
    } else {
        $stmt = $conn->prepare("UPDATE productos SET promocion = 1, precio_promocion = ? WHERE id = ?");
        $stmt->bind_param("di", $precio_promocion, $id);
        $stmt->execute();

        header("Location: admin_promociones_ventas.php?mensaje=promocion_agregada");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Promoción | PowerStreet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fffdf5;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 35px 25px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        h3 {
            color: #d4af37;
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-guardar {
            background-color: #d4af37;
            color: white;
            border-radius: 10px;
            font-weight: bold;
        }

        .btn-guardar:hover {
            background-color: #c3a12d;
        }

        .btn-cancelar {
            background-color: #e0e0e0;
            color: #333;
            border-radius: 10px;
            font-weight: bold;
        }

        .error {
            background-color: #ffe2e2;
            color: #a10000;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .producto-img {
            display: block;
            margin: 0 auto 15px;
            max-height: 180px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .precio-original {
            text-align: center;
            color: #777;
            margin-bottom: 15px;
        }

        footer {
            text-align: center;
            font-size: 0.9rem;
            color: #777;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>🎯 Agregar Promoción</h3>

    <img src="admin/img/<?= htmlspecialchars($producto['imagen']) ?>" class="producto-img" alt="Imagen del producto" onerror="this.src='img/default.png'">

    <p class="text-center fw-bold"><?= htmlspecialchars($producto['nombre']) ?></p>
    <p class="precio-original">Precio original: <strong>S/ <?= number_format($producto['precio'], 2) ?></strong></p>

    <?php if (isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="mb-3">
            <label for="precio_promocion" class="form-label">Precio Promocional</label>
            <input type="number" step="0.01" min="0" name="precio_promocion" id="precio_promocion" class="form-control" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="admin_promociones_ventas.php" class="btn btn-cancelar"><i class="fas fa-arrow-left"></i> Cancelar</a>
            <button type="submit" class="btn btn-guardar"><i class="fas fa-check-circle"></i> Guardar</button>
        </div>
    </form>
</div>

<footer>
    &copy; <?= date('Y') ?> PowerStreet | Todos los derechos reservados.
</footer>

</body>
</html>
