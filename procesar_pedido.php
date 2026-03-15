<?php
session_start();
include("includes/db.php");

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si hay productos en el carrito
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$carrito = $_SESSION['carrito'];
$metodo_pago = $_POST['metodo_pago'] ?? 'No especificado';

// Validación del método de pago
$metodo_pago = in_array($metodo_pago, ['Yape', 'Efectivo']) ? $metodo_pago : 'Otro';

// Calcular total del pedido
$total = 0;
foreach ($carrito as $id => $cantidad) {
    $stmt = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $producto = $res->fetch_assoc();
        $total += $producto['precio'] * $cantidad;
    }
}

// Insertar el pedido en la base de datos
$stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, total, metodo_pago) VALUES (?, ?, ?)");
$stmt->bind_param("ids", $usuario_id, $total, $metodo_pago);
$stmt->execute();
$id_pedido = $stmt->insert_id;

// Insertar detalles del pedido
foreach ($carrito as $id => $cantidad) {
    $stmt = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $producto = $res->fetch_assoc();
        $precio = $producto['precio'];

        $stmt_detalle = $conn->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
        $stmt_detalle->bind_param("iiid", $id_pedido, $id, $cantidad, $precio);
        $stmt_detalle->execute();
    }
}

// Limpiar el carrito
unset($_SESSION['carrito']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesando tu pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #dde2f1, #f1f5fb);
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #ffffffee;
            border-radius: 20px;
            padding: 40px;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #5f72be;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            animation: spin 1s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .redirecting-text {
            margin-top: 20px;
            font-size: 1rem;
            color: #555;
        }
    </style>

    <script>
        setTimeout(() => {
            window.location.href = "gracias.php?pedido=<?= $id_pedido ?>";
        }, 3000);
    </script>
</head>
<body>
    <div class="card">
        <div class="loader mb-4"></div>
        <h4 class="fw-bold text-primary">¡Procesando tu pedido!</h4>
        <p class="redirecting-text">Espera unos segundos mientras te redirigimos a la página de confirmación...</p>
    </div>
</body>
</html>
