<?php
include('includes/db.php');

$query = "SELECT * FROM productos WHERE promocion = 1 AND precio_promocion IS NOT NULL ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Promociones | PowerStreet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Estilo personalizado -->
  <style>
    :root {
      --dorado: #d4af37;
      --dorado-oscuro: #b8914b;
      --blanco: #ffffff;
      --gris-claro: #f9f9f9;
      --texto: #333;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--gris-claro);
      color: var(--texto);
    }

    h2.title {
      color: var(--dorado);
      font-weight: bold;
      text-align: center;
      margin-bottom: 2rem;
    }

    .btn-volver {
      background-color: var(--dorado);
      color: white;
      border: none;
      font-weight: 600;
      transition: background 0.3s;
    }

    .btn-volver:hover {
      background-color: var(--dorado-oscuro);
    }

    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: scale(1.02);
      box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
    }

    .card-img-top {
      height: 220px;
      object-fit: contain;
      padding: 1rem;
      border-radius: 16px 16px 0 0;
      background-color: var(--blanco);
      border-bottom: 1px solid #eee;
    }

    .badge-oferta {
      position: absolute;
      top: 0;
      left: 0;
      background: var(--dorado);
      color: var(--blanco);
      font-size: 0.75rem;
      padding: 0.45em 0.9em;
      border-radius: 0 0.5rem 0.5rem 0;
      z-index: 10;
    }

    .precio-original {
      text-decoration: line-through;
      color: #999;
      font-size: 0.9rem;
    }

    .precio-promocion {
      font-size: 1.4rem;
      font-weight: bold;
      color: var(--dorado);
    }

    .btn-ver {
      border: 2px solid var(--dorado);
      color: var(--dorado);
      font-weight: 600;
      transition: 0.3s ease-in-out;
    }

    .btn-ver:hover {
      background-color: var(--dorado);
      color: white;
    }

    footer {
      margin-top: 4rem;
      text-align: center;
      color: #777;
      padding: 1rem;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

  <main class="container py-5">
    <!-- Botón Volver -->
    <div class="d-flex justify-content-start mb-3">
      <a href="panel_cliente.php" class="btn btn-volver">
        <i class="bi bi-arrow-left"></i> Volver
      </a>
    </div>

    <!-- Título -->
    <h2 class="title">🎯 Promociones Exclusivas - PowerStreet</h2>

    <!-- Tarjetas -->
    <div class="row g-4">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($producto = $result->fetch_assoc()): ?>
          <?php
            $img = (!empty($producto['imagen']) && file_exists("admin/img/" . $producto['imagen']))
              ? "admin/img/" . $producto['imagen']
              : "admin/img/default.png";
          ?>
          <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card position-relative h-100">
              <span class="badge-oferta">OFERTA</span>
              <img src="<?= $img ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="card-img-top">
              <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                <p class="text-muted mb-2"><?= htmlspecialchars($producto['categoria'] ?? 'Sin categoría') ?></p>
                <div class="mb-2">
                  <div class="precio-original">S/ <?= number_format($producto['precio'], 2) ?></div>
                  <div class="precio-promocion">S/ <?= number_format($producto['precio_promocion'], 2) ?></div>
                </div>
                <a href="detalle_producto.php?id=<?= $producto['id'] ?>" class="btn btn-ver w-100 mt-2">
                  <i class="bi bi-eye"></i> Ver Producto
                </a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12">
          <div class="alert alert-warning text-center p-4">
            🚫 No hay promociones disponibles por el momento.
          </div>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <footer>
    &copy; <?= date('Y') ?> PowerStreet | Todos los derechos reservados.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
