<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] !== 'almacenero' && $_SESSION['rol'] !== 'admin')) {
    header("Location: ../login.php");
    exit();
}

$categorias = ["Calzado", "Deportivo", "Accesorio", "Ropa", "Otros"];
$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio = floatval($_POST["precio"]);
    $stock = intval($_POST["stock"]);
    $categoria = $_POST["categoria"];
    $talla = trim($_POST["talla"]);
    $color = trim($_POST["color"]);
    $marca = trim($_POST["marca"]);

    $imagenNombre = "";
    if (!empty($_FILES["imagen"]["name"])) {
        $imagenTmp = $_FILES["imagen"]["tmp_name"];
        $imagenExt = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
        $imagenNombre = time() . "." . $imagenExt;
        $destino = __DIR__ . "/img/" . $imagenNombre;

        if (!move_uploaded_file($imagenTmp, $destino)) {
            $error = "Error al subir la imagen.";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, categoria, talla, color, marca, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdisssss", $nombre, $descripcion, $precio, $stock, $categoria, $talla, $color, $marca, $imagenNombre);
        $stmt->execute();
        $mensaje = "✅ Producto agregado correctamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto | PowerStreet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #f9f7f2, #ece1c8);
            padding: 40px 0;
            color: #2c2c2c;
        }

        .card {
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
            animation: fadeIn 0.7s ease;
        }

        .btn-guardar {
            background-color: #c9b57a;
            color: #1c1c1c;
            font-weight: bold;
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-guardar:hover {
            background-color: #bba460;
            transform: scale(1.03);
        }

        .btn-volver {
            background-color: #343a40;
            color: white;
            font-weight: bold;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-volver:hover {
            background-color: #000;
            transform: scale(1.03);
        }

        label {
            font-weight: 600;
        }

        .alert {
            border-radius: 12px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 px-2">
        <h2 class="fw-bold text-dark"><i class="bi bi-box-seam me-2"></i>Agregar Producto</h2>
        <a href="panel_almacen.php" class="btn btn-volver">
            <i class="bi bi-arrow-left-circle me-1"></i> Volver al panel
        </a>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success animate__animated animate__fadeInDown">
            <?= $mensaje ?>
        </div>
    <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger animate__animated animate__fadeInDown">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <div class="card animate__animated animate__fadeInUp">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Nombre del producto</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3" required></textarea>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Precio (S/)</label>
                    <input type="number" name="precio" step="0.01" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Categoría</label>
                    <select name="categoria" class="form-select" required>
                        <option value="">Selecciona una categoría</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Talla</label>
                    <input type="text" name="talla" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Color</label>
                    <input type="text" name="color" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Marca</label>
                    <input type="text" name="marca" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Imagen del producto</label>
                <input type="file" name="imagen" accept="image/*" class="form-control">
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-guardar">
                    <i class="bi bi-plus-circle me-1"></i> Agregar Producto
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
