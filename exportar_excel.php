<?php
include("includes/db.php");

$fecha_inicio = $_GET['inicio'] ?? '';
$fecha_fin = $_GET['fin'] ?? '';

$where = "";
$params = [];

if ($fecha_inicio && $fecha_fin) {
    $where = "WHERE p.fecha BETWEEN ? AND ?";
    $params[] = $fecha_inicio . " 00:00:00";
    $params[] = $fecha_fin . " 23:59:59";
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=reportes_pedidos.xls");
header("Pragma: no-cache");
header("Expires: 0");

$sql = "
    SELECT p.id, u.nombres AS cliente, p.fecha, p.total, p.estado
    FROM pedidos p
    JOIN usuarios u ON p.usuario_id = u.id
    $where
    ORDER BY p.fecha DESC
";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param("ss", ...$params);
}
$stmt->execute();
$resultado = $stmt->get_result();

// Logo
$logo = "img/logo.png"; // Reemplaza con tu ruta real

echo "
<table>
    <tr>
        <td colspan='5' align='center'>
            <img src='$logo' height='100'><br>
            <h2 style='color:#bfa14b;'>Reporte de Pedidos</h2>
        </td>
    </tr>
</table>
";

echo "<table border='1' cellpadding='5'>
<tr style='background-color:#f5e6b3; font-weight:bold;'>
    <th>ID Pedido</th>
    <th>Cliente</th>
    <th>Fecha</th>
    <th>Total (S/)</th>
    <th>Estado</th>
</tr>";

while ($row = $resultado->fetch_assoc()) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['cliente']}</td>
        <td>" . date("d/m/Y H:i", strtotime($row['fecha'])) . "</td>
        <td>" . number_format($row['total'], 2) . "</td>
        <td>" . ucfirst($row['estado']) . "</td>
    </tr>";
}
echo "</table>";
?>
