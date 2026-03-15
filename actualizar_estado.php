<?php
session_start();
include("../includes/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = $_POST['pedido_id'];
    $nuevo_estado = $_POST['nuevo_estado'];

    $stmt = $conn->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
    $stmt->bind_param("ii", $nuevo_estado, $pedido_id);
    $stmt->execute();
}

header("Location: pedidos.php");
exit();
