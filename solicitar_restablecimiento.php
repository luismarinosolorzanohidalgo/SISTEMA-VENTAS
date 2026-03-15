<?php
session_start();
include("includes/db.php");

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Verificar si existe el email
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($usuario_id);
        $stmt->fetch();

        // Verificar si ya hay una solicitud pendiente
        $verifica = $conn->prepare("SELECT id FROM restablecimientos WHERE usuario_id = ?");
        $verifica->bind_param("i", $usuario_id);
        $verifica->execute();
        $verifica->store_result();

        if ($verifica->num_rows > 0) {
            $mensaje = "⚠️ Ya tienes una solicitud pendiente.";
        } else {
            // Insertar nueva solicitud
            $insertar = $conn->prepare("INSERT INTO restablecimientos (usuario_id, email, fecha_restablecimiento) VALUES (?, ?, NOW())");
            $insertar->bind_param("is", $usuario_id, $email);
            if ($insertar->execute()) {
                $mensaje = "✅ Solicitud enviada. Un administrador la revisará.";
            } else {
                $mensaje = "❌ Error al enviar la solicitud.";
            }
        }
    } else {
        $mensaje = "⚠️ El correo no está registrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Restablecimiento</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + Fuente -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f2e9e4, #e0e0e0);
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .form-box {
            background: #ffffff;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 420px;
        }

        .form-box img.logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 100px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
            color: #1b1b1b;
        }

        .form-label {
            font-weight: 500;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            background-color: #1b1b1b;
            border: none;
            border-radius: 10px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #343a40;
        }

        .mensaje {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 10px;
            font-weight: 500;
            text-align: center;
        }

        .mensaje.ok {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .mensaje.err {
            background-color: #f8d7da;
            color: #842029;
        }

        .text-center a {
            color: #1b1b1b;
            text-decoration: none;
            font-weight: 500;
        }

        .text-center a:hover {
            text-decoration: underline;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="form-box">

        <!-- Logo -->
        <img src="img/logo.png" alt="Logo" class="logo">

        <h2>Restablecer Contraseña</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?= str_contains($mensaje, '✅') ? 'ok' : 'err' ?>">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="correo@gmail.com">
            </div>
            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary">Solicitar</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="login.php">← Volver al inicio de sesión</a>
        </div>
    </div>
</body>
</html>
