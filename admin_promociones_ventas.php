<?php
session_start();
include("includes/db.php");

// Solo rol ventas puede acceder
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ventas') {
    header("Location: login.php");
    exit();
}

// Obtener productos
$resultado = $conn->query("SELECT id, nombre, imagen, precio, precio_promocion, promocion FROM productos ORDER BY promocion DESC, nombre ASC");

$productos = [];
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $productos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Promociones | PowerStreet</title>
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
            max-width: 1200px;
            margin: auto;
            padding: 30px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        h2 {
            color: #d4af37;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-agregar {
            background-color: #d4af37;
            color: white;
        }

        .btn-agregar:hover {
            background-color: #bfa22e;
        }

        .btn-editar, .btn-quitar {
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-editar {
            background-color: #ffc107;
            color: white;
        }

        .btn-quitar {
            background-color: #dc3545;
            color: white;
        }

        .card-img-top {
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }

        .card {
            border: 1px solid #eee;
            border-radius: 16px;
            transition: all 0.3s;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }

        .card:hover {
            transform: scale(1.015);
        }

        .precio-original {
            text-decoration: line-through;
            color: #888;
        }

        .precio-promocion {
            font-size: 1.2rem;
            color: #d4af37;
            font-weight: bold;
        }

        .btn-volver {
            background-color: #d4af37;
            color: white;
            font-weight: bold;
        }

        .btn-volver:hover {
            background-color: #b99928;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🎯 Gestión de Promociones</h2>

    <div class="mb-4 text-end">
        <a href="panel_ventas.php" class="btn btn-volver"><i class="fas fa-arrow-left"></i> Volver</a>
    </div>

    <div class="row">
        <?php foreach ($productos as $prod): ?>
        <div class="col-md-4 mb-4">
            <div class="card p-3 h-100">
                <img src="admin/img/<?= $prod['imagen'] ?>" class="card-img-top mb-3" alt="<?= htmlspecialchars($prod['nombre']) ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($prod['nombre']) ?></h5>
                    <p>
                        <?php if ($prod['promocion']): ?>
                            <span class="precio-original">S/ <?= number_format($prod['precio'], 2) ?></span><br>
                            <span class="precio-promocion">S/ <?= number_format($prod['precio_promocion'], 2) ?></span>
                        <?php else: ?>
                            <span class="text-dark">Precio: <strong>S/ <?= number_format($prod['precio'], 2) ?></strong></span>
                        <?php endif; ?>
                    </p>

                    <?php if ($prod['promocion']): ?>
                        <a href="editar_promocion.php?id=<?= $prod['id'] ?>" class="btn btn-editar w-100 mb-2"><i class="fas fa-edit"></i> Editar Promoción</a>
                        <a href="eliminar_promocion.php?id=<?= $prod['id'] ?>" class="btn btn-quitar w-100" onclick="return confirm('¿Seguro que deseas quitar la promoción?')"><i class="fas fa-trash-alt"></i> Quitar Promoción</a>
                    <?php else: ?>
                        <a href="agregar_promocion.php?id=<?= $prod['id'] ?>" class="btn btn-agregar w-100"><i class="fas fa-plus"></i> Agregar Promoción</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
