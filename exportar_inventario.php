<?php
include("includes/db.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=inventario.xls");
header("Pragma: no-cache");
header("Expires: 0");

$categoriasDisponibles = ["Calzado", "Deportivo", "Accesorio", "Ropa", "Otros"];
$categoria = $_GET['categoria'] ?? '';

echo "<table border='0' style='width:100%;'>";
echo "<tr><td colspan='4' align='center'><img src='img/logo.png' height='80'></td></tr>";
echo "<tr><td colspan='4' align='center'><h2>Inventario PowerStreet</h2></td></tr>";
if ($categoria && in_array($categoria, $categoriasDisponibles)) {
    echo "<tr><td colspan='4' align='center'><strong>Categoría: $categoria</strong></td></tr>";
}
echo "</table><br>";

$query = "SELECT id, nombre, categoria, stock FROM productos";
$params = [];

if ($categoria && in_array($categoria, $categoriasDisponibles)) {
    $stmt = $conn->prepare("SELECT id, nombre, categoria, stock FROM productos WHERE categoria = ? ORDER BY nombre ASC");
    $stmt->bind_param("s", $categoria);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $resultado = $conn->query("SELECT id, nombre, categoria, stock FROM productos ORDER BY nombre ASC");
}

echo "<table border='1'>";
echo "<tr style='background-color: #f2f2f2;'>
        <th>ID</th>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Stock</th>
      </tr>";

while ($row = $resultado->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>" . htmlspecialchars($row['nombre']) . "</td>
            <td>{$row['categoria']}</td>
            <td>{$row['stock']}</td>
          </tr>";
}

echo "</table>";
?>
