<?php
session_start();
include("includes/db.php");

// Validación de sesión
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$mensaje = '';
$clase_mensaje = 'mensaje-exito';

// Procesar subida de imagen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Subir foto
    if (isset($_FILES['foto'])) {
        $archivo = $_FILES['foto'];
        $nombreTemporal = $archivo['tmp_name'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extension, $permitidas)) {
            $nuevoNombre = "perfil_" . $usuario_id . "." . $extension;
            $rutaDestino = "uploads/" . $nuevoNombre;

            if (move_uploaded_file($nombreTemporal, $rutaDestino)) {
                $stmt = $conn->prepare("UPDATE usuarios SET foto = ? WHERE id = ?");
                $stmt->bind_param("si", $nuevoNombre, $usuario_id);
                $stmt->execute();
                $mensaje = "✅ Foto actualizada correctamente.";
            } else {
                $mensaje = "❌ Error al subir la imagen.";
                $clase_mensaje = 'mensaje-error';
            }
        } else {
            $mensaje = "❌ Formato inválido. Solo JPG, PNG, GIF.";
            $clase_mensaje = 'mensaje-error';
        }
    }

    // Eliminar foto
    if (isset($_POST['eliminar_foto'])) {
        // Obtener nombre actual
        $res = $conn->query("SELECT foto FROM usuarios WHERE id = $usuario_id");
        $fotoActual = $res->fetch_assoc()['foto'] ?? '';

        if ($fotoActual && $fotoActual !== 'default.png') {
            $ruta = "uploads/" . $fotoActual;
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }

        // Actualizar en BD
        $stmt = $conn->prepare("UPDATE usuarios SET foto = 'default.png' WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $mensaje = "✅ Foto de perfil eliminada.";
    }
}

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT nombres, email, rol, foto FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

$nombre = $usuario['nombres'];
$email = $usuario['email'];
$rol = $usuario['rol'];
$foto = $usuario['foto'] ?? 'default.png';
$rutaFoto = "uploads/" . $foto;
if (!file_exists($rutaFoto)) {
    $rutaFoto = "uploads/default.png";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil | PowerStreet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --dorado: #d4af37;
            --dorado-suave: #f5e6b3;
            --blanco: #ffffff;
            --gris: #f3f3f3;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom right, #fffaf2, #fceccc);
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: var(--dorado);
            padding: 20px;
            text-align: center;
            color: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .perfil-container {
            max-width: 620px;
            margin: 50px auto;
            background: #fff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 20px;
            object-fit: cover;
            border: 4px solid var(--dorado);
        }

        h2 {
            text-align: center;
            color: #b8914b;
            margin-bottom: 30px;
        }

        .campo {
            margin-bottom: 20px;
        }

        .campo label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        .campo span {
            display: block;
            background: var(--gris);
            padding: 12px;
            border-radius: 8px;
        }

        form {
            text-align: center;
            margin-top: 25px;
        }

        input[type="file"] {
            margin-top: 10px;
            padding: 8px;
            background-color: var(--gris);
            border: none;
            border-radius: 8px;
        }

        .btn-accion {
            margin: 10px 6px 0;
            background: var(--dorado);
            border: none;
            color: #fff;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-accion:hover {
            background: #b8914b;
        }

        .mensaje-exito {
            margin-top: 20px;
            text-align: center;
            color: #2e7d32;
            font-weight: bold;
        }

        .mensaje-error {
            margin-top: 20px;
            text-align: center;
            color: #c62828;
            font-weight: bold;
        }

        .acciones {
            text-align: center;
            margin-top: 35px;
        }

        .acciones a {
            text-decoration: none;
            color: var(--dorado);
            font-weight: bold;
            transition: color 0.2s;
        }

        .acciones a:hover {
            color: #b8914b;
        }

        .botones {
            margin-top: 15px;
        }
    </style>
</head>
<body>

<header>
    <h1><i class="fas fa-user-circle"></i> Mi Perfil</h1>
</header>

<div class="perfil-container">

    <img src="<?= htmlspecialchars($rutaFoto) ?>" alt="Foto de perfil" class="avatar">

    <h2><?= htmlspecialchars($nombre) ?></h2>

    <div class="campo">
        <label><i class="fas fa-user"></i> Nombre:</label>
        <span><?= htmlspecialchars($nombre) ?></span>
    </div>

    <div class="campo">
        <label><i class="fas fa-envelope"></i> Email:</label>
        <span><?= htmlspecialchars($email) ?></span>
    </div>

    <div class="campo">
        <label><i class="fas fa-user-tag"></i> Rol:</label>
        <span><?= htmlspecialchars($rol) ?></span>
    </div>

    <!-- Formulario para subir imagen -->
    <form method="POST" enctype="multipart/form-data">
        <label for="foto"><i class="fas fa-camera"></i> Cambiar foto de perfil:</label><br>
        <input type="file" name="foto" required accept="image/*"><br>
        <div class="botones">
            <button type="submit" class="btn-accion"><i class="fas fa-upload"></i> Guardar</button>
        </div>
    </form>

    <!-- Botón para eliminar imagen -->
    <form method="POST">
        <input type="hidden" name="eliminar_foto" value="1">
        <button type="submit" class="btn-accion" style="background-color:#dc3545;"><i class="fas fa-trash-alt"></i> Eliminar Foto</button>
    </form>

    <?php if ($mensaje): ?>
        <div class="<?= $clase_mensaje ?>"><?= $mensaje ?></div>
    <?php endif; ?>

    <div class="acciones">
        <a href="panel_cliente.php"><i class="fas fa-arrow-left"></i> Volver al panel</a>
    </div>
</div>

</body>
</html>
