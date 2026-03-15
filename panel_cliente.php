<?php
session_start();
include("includes/db.php");

// Verifica si el usuario ha iniciado sesión y es cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Cliente';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel del Cliente </title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom right, #fffaf2, #f7e9c5);
            color: #333;
        }

        header {
            background-color: #f6dfb6;
            color: #a5722b;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        header h1 {
            margin: 0;
        }

        .contenedor {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            padding: 40px;
        }

        .tarjeta {
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .tarjeta:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .tarjeta h2 {
            margin: 15px 0 10px;
            color: #b8914b;
        }

        .tarjeta i {
            font-size: 45px;
            color: #d4af73;
        }

        .saludo {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
        }

        .cerrar {
            margin: 40px auto 0;
            display: block;
            width: fit-content;
            padding: 10px 20px;
            background-color: #d4af73;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .cerrar:hover {
            background-color: #b8914b;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>

    <header>
        <h1>Bienvenido al Panel de Cliente</h1>
    </header>

    <p class="saludo">Hola, <strong><?= htmlspecialchars($nombre_usuario) ?></strong> 👋</p>

    <div class="contenedor">
        <a href="mis_pedidos.php" class="tarjeta">
            <i class="fas fa-box-open"></i>
            <h2>Mis Pedidos</h2>
            <p>Consulta el estado y historial de tus pedidos.</p>
        </a>

        <a href="perfil.php" class="tarjeta">
            <i class="fas fa-user-edit"></i>
            <h2>Editar Perfil</h2>
            <p>Actualiza tus datos personales.</p>
        </a>

        <a href="promociones.php" class="tarjeta">
            <i class="fas fa-tags"></i>
            <h2>Promociones</h2>
            <p>Aprovecha las mejores ofertas en ropa seleccionada por tiempo limitado.</p>
        </a>


    </div>

    </div>

    <a href="index.php" class="cerrar" style="margin-top: 20px;"><i class="fas fa-arrow-left"></i> Volver al inicio</a>
    <a href="logout.php" class="cerrar"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>

</body>

</html>