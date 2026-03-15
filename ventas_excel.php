<?php
include("includes/db.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ventas_powerstreet.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='0' style='width:100%;'>";
echo "<tr><td colspan='5' align='center'><img src='img/logo.png' height='80'></td></tr>";
echo "<tr><td colspan='5' align='center'><h2>Historial de Ventas - PowerStreet</h2></td></tr>";
echo "</table><br>";

$sql = "SELECT p.id, p.fecha, p.total, p.metodo_pago, u.nombres AS cliente
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.fecha DESC";
$resultado = $conn->query($sql);

echo "<table border='1'>";
echo "<tr style='background-color: #f2f2f2;'>
        <th>ID</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Método de Pago</th>
        <th>Total</th>
      </tr>";

$total_ventas = 0;

while ($row = $resultado->fetch_assoc()) {
    $total_ventas += $row['total'];
    echo "<tr>
            <td>#{$row['id']}</td>
            <td>" . htmlspecialchars($row['cliente']) . "</td>
            <td>" . date('d/m/Y H:i', strtotime($row['fecha'])) . "</td>
            <td>" . ucfirst($row['metodo_pago']) . "</td>
            <td>S/ " . number_format($row['total'], 2) . "</td>
          </tr>";
}

echo "<tr style='font-weight:bold; background-color:#fff9e6;'>
        <td colspan='4' align='right'>Total Generado:</td>
        <td>S/ " . number_format($total_ventas, 2) . "</td>
      </tr>";
echo "</table>";
?>
