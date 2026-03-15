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

$result = $conn->query("SELECT * FROM productos");

$rol = $_SESSION['rol'] ?? null;
$usuario_id = $_SESSION['usuario_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>PowerStreet - Tienda Deportiva</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #f5f3ef;
      color: #1c1c1c;
      margin: 0;
      padding: 0;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 220px;
      height: 100vh;
      background: #2d2d2d;
      padding-top: 30px;
      z-index: 1000;
    }

    .sidebar img {
      max-width: 100px;
      margin: 0 auto 20px;
      display: block;
    }

    .sidebar a {
      display: block;
      color: #fff;
      padding: 15px 20px;
      text-decoration: none;
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background-color: #444;
    }

    .navbar {
      background-color: #1c1c1c;
      color: white;
      padding: 0.7rem 1rem;
      position: fixed;
      top: 0;
      left: 220px;
      right: 0;
      z-index: 999;
      display: flex;
      align-items: center;
    }

    .navbar img {
      height: 40px;
      margin-right: 10px;
    }

    .navbar-brand {
      font-size: 1.5rem;
      font-weight: bold;
      color: white;
      text-decoration: none;
    }

    .content {
      margin-left: 220px;
      padding: 100px 20px 40px;
    }

    .hero-section {
      background: linear-gradient(135deg, #ded2c6, #eae7e1);
      color: #1c1c1c;
      padding: 60px 20px 40px;
      border-radius: 15px;
      margin-bottom: 30px;
    }

    .product-card {
      border-radius: 15px;
      overflow: hidden;
      background: white;
      transition: 0.3s ease;
      border: 1px solid #ddd;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .product-card img {
      height: 240px;
      object-fit: contain;
      background: #fafafa;
      border-bottom: 1px solid #eee;
      width: 100%;
    }

    .btn-outline-dark {
      color: #1c1c1c;
      border-color: #1c1c1c;
      font-weight: bold;
    }

    .btn-outline-dark:hover {
      background-color: #1c1c1c;
      color: white;
    }

    .seccion-titulo {
      color: #d4af37;
      font-weight: bold;
      margin: 50px 0 25px;
      text-align: center;
    }

    footer {
      background: #2d2d2d;
      color: #fff;
      text-align: center;
      padding: 20px 0;
      margin-top: 40px;
      border-top: 3px solid #c2b280;
    }

    .logo-visible {
      background: white;
      padding: 10px;
      border-radius: 50%;
      box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }

      .navbar {
        left: 0;
      }

      .content {
        margin-left: 0;
      }
    }
  </style>
</head>

<body>

  <!-- SIDEBAR -->
  <div class="sidebar">
    <img src="img/logo.png" alt="Logo" class="logo-visible">
    <a href="index.php"><i class="bi bi-house-door me-2"></i>Inicio</a>
    <a href="producto.php"><i class="bi bi-bag-check me-2"></i>Productos</a>
    <a href="nosotros.php"><i class="bi bi-people me-2"></i>Nosotros</a>
    <a href="contacto.php"><i class="bi bi-envelope me-2"></i>Contacto</a>

    <?php if (!$usuario_id): ?>
      <a href="login.php"><i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión</a>
    <?php else: ?>
      <?php if ($rol === 'admin'): ?>
        <a href="admin/panel.php"><i class="bi bi-speedometer2 me-2"></i>Panel Admin</a>
      <?php elseif ($rol === 'cliente'): ?>
        <a href="panel_cliente.php"><i class="bi bi-receipt-cutoff me-2"></i>Panel Cliente</a>
      <?php elseif ($rol === 'almacenero'): ?>
        <a href="panel_almacen.php"><i class="bi bi-boxes me-2"></i>Panel Almacén</a>
      <?php elseif ($rol === 'ventas'): ?>
        <a href="panel_ventas.php"><i class="bi bi-currency-dollar me-2"></i>Panel Ventas</a>
      <?php endif; ?>
      <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Salir</a>
    <?php endif; ?>
  </div>

  <!-- NAVBAR -->
  <nav class="navbar">
    <img src="img/logo.png" alt="Logo" class="logo-visible">
    <a class="navbar-brand" href="#">PowerStreet</a>
  </nav>

  <!-- CONTENIDO -->
  <div class="content">
    <section class="hero-section text-center">
      <h1 class="display-5 fw-bold">Tu estilo, tu rendimiento</h1>
      <p class="fs-5">Encuentra lo mejor en calzado, ropa y accesorios deportivos</p>
    </section>

    <main class="container">
      <!-- NOVEDADES -->
      <h2 class="seccion-titulo"> Novedades</h2>
      <div class="row g-4">
        <?php
        mysqli_data_seek($result, 0);
        while ($row = $result->fetch_assoc()):
          if (!empty($row['promocion']) && !empty($row['precio_promocion'])) continue;

          $img = (!empty($row['imagen']) && file_exists("admin/img/" . $row['imagen']))
            ? "admin/img/" . $row['imagen']
            : "admin/img/zapatilla_blanco.png";
        ?>
          <div class="col-md-4">
            <div class="card product-card">
              <img src="<?= $img ?>" alt="<?= htmlspecialchars($row['nombre']) ?>">
              <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
                <p class="text-muted small"><?= htmlspecialchars($row['categoria'] ?? 'Sin categoría') ?></p>
                <p class="fw-bold text-success fs-5">$<?= number_format($row['precio'], 2) ?></p>
                <a href="detalle_producto.php?id=<?= $row['id'] ?>" class="btn btn-outline-dark w-100 mt-2">Ver más</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>

      <!-- PROMOCIONES -->
      <h2 class="seccion-titulo"> Promociones</h2>
      <div class="row g-4">
        <?php
        mysqli_data_seek($result, 0);
        while ($row = $result->fetch_assoc()):
          if (empty($row['promocion']) || empty($row['precio_promocion'])) continue;

          $img = (!empty($row['imagen']) && file_exists("admin/img/" . $row['imagen']))
            ? "admin/img/" . $row['imagen']
            : "admin/img/zapatilla_blanco.png";
        ?>
          <div class="col-md-4">
            <div class="card product-card">
              <img src="<?= $img ?>" alt="<?= htmlspecialchars($row['nombre']) ?>">
              <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
                <p class="text-muted small"><?= htmlspecialchars($row['categoria'] ?? 'Sin categoría') ?></p>
                <p class="fw-bold fs-5">
                  <span class="text-decoration-line-through text-muted me-2">$<?= number_format($row['precio'], 2) ?></span>
                  <span class="text-danger">$<?= number_format($row['precio_promocion'], 2) ?></span>
                </p>
                <a href="detalle_producto.php?id=<?= $row['id'] ?>" class="btn btn-outline-dark w-100 mt-2">Ver más</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </main>

    <!-- FOOTER -->
    <footer>
      <p>&copy; <?= date('Y') ?> PowerStreet. Todos los derechos reservados.</p>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
