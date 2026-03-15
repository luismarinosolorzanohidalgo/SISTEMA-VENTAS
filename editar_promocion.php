<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ventas') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: promociones_ventas.php");
    exit();
}

$id = $_GET['id'];

// Obtener datos del producto
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ? AND promocion = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: promociones_ventas.php");
    exit();
}

$producto = $resultado->fetch_assoc();

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = floatval($_POST['precio']);
    $precio_promocion = floatval($_POST['precio_promocion']);

    // Validación simple
    if ($precio_promocion >= $precio) {
        $error = "El precio promocional debe ser menor que el precio normal.";
    } else {
        // Imagen
        if (!empty($_FILES['imagen']['name'])) {
            $nombre_imagen = uniqid() . "_" . basename($_FILES["imagen"]["name"]);
            $ruta_imagen = "admin/img/" . $nombre_imagen;
            move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta_imagen);
        } else {
            $nombre_imagen = $producto['imagen'];
        }

        // Actualizar en DB
        $stmtUpdate = $conn->prepare("UPDATE productos SET descripcion = ?, precio = ?, precio_promocion = ?, imagen = ? WHERE id = ?");
        $stmtUpdate->bind_param("sddsi", $descripcion, $precio, $precio_promocion, $nombre_imagen, $id);
        $stmtUpdate->execute();

        header("Location: promociones_ventas.php?editado=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Promoción | PowerStreet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fffdf5;
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .container {
            max-width: 700px;
            margin: auto;
            padding: 40px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        h2 {
            color: #d4af37;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            font-weight: 600;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-guardar {
            background-color: #d4af37;
            color: white;
            font-weight: bold;
            border-radius: 10px;
        }

        .btn-guardar:hover {
            background-color: #c9a833;
        }

        .btn-cancelar {
            background-color: #fff;
            border: 2px solid #d4af37;
            color: #d4af37;
            font-weight: bold;
            border-radius: 10px;
        }

        .btn-cancelar:hover {
            background-color: #fffbe5;
        }

        .img-preview {
            max-height: 150px;
            margin-bottom: 15px;
            border-radius: 10px;
        }

        footer {
            text-align: center;
            font-size: 0.9rem;
            color: #777;
            margin-top: 40px;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>✏️ Editar Promoción</h2>

    <?php if (isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nombre del producto</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" readonly>
        </div>

        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Precio original (S/)</label>
            <input type="number" name="precio" step="0.01" min="0" class="form-control" value="<?= $producto['precio'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Precio promocional (S/)</label>
            <input type="number" name="precio_promocion" step="0.01" min="0" class="form-control" value="<?= $producto['precio_promocion'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Imagen actual</label><br>
            <img src="admin/img/<?= $producto['imagen'] ?>" class="img-preview" onerror="this.src='img/default.png';">
        </div>

        <div class="mb-3">
            <label>Cambiar imagen (opcional)</label>
            <input type="file" name="imagen" accept="image/*" class="form-control">
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="admin_promociones_ventas.php" class="btn btn-cancelar"><i class="fas fa-arrow-left"></i> Cancelar</a>
            <button type="submit" class="btn btn-guardar"><i class="fas fa-save"></i> Guardar cambios</button>
        </div>
    </form>
</div>

<footer>
    &copy; <?= date('Y') ?> PowerStreet | Todos los derechos reservados.
</footer>

</body>
</html>
