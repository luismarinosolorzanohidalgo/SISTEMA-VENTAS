<?php
session_start();
include("includes/db.php");

// Verificar acceso correcto al rol ventas
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ventas') {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'] ?? 'Vendedor';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Ventas | PowerStreet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --dorado: #d4af37;
      --dorado-suave: #f5e6b3;
      --blanco: #ffffff;
    }

    body {
      background-color: var(--blanco);
      font-family: 'Segoe UI', sans-serif;
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
    }

    .btn-sesion {
      background-color: white;
      color: #d4af37;
      border: 2px solid #d4af37;
      border-radius: 0.75rem;
      font-weight: bold;
    }

    .btn-sesion:hover {
      background-color: #fff6da;
      color: #b89d2f;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <span class="navbar-brand"><i class="fa-solid fa-cash-register me-2"></i>PowerStreet - Ventas</span>
    <div class="ms-auto">
      <a href="index.php" class="btn btn-sesion me-2"><i class="fa-solid fa-house"></i> Inicio</a>
      <a href="logout.php" class="btn btn-sesion"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="text-center welcome">
    <h3>Bienvenido, <?= htmlspecialchars($usuario) ?> 🧾</h3>
    <p class="text-muted">Accede a funciones de gestión y seguimiento de ventas.</p>
  </div>

  <div class="row mt-4">

    <!-- Pedidos a procesar -->
    <div class="col-md-6 col-lg-3 mb-4">
      <div class="card text-center p-4">
        <div class="card-body">
          <i class="fas fa-truck card-icon mb-3"></i>
          <h5 class="card-title">Pedidos</h5>
          <p class="card-text">Gestiona pedidos recibidos y su estado.</p>
          <a href="pedidos.php" class="btn btn-custom">Ver Pedidos</a>
        </div>
      </div>
    </div>

    <!-- Historial de ventas -->
    <div class="col-md-6 col-lg-3 mb-4">
      <div class="card text-center p-4">
        <div class="card-body">
          <i class="fas fa-clock-rotate-left card-icon mb-3"></i>
          <h5 class="card-title">Historial</h5>
          <p class="card-text">Consulta las ventas realizadas.</p>
          <a href="historial_ventas.php" class="btn btn-custom">Ver Historial</a>
        </div>
      </div>
    </div>

    <!-- Vistas o estadísticas -->
    <div class="col-md-6 col-lg-3 mb-4">
      <div class="card text-center p-4">
        <div class="card-body">
          <i class="fas fa-chart-line card-icon mb-3"></i>
          <h5 class="card-title">Vistas</h5>
          <p class="card-text">Visualiza el impacto y visitas.</p>
          <a href="vistas.php" class="btn btn-custom">Ver Vistas</a>
        </div>
      </div>
    </div>

    <!-- Promociones -->
    <div class="col-md-6 col-lg-3 mb-4">
      <div class="card text-center p-4">
        <div class="card-body">
          <i class="fas fa-tags card-icon mb-3"></i>
          <h5 class="card-title">Promociones</h5>
          <p class="card-text">Muestra o crea promociones activas.</p>
          <a href="admin_promociones_ventas.php" class="btn btn-custom">Ver Promociones</a>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
