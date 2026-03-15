<?php
session_start();
include("includes/db.php");

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validación de campos vacíos
    if (empty($email) || empty($password)) {
        $mensaje = "⚠️ Por favor, completa todos los campos.";
    } else {
        // Buscar al usuario por correo
        $stmt = $conn->prepare("SELECT id, nombres, password, rol FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $nombres, $hash, $rol);
            $stmt->fetch();

            // Verificar contraseña
            if (password_verify($password, $hash)) {
                // Guardar datos en la sesión
                $_SESSION['usuario_id']  = $id;
                $_SESSION['usuario']     = $nombres;  // nombre corto
                $_SESSION['rol']         = $rol;

                // Ir al index principal
                header("Location: index.php");
                exit;
            } else {
                $mensaje = "⚠️ Contraseña incorrecta.";
            }
        } else {
            $mensaje = "⚠️ El correo no está registrado.";
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Estilos & fuentes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #f6f3ea, #e9ecef);
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            background: #fff;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 420px;
        }

        .form-container img.logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 120px;
        }

        h2 {
            text-align: center;
            color: #1b1b1b;
            margin-bottom: 25px;
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
            background-color: #1f1f1f;
            border: none;
            border-radius: 10px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #333;
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

        .extra {
            text-align: center;
            margin-top: 20px;
            font-size: 0.95rem;
        }

        .extra a {
            color: #1f1f1f;
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
        <!-- Logo personalizado -->
        <img src="img/logo.png" alt="Logo" class="logo">

        <h2>Iniciar sesión</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" class="form-control" placeholder="correo@gmail.com" required>
            </div>
            <div class="mb-3">
                <label for="password">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="********" required>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
        </form>

        <div class="extra">
            <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
            <p><a href="solicitar_restablecimiento.php">¿Olvidaste tu contraseña?</a></p>
        </div>
    </div>
</body>

</html>