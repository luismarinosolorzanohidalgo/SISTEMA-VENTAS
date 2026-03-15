<?php
include("includes/db.php");

$inicio = $_GET['inicio'] ?? '';
$fin = $_GET['fin'] ?? '';

$where = "";
$params = [];

if ($inicio && $fin) {
    $where = "WHERE fecha BETWEEN ? AND ?";
    $params[] = $inicio . " 00:00:00";
    $params[] = $fin . " 23:59:59";
}

// Conexión segura con prepared statements
$sql1 = "SELECT DATE(fecha) as dia, SUM(total) as total FROM pedidos $where GROUP BY dia ORDER BY dia";
$sql2 = "SELECT estado, COUNT(*) as cantidad FROM pedidos $where GROUP BY estado";

$data = ['fechas' => ['labels' => [], 'valores' => []], 'estados' => ['labels' => [], 'valores' => []]];

if ($stmt = $conn->prepare($sql1)) {
    if (!empty($params)) $stmt->bind_param("ss", ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $data['fechas']['labels'][] = $row['dia'];
        $data['fechas']['valores'][] = $row['total'];
    }
    $stmt->close();
}

if ($stmt = $conn->prepare($sql2)) {
    if (!empty($params)) $stmt->bind_param("ss", ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $data['estados']['labels'][] = ucfirst($row['estado']);
        $data['estados']['valores'][] = $row['cantidad'];
    }
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($data);
