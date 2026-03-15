<?php
session_start();
include("includes/db.php");

// Verificar sesión y rol
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ventas') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_promociones_ventas.php");
    exit();
}

$id = intval($_GET['id']);

// Verificar si el producto tiene promoción
$stmt = $conn->prepare("SELECT nombre FROM productos WHERE id = ? AND promocion = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Promoción no encontrada o ya eliminada.";
    exit();
}

$producto = $resultado->fetch_assoc();

// Procesar eliminación
$stmt = $conn->prepare("UPDATE productos SET promocion = 0, precio_promocion = NULL WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_promociones_ventas.php?eliminado=1");
exit();
?>
