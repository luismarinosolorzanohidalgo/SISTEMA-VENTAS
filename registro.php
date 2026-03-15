<?php
session_start();
include("includes/db.php");

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres = trim($_POST['nombres']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmar = $_POST['confirmar'];
    $rol = "cliente"; // Fijo por seguridad

    if ($password !== $confirmar) {
        $mensaje = "⚠️ Las contraseñas no coinciden.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "⚠️ El correo no es válido.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $verificar = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verificar->bind_param("s", $email);
        $verificar->execute();
        $verificar->store_result();

        if ($verificar->num_rows > 0) {
            $mensaje = "⚠️ El correo ya está registrado.";
        } else {
            $stmt = $conn->prepare("INSERT INTO usuarios (nombres, email, password, rol) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombres, $email, $hash, $rol);

            if ($stmt->execute()) {
                $mensaje = "✅ Registro exitoso. Ahora puedes iniciar sesión.";
            } else {
                $mensaje = "❌ Error al registrar usuario.";
            }
            $stmt->close();
        }
        $verificar->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #f9f7f1, #e0e0e0);
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .form-container {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 450px;
        }
        .form-container img.logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 120px;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #1b1b1b;
            font-weight: 600;
        }
        label {
            font-weight: 500;
            color: #333;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-primary {
            background: #1b1b1b;
            border: none;
            border-radius: 10px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: #343a40;
        }
        .mensaje {
            background-color: #f8d7da;
            color: #842029;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 500;
        }
        .mensaje.ok {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .extra {
            text-align: center;
            margin-top: 20px;
            font-size: 0.95rem;
        }
        .extra a {
            color: #1b1b1b;
            font-weight: 500;
            text-decoration: none;
        }
        .extra a:hover {
            color: #6c757d;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <img src="img/logo.png" alt="Logo" class="logo">
    <h2>Crear cuenta</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="mensaje <?= str_contains($mensaje, '✅') ? 'ok' : '' ?>">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="mb-3">
            <label>Nombre completo</label>
            <input type="text" name="nombres" class="form-control" placeholder="Tu nombre" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="correo@gmail.com" required>
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" placeholder="********" required>
        </div>
        <div class="mb-3">
            <label>Confirmar contraseña</label>
            <input type="password" name="confirmar" class="form-control" placeholder="********" required>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary">Registrarme</button>
        </div>
    </form>

    <div class="extra">
        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
    </div>
</div>

</body>
</html>


