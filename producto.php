<?php
session_start();
include("includes/db.php");

// Contador de vistas
$pagina = basename($_SERVER['PHP_SELF']);
$stmt = $conn->prepare("SELECT vistas FROM vistas WHERE pagina = ?");
$stmt->bind_param("s", $pagina);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $conn->query("UPDATE vistas SET vistas = vistas + 1 WHERE pagina = '$pagina'");
} else {
    $stmt_insert = $conn->prepare("INSERT INTO vistas (pagina) VALUES (?)");
    $stmt_insert->bind_param("s", $pagina);
    $stmt_insert->execute();
}

// Obtener categorías distintas
$categorias = $conn->query("SELECT DISTINCT categoria FROM productos WHERE categoria IS NOT NULL");

// Filtro por categoría
$filtro_categoria = $_GET['categoria'] ?? '';
$sql = "SELECT * FROM productos";
if ($filtro_categoria != '') {
    $sql .= " WHERE categoria = '" . $conn->real_escape_string($filtro_categoria) . "'";
}
$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);

$rol = $_SESSION['rol'] ?? null;
$usuario_id = $_SESSION['usuario_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos | PowerStreet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #f5f3ef;
      color: #1c1c1c;
    }
    .navbar {
      background-color: #1c1c1c;
      color: white;
    }
    .navbar-brand, .nav-link {
      color: white;
    }
    .hero {
      background: linear-gradient(135deg, #ded2c6, #eae7e1);
      padding: 3rem 1rem;
      border-radius: 1rem;
      text-align: center;
      margin-bottom: 2rem;
    }
    .product-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    .product-card:hover {
      transform: translateY(-5px);
    }
    .product-card img {
      width: 100%;
      height: 250px;
      object-fit: contain;
      background: #fafafa;
      border-bottom: 1px solid #eee;
    }
    .product-card .card-body {
      padding: 1rem;
      text-align: center;
    }
    .product-card .card-title {
      font-size: 1.1rem;
      font-weight: bold;
    }
    .product-card .price {
      font-size: 1.2rem;
      color: #28a745;
      font-weight: 600;
      margin: 0.5rem 0;
    }
    .btn-volver {
      margin-bottom: 2rem;
    }
    .btn-flotante {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: #1c1c1c;
      color: white;
      border-radius: 50%;
      padding: 15px;
      font-size: 20px;
      z-index: 999;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .btn-flotante:hover {
      background: #333;
    }
    footer {
      background: #2d2d2d;
      color: white;
      text-align: center;
      padding: 1rem;
      margin-top: 3rem;
      border-top: 3px solid #c2b280;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg px-4">
    <a class="navbar-brand" href="index.php">PowerStreet</a>
  </nav>

  <div class="container mt-4">
    <div class="text-start btn-volver">
      <a href="index.php" class="btn btn-outline-dark">
        <i class="bi bi-arrow-left-circle"></i> Volver al inicio
      </a>
    </div>

    <div class="hero">
      <h1 class="display-5 fw-bold">Nuestros Productos</h1>
      <p class="lead">Explora lo más reciente en artículos deportivos</p>
    </div>

    <!-- Filtro por categoría -->
    <div class="mb-4 text-center">
      <form method="GET" action="producto.php" class="d-inline-block">
        <select name="categoria" class="form-select d-inline-block w-auto">
          <option value="">Todas las categorías</option>
          <?php while ($cat = $categorias->fetch_assoc()): ?>
            <option value="<?= $cat['categoria'] ?>" <?= ($filtro_categoria == $cat['categoria']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['categoria']) ?>
            </option>
          <?php endwhile; ?>
        </select>
        <button type="submit" class="btn btn-dark ms-2">Filtrar</button>
        <?php if ($filtro_categoria): ?>
          <a href="producto.php" class="btn btn-outline-secondary ms-1">Limpiar</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="row g-4">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php
            $img = !empty($row['imagen']) && file_exists("admin/img/" . $row['imagen'])
                   ? "admin/img/" . $row['imagen']
                   : "assets/img/no-image.png";
          ?>
          <div class="col-md-4">
            <div class="card product-card">
              <img src="<?= $img ?>" alt="<?= htmlspecialchars($row['nombre']) ?>" onerror="this.src='assets/img/no-image.png'">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
                <p class="text-muted small">Categoría: <?= htmlspecialchars($row['categoria'] ?? 'General') ?></p>
                <p class="price">\$<?= number_format($row['precio'], 2) ?></p>
                <a href="detalle_producto.php?id=<?= $row['id'] ?>" class="btn btn-outline-dark w-100">
                  Ver detalle <i class="bi bi-box-arrow-in-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12 text-center">
          <p class="text-muted">No hay productos disponibles en esta categoría.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Botón flotante para volver arriba -->
  <a href="#top" class="btn-flotante" title="Volver arriba">
    <i class="bi bi-chevron-up"></i>
  </a>

  <footer>
    <p>&copy; <?= date('Y') ?> PowerStreet. Todos los derechos reservados.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
