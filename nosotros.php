<?php
session_start();
include("includes/db.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Nosotros - PowerStreet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Iconos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Estilos personalizados -->
  <style>
    body {
      background-color: #ffffff;
      color: #333;
      font-family: 'Segoe UI', sans-serif;
    }

    .navbar {
      background-color: #fff;
      border-bottom: 1px solid #eee;
    }

    .navbar-brand {
      font-weight: bold;
      color: #bfa046 !important;
    }

    .section-nosotros {
      background-color: #fff8e1;
      padding: 60px 20px;
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      margin: 40px auto;
      max-width: 1100px;
    }

    .section-nosotros h2 {
      color: #bfa046;
      font-weight: bold;
      font-size: 36px;
      margin-bottom: 20px;
    }

    .section-nosotros p {
      font-size: 18px;
      line-height: 1.8;
    }

    .icon-box {
      text-align: center;
      padding: 30px;
      background-color: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s ease;
      cursor: pointer;
    }

    .icon-box:hover {
      transform: translateY(-5px);
    }

    .icon-box i {
      font-size: 40px;
      color: #bfa046;
      margin-bottom: 15px;
    }

    .mensaje-expandido {
      display: none;
      background-color: #fffbe9;
      border-left: 5px solid #bfa046;
      border-radius: 12px;
      padding: 25px;
      margin-top: 20px;
      font-size: 1.1rem;
      animation: fadeIn 0.3s ease-in-out;
    }

    .mensaje-expandido.active {
      display: block;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    footer {
      background-color: #f8f9fa;
      padding: 30px 0;
      text-align: center;
      color: #888;
      margin-top: 50px;
    }

    .btn-gold {
      background-color: #f5c242;
      color: white;
      border: none;
    }

    .btn-gold:hover {
      background-color: #e0ae32;
      color: white;
    }

    .volver-btn {
      margin-top: 40px;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="index.php">PowerStreet</a>
    </div>
  </nav>

  <!-- Sección Nosotros -->
  <section class="section-nosotros">
    <div class="container">
      <h2 class="text-center mb-5">¿Quiénes Somos?</h2>
      <div class="row align-items-center">
        <div class="col-md-6">
          <p>En <strong>PowerStreet</strong> nos dedicamos a ofrecer lo mejor en ropa deportiva, combinando estilo, comodidad y rendimiento. Nuestra misión es inspirar a cada persona a alcanzar su máximo potencial, llevando prendas que no solo se ven bien, sino que funcionan bien.</p>
          <p>Con una amplia variedad de productos, desde ropa de entrenamiento hasta atuendos casuales, trabajamos con materiales de alta calidad para brindarte una experiencia única. Nuestro equipo está comprometido con la innovación constante y con brindar atención personalizada.</p>
        </div>
        <div class="col-md-6 text-center">
          <img src="img/logo.png" alt="Nuestro equipo" class="img-fluid rounded shadow">
        </div>
      </div>

      <!-- Tarjetas -->
      <div class="row text-center mt-5">
        <div class="col-md-4">
          <div class="icon-box" onclick="mostrarMensaje('premium')">
            <i class="bi bi-star-fill"></i>
            <h5 class="mt-3">Calidad Premium</h5>
            <p>Prendas diseñadas para durar y rendir en cualquier situación.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="icon-box" onclick="mostrarMensaje('personalizada')">
            <i class="bi bi-people-fill"></i>
            <h5 class="mt-3">Atención Personalizada</h5>
            <p>Estamos contigo en cada paso de tu experiencia de compra.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="icon-box" onclick="mostrarMensaje('innovacion')">
            <i class="bi bi-rocket-takeoff-fill"></i>
            <h5 class="mt-3">Innovación Constante</h5>
            <p>Siempre a la vanguardia de la moda y la tecnología deportiva.</p>
          </div>
        </div>
      </div>

      <!-- Mensajes Expandidos -->
      <div id="mensaje-premium" class="mensaje-expandido mt-4">
        <strong>Calidad Premium:</strong> Utilizamos telas técnicas de alto rendimiento, resistentes al desgaste y con tecnología de absorción, diseñadas para los entrenamientos más exigentes y el estilo diario.
      </div>

      <div id="mensaje-personalizada" class="mensaje-expandido mt-4">
        <strong>Atención Personalizada:</strong> Nos enfocamos en cada cliente, brindando recomendaciones únicas, atención directa y soluciones rápidas. ¡Queremos que vivas una experiencia única con PowerStreet!
      </div>

      <div id="mensaje-innovacion" class="mensaje-expandido mt-4">
        <strong>Innovación Constante:</strong> Incorporamos nuevas tecnologías en cada temporada, combinando funcionalidad, diseño y sostenibilidad en cada prenda que lanzamos al mercado.
      </div>
    </div>
    <div class="text-center volver-btn">
      <a href="index.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Volver
      </a>
    </div>

  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; <?= date("Y") ?> PowerStreet. Todos los derechos reservados.</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Script para mostrar mensajes -->
  <script>
    function mostrarMensaje(tipo) {
      // Ocultar todos
      document.getElementById('mensaje-premium').classList.remove('active');
      document.getElementById('mensaje-personalizada').classList.remove('active');
      document.getElementById('mensaje-innovacion').classList.remove('active');

      // Mostrar solo el seleccionado
      document.getElementById('mensaje-' + tipo).classList.add('active');
    }
  </script>
</body>

</html>