<?php
include("includes/db.php");

header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
header("Content-Disposition: attachment; filename=vistas_powerstreet.xls");
header("Pragma: no-cache");
header("Expires: 0");

$result = $conn->query("SELECT pagina, vistas FROM vistas ORDER BY vistas DESC");

$total = 0;
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
    $total += $row['vistas'];
}

// Logo y encabezado
echo "<table border='0' style='width:100%;'>";
echo "<tr><td colspan='3' align='center'><img src='img/logo.png' height='80'></td></tr>";
echo "<tr><td colspan='3' align='center'><h2>Reporte de Vistas - PowerStreet</h2></td></tr>";
echo "</table><br>";

// Tabla de datos
echo "<table border='1' cellpadding='6' cellspacing='0' style='width:100%; border-collapse:collapse;'>";
echo "<tr style='background-color:#f5e6b3; font-weight:bold;'>
        <th>Página</th>
        <th>Vistas</th>
        <th>% del Total</th>
      </tr>";

foreach ($data as $row) {
    $porcentaje = $total > 0 ? round(($row['vistas'] / $total) * 100, 2) : 0;
    echo "<tr>
            <td>" . htmlspecialchars($row['pagina']) . "</td>
            <td align='center'>{$row['vistas']}</td>
            <td align='center'>{$porcentaje} %</td>
          </tr>";
}

echo "<tr style='background-color:#fff9db; font-weight:bold;'>
        <td align='right'>TOTAL:</td>
        <td align='center'>{$total}</td>
        <td></td>
      </tr>";

echo "</table>";
?>
