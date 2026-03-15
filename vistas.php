<?php
session_start();
include("includes/db.php");

// Verificación de acceso
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'ventas') {
    header("Location: login.php");
    exit();
}

$resultado = $conn->query("SELECT pagina, vistas FROM vistas ORDER BY vistas DESC");

$total_vistas = 0;
$vistas = [];

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $vistas[] = $fila;
        $total_vistas += $fila['vistas'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas de Vistas | PowerStreet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #fffdf5;
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            padding: 40px 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        h2 {
            color: #d4af37;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 130px;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn {
            border-radius: 10px;
        }

        .btn-exportar {
            background-color: #d4af37;
            color: white;
        }

        .btn-exportar:hover {
            background-color: #c39d2f;
        }

        .btn-limpiar {
            background-color: #fff;
            border: 2px solid #d4af37;
            color: #d4af37;
        }

        .btn-limpiar:hover {
            background-color: #fffbe5;
        }

        .btn-volver {
            background-color: #d4af37;
            color: white;
            font-weight: bold;
            margin-top: 20px;
        }

        .btn-volver:hover {
            background-color: #b99928;
        }

        .resumen {
            text-align: right;
            font-weight: bold;
            margin-top: 15px;
            font-size: 1.1rem;
        }

        footer {
            text-align: center;
            font-size: 0.9rem;
            color: #777;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<div class="container">
    <img src="img/logo.png" class="logo" alt="Logo PowerStreet">
    <h2>📈 Estadísticas de Vistas</h2>

    <div class="row mb-3 align-items-center">
        <div class="col-md-6 mb-2">
            <input type="text" id="buscador" class="form-control shadow-sm" placeholder="🔍 Buscar página...">
        </div>

        <div class="col-6 col-md-2 mb-2">
            <button onclick="limpiarFiltro()" class="btn w-100 btn-limpiar">
                <i class="fas fa-eraser me-1"></i> Limpiar
            </button>
        </div>

        <div class="col-6 col-md-2 mb-2">
            <a href="exportar_vistas.php" class="btn w-100 btn-exportar" target="_blank">
                <i class="fas fa-file-excel me-1"></i> Excel
            </a>
        </div>

        <div class="col-12 col-md-2 mb-2">
            <button onclick="window.print()" class="btn w-100 btn-exportar">
                <i class="fas fa-print me-1"></i> PDF
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle" id="tablaVistas">
            <thead class="table-light">
                <tr>
                    <th>Página</th>
                    <th>Vistas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vistas as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila['pagina']) ?></td>
                        <td><?= $fila['vistas'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="resumen">
        Total de vistas: <span style="color:#b98d1e;"><?= number_format($total_vistas) ?></span>
    </div>

    <a href="panel_ventas.php" class="btn btn-volver"><i class="fas fa-arrow-left"></i> Volver</a>
</div>

<footer>
    &copy; <?= date('Y') ?> PowerStreet | Todos los derechos reservados.
</footer>

<script>
function limpiarFiltro() {
    document.getElementById("buscador").value = "";
    const filas = document.querySelectorAll("#tablaVistas tbody tr");
    filas.forEach(f => f.style.display = "");
}

document.getElementById('buscador').addEventListener('keyup', function () {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll("#tablaVistas tbody tr");

    filas.forEach(fila => {
        const texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});
</script>

</body>
</html>
