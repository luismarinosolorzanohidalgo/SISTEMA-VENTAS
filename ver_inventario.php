<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'almacenero') {
    header("Location: login.php");
    exit();
}

$categoriasDisponibles = ["Calzado", "Deportivo", "Accesorio", "Ropa", "Otros"];
$categoriaSeleccionada = $_GET['categoria'] ?? '';

$queryBase = "SELECT id, nombre, categoria, stock, talla, color, marca FROM productos";
if ($categoriaSeleccionada && in_array($categoriaSeleccionada, $categoriasDisponibles)) {
    $stmt = $conn->prepare($queryBase . " WHERE categoria = ? ORDER BY nombre ASC");
    $stmt->bind_param("s", $categoriaSeleccionada);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $resultado = $conn->query($queryBase . " ORDER BY nombre ASC");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inventario | PowerStreet</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fdfdfd;
      font-family: 'Segoe UI', sans-serif;
    }
    .header {
      background-color: #d4af37;
      color: white;
      padding: 1rem;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .btn-volver, .btn-exportar {
      background-color: #d4af37;
      color: white;
      border: none;
      transition: background-color 0.3s ease;
    }
    .btn-volver:hover, .btn-exportar:hover {
      background-color: #b89d2f;
    }
    .table-container {
      background-color: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.5s ease-in-out;
    }
    .table thead {
      background-color: #343a40;
      color: white;
    }
    h2 {
      font-weight: bold;
    }
    .fade-in {
      animation: fadeIn 1s ease;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="header text-center">
    <h2 class="animate__animated animate__fadeInDown">Inventario General - PowerStreet</h2>
  </div>

  <div class="container my-5">
    <form class="row g-3 mb-4 justify-content-between fade-in" method="GET">
      <div class="col-md-4">
        <select name="categoria" class="form-select">
          <option value="">Todas las categorías</option>
          <?php foreach ($categoriasDisponibles as $cat): ?>
            <option value="<?= $cat ?>" <?= $categoriaSeleccionada == $cat ? 'selected' : '' ?>><?= $cat ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4 text-end">
        <button type="submit" class="btn btn-dark"><i class="bi bi-funnel-fill"></i> Filtrar</button>
        <a href="exportar_inventario.php?categoria=<?= urlencode($categoriaSeleccionada) ?>" class="btn btn-exportar"><i class="bi bi-download"></i> Exportar Excel</a>
      </div>
    </form>

    <div class="table-container fade-in">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-center">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Talla</th>
              <th>Color</th>
              <th>Marca</th>
              <th>Stock</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($producto = $resultado->fetch_assoc()): ?>
              <tr>
                <td><?= $producto['id'] ?></td>
                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                <td><?= htmlspecialchars($producto['categoria']) ?></td>
                <td><?= htmlspecialchars($producto['talla']) ?></td>
                <td><?= htmlspecialchars($producto['color']) ?></td>
                <td><?= htmlspecialchars($producto['marca']) ?></td>
                <td>
                  <?php if ($producto['stock'] <= 5): ?>
                    <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> <?= $producto['stock'] ?></span>
                  <?php elseif ($producto['stock'] <= 15): ?>
                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle"></i> <?= $producto['stock'] ?></span>
                  <?php else: ?>
                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> <?= $producto['stock'] ?></span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="text-center mt-4">
      <a href="panel_almacen.php" class="btn btn-volver px-4 py-2"><i class="bi bi-arrow-left-circle"></i> Volver al Panel</a>
    </div>
  </div>
</body>
</html>
