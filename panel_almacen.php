<?php
session_start();
include("includes/db.php");

$rol = $_SESSION['rol'] ?? null;
$usuario_id = $_SESSION['usuario_id'] ?? null;
$usuario = $_SESSION['usuario'] ?? 'Invitado';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Almacén | PowerStreet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <style>
    :root {
      --dorado: #d4af37;
      --dorado-suave: #f5e6b3;
      --blanco: #ffffff;
    }

    body {
      background-color: var(--blanco);
      font-family: 'Segoe UI', sans-serif;
      animation: fadeInBody 1s ease-in;
    }

    .navbar {
      background-color: var(--dorado);
      padding: 1rem;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .navbar-brand {
      color: white;
      font-weight: bold;
      font-size: 1.5rem;
    }

    .navbar a {
      color: #fff;
      font-weight: 500;
      margin-left: 10px;
    }

    .navbar a:hover {
      color: #2b2b2b;
    }

    .welcome {
      color: #333;
      margin-top: 1rem;
    }

    .card {
      border: none;
      border-radius: 1rem;
      background-color: var(--blanco);
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      animation: fadeInCard 0.7s ease-in;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-icon {
      font-size: 3rem;
      color: var(--dorado);
    }

    .btn-custom {
      background-color: var(--dorado);
      color: white;
      border-radius: 0.75rem;
      font-weight: 600;
    }

    .btn-custom:hover {
      background-color: #b89d2f;
      color: white;
    }

    .btn-sesion {
      background-color: white;
      color: #d4af37;
      border: 2px solid #d4af37;
      border-radius: 0.75rem;
      font-weight: bold;
      transition: all 0.3s ease;
    }

    .btn-sesion:hover {
      background-color: #fff6da;
      color: #b89d2f;
    }

    @keyframes fadeInBody {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes fadeInCard {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <span class="navbar-brand"><i class="fa-solid fa-warehouse me-2"></i>PowerStreet - Almacén</span>
      <div class="ms-auto">
        <a href="index.php" class="btn btn-sesion me-2"><i class="fa-solid fa-house"></i> Inicio</a>
        <a href="logout.php" class="btn btn-sesion"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <div class="text-center welcome">
      <h3 class="animate__animated animate__fadeInDown">Bienvenido, <?= htmlspecialchars($usuario) ?> 👋</h3>
      <p class="text-muted animate__animated animate__fadeIn">Accede a las funciones de inventario y gestión de productos.</p>
    </div>

    <div class="row mt-4">
      <!-- Ver Inventario -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card text-center p-4">
          <div class="card-body">
            <i class="fas fa-boxes card-icon mb-3"></i>
            <h5 class="card-title">Ver Inventario</h5>
            <p class="card-text">Consulta el stock de todos los productos registrados.</p>
            <a href="ver_inventario.php" class="btn btn-custom">Ver Inventario</a>
          </div>
        </div>
      </div>

      <!-- Agregar Producto -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card text-center p-4">
          <div class="card-body">
            <i class="fas fa-plus-circle card-icon mb-3"></i>
            <h5 class="card-title">Agregar Producto</h5>
            <p class="card-text">Registra un nuevo artículo en el inventario.</p>
            <a href="agregar_producto.php" class="btn btn-custom">Agregar</a>
          </div>
        </div>
      </div>

      <!-- Reportes -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card text-center p-4">
          <div class="card-body">
            <i class="fas fa-file-alt card-icon mb-3"></i>
            <h5 class="card-title">Reportes</h5>
            <p class="card-text">Genera reportes de inventario y movimientos.</p>
            <a href="reportes.php" class="btn btn-custom">Ver Reportes</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
